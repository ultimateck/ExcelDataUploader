<?php

namespace App\Services\Domain;

use App\Services\BaseService;
use App\Models\ExcelFile;
use App\Models\Item;
use App\Imports\ExcelDataImport;
use App\Support\DomainConst;
use App\Exceptions\InvalidExcelFile;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Exception;

class ExcelFileService extends BaseService
{
    public function storeFileData(array $data): array
    {
        $excelFile = new ExcelFile();
        // Store the file in storage\app\public\uploads folder
        $file = $data['excel-file'];
        $fileName = $file->getClientOriginalName();
        $filePath = $file->store('uploads', 'public');

        // Store file information in the database
        try {
            $excelFile->file_name = $fileName;
            $excelFile->description = $data['description'];
            $excelFile->extention = $file->extension();
            $excelFile->path = $filePath;
            $excelFile->file = null;
            $excelFile->status = DomainConst::PROCESSING_STATE_PENDING;
            $excelFile->processed = false;
            $excelFile->save();
            $result = [
                'error' => false, 
                'message' => 'File Uploaded Successfully', 
                'excelFile' => $excelFile,
                'storagePath' => $filePath
            ];
        } catch (Exception $e) {
            Log::channel('stderr')->info($e->getMessage());
            $result = ['error' => true, 'message' => $e->getMessage()];
        }
        
        return $result;
    }

    public function processFileData(ExcelFile $excelFile): array
    {
        try {
            // Import excel file from storage and process as a collection
            $dataCollection = $this->importExcel(storage_path('app/public/' . $excelFile->path));
            $items = $this->processExcel($dataCollection);

            // Store processed items
            $this->storeProcessedItems($items);

            // Update file data for successfull process
            $this->updateFileData($excelFile , DomainConst::PROCESSING_STATE_COMPLETED, true);
            $result = ['error' => false, 'message' => 'File Processed Successfully', 'excelFile' => $excelFile];
        } catch (Exception $e) {
            // Update file data for error process
            $this->updateFileData($excelFile , DomainConst::PROCESSING_STATE_FAILED, true, $e->getMessage());
            Log::channel('stderr')->info($e->getMessage());
            $result = ['error' => true, 'message' => $e->getMessage()];
        }
        return $result;
    }

    public function updateFileData(ExcelFile $excelFile, string $status, bool $processed, string $errors = null): void
    {
        try {
            $excelFile->status = $status;
            $excelFile->processed = $processed;
            $excelFile->errors = $errors;
            $excelFile->save();
        } catch (Exception $e) {
            Log::channel('stderr')->info($e->getMessage());
        }
    }

    public function getAllUploadedFiles(): Collection
    {
        $allFiles = ExcelFile::all();
        return $allFiles;
    }

    public function importExcel(string $path): Collection
    {
        $dataCollection = Excel::toCollection(new ExcelDataImport, $path);
        return $dataCollection;
    }

    public function processExcel(Collection $dataCollection): array
    {
        $items = [];
        foreach($dataCollection[0] as $key => $row) {
            if (!$row->has('code') || !$row->has('quantity') || !$row->has('price')) {
                throw(new InvalidExcelFile("Required item columns are missing."));
            }
            if (
                !Str::startsWith($row->get('code'), 'IT') ||
                !is_numeric($row->get('quantity')) ||
                !is_numeric($row->get('price'))
            ) {
                continue;
            }
            
            $item = Item::where('code', $row->get('code'))->first();
            if (is_null($item)) {
                $item = new Item();
                $item->code = $row->get('code');
            }
            $item->description = $row->get('description');
            $item->quantity = $row->get('quantity');
            $item->price = $row->get('price');
            $items[] = $item;
        }

        return $items;
    }

    public function storeProcessedItems(array $items): void
    {
        foreach($items as $item) {
            $item->save();
        }
    }
}

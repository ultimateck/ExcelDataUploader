<?php

namespace App\Services\Domain;

use App\Services\BaseService;
use App\Models\ExcelFile;
use App\Imports\ExcelDataImport;
use Maatwebsite\Excel\Facades\Excel;
use Exception;
use Illuminate\Support\Collection;
use stdClass;

class ExcelFileService extends BaseService
{
    public function storeFileData($data): array
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
            $excelFile->status = 'PENDING';
            $excelFile->processed = false;
            $excelFile->save();
            $result = ['error' => false, 'message' => 'File Uploaded Successfully', 'excelFile' => $excelFile];
        } catch (Exception $e) {
            $result = ['error' => true, 'message' => $e->getMessage()];
        }
        
        return $result;
    }

    public function processFileData($excelFile): array
    {
        try {
            $dataCollection = Excel::toCollection(new ExcelDataImport, storage_path('app/public/' . $excelFile->path));
            $this->processExcel($dataCollection);
            $excelFile->status = 'DONE';
            $excelFile->processed = true;
            $excelFile->save();
            $result = ['error' => false, 'message' => 'File Processed Successfully', 'excelFile' => $excelFile];
        } catch (Exception $e) {
            $result = ['error' => true, 'message' => $e->getMessage()];
        }
        return $result;
    }

    public function processExcel(Collection $dataCollection): void
    {
        foreach($dataCollection as $key => $row) {
            // Do something
        }
    }
}

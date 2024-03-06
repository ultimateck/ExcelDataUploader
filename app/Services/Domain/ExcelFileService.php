<?php

namespace App\Services\Domain;

use App\Services\BaseService;
use App\Models\ExcelFile;
use Exception;

class ExcelFileService extends BaseService
{
    public function storeFileData($data): array
    {
        $excelFile = new ExcelFile();
        // Store the file in storage\app\public folder
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
            $result = ['error' => false, 'message' => 'File Uploaded Successfully'];
        } catch (Exception $e) {
            $result = ['error' => true, 'message' => $e->getMessage()];
        }
        
        return $result;
    }
}

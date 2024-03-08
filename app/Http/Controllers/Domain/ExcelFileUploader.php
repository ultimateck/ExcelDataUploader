<?php

namespace App\Http\Controllers\Domain;

use App\Http\Controllers\Controller as BaseController;
use App\Http\Requests\Domain\ExcelFileUploadRequest;
use App\Services\Domain\ExcelFileService;
use App\Jobs\ProcessExcelData;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;

class ExcelFileUploader extends BaseController
{
    public function getUpload(ExcelFileService $excelFileService)
    {
        $uploadedFiles = $excelFileService->getAllUploadedFiles();
        return View::make('domain.excel_file_upload')
            ->with('uploadedFiles', $uploadedFiles);
    }
    
    public function postUpload(ExcelFileUploadRequest $request, ExcelFileService $excelFileService)
    {
        $rtnObj = $excelFileService->storeFileData($request->all());
        if ($rtnObj['error'] == false) {
            dispatch(new ProcessExcelData($rtnObj['excelFile']));
            //$excelFileService->processFileData($rtnObj['excelFile']);
        }
        return Redirect::route('getUpload')
            ->with('message', $rtnObj['message']);
    }
}
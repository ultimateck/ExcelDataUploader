<?php

namespace App\Http\Controllers\Domain;

use App\Http\Controllers\Controller as BaseController;
use App\Http\Requests\Domain\ExcelFileUploadRequest;
use App\Services\Domain\ExcelFileService;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;

class ExcelFileUploader extends BaseController
{
    public function getUpload()
    {
        return View::make('domain.excel_file_upload')
            ->with('message', '');
    }
    
    public function postUpload(ExcelFileUploadRequest $request, ExcelFileService $excelFileService)
    {
        $rtnObj = $excelFileService->storeFileData($request->all());
        return Redirect::route('getUpload')
            ->with('message', 'File Submitted');
    }
}
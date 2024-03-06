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
    public function getUpload()
    {
        return View::make('domain.excel_file_upload')
            ->with('message', '');
    }
    
    public function postUpload(ExcelFileUploadRequest $request, ExcelFileService $excelFileService)
    {
        $rtnObj = $excelFileService->storeFileData($request->all());
        if ($rtnObj['error'] == false) {
            dispatch(new ProcessExcelData($rtnObj['excelFile']));
        }
        return Redirect::route('getUpload')
            ->with('message', $rtnObj['message']);
    }
}
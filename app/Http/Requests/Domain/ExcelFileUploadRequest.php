<?php

namespace App\Http\Requests\Domain;

use App\Http\Requests\BaseRequest;
use Illuminate\Support\Facades\Request;

class ExcelFileUploadRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'excel-file' => 'required|file|mimes:csv,xlxs|max:2048'
        ];
    }
}
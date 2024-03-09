<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\Domain\ExcelFileService;

class ExcelFileServiceTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_excel_file_service_method_exists(): void
    {
        $this->assertTrue(method_exists(ExcelFileService::class, 'storeFileData'));
        $this->assertTrue(method_exists(ExcelFileService::class, 'processFileData'));
        $this->assertTrue(method_exists(ExcelFileService::class, 'updateFileData'));
        $this->assertTrue(method_exists(ExcelFileService::class, 'importExcel'));
        $this->assertTrue(method_exists(ExcelFileService::class, 'processExcel'));
        $this->assertTrue(method_exists(ExcelFileService::class, 'storeProcessedItems'));
    }
}

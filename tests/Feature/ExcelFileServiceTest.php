<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\Domain\ExcelFileService;
use App\Exceptions\InvalidExcelFile;
use Tests\TestCase;

class ExcelFileServiceTest extends TestCase
{
    /**
     * Test storeFileData() method.
     */
    public function test_store_file_data(): void
    {
        $excelFileService = new ExcelFileService();
        $file = UploadedFile::fake()->createWithContent('items.xlsx', file_get_contents('tests/Files/items.xlsx'));
        $rtn = $excelFileService->storeFileData(['excel-file' => $file, 'description' => 'Test Description']);

        $this->assertIsArray($rtn);
        $this->assertFalse($rtn['error']);
        $this->assertEquals('File Uploaded Successfully', $rtn['message']);
        $this->assertTrue(Storage::exists('public/' . $rtn['storagePath']));

        Storage::delete('public/' . $rtn['storagePath']);
    }

    /**
     * Test storeFileData() method.
     */
    public function test_process_file_data(): void
    {
        $excelFileService = new ExcelFileService();
        $file = UploadedFile::fake()->createWithContent('items.xlsx', file_get_contents('tests/Files/items.xlsx'));
        $rtn1 = $excelFileService->storeFileData(['excel-file' => $file, 'description' => 'Test Description']);
        $rtn2 = $excelFileService->processFileData($rtn1['excelFile']);

        $this->assertFalse($rtn2['error']);

        Storage::delete('public/' . $rtn1['storagePath']);
    }

    /**
     * Test getAllUploadedFiles() method.
     */
    public function test_all_uploaded_files(): void
    {
        $excelFileService = new ExcelFileService();
        $allFiles = $excelFileService->getAllUploadedFiles();
        $this->assertIsIterable($allFiles);
    }

    /**
     * Test importExcel(string $path) method.
     */
    public function test_import_excel(): void
    {
        $excelFileService = new ExcelFileService();
        $dataCollection = $excelFileService->importExcel('tests/Files/items.xlsx');
        $this->assertIsIterable($dataCollection);
        $rows = $dataCollection[0];
        $this->assertIsIterable($rows);
        $firstItemRow = $rows[0];
        $this->assertCount(4, $firstItemRow);
        $this->assertTrue($firstItemRow->has('code'));
        $this->assertEquals('IT001', $firstItemRow->get('code'));
    }

    /**
     * Test processExcel(Collection $dataCollection) method with valid excel file.
     */
    public function test_process_excel(): void
    {
        $excelFileService = new ExcelFileService();
        $dataCollection = $excelFileService->importExcel('tests/Files/items.xlsx');
        $items = $excelFileService->processExcel($dataCollection);
        $this->assertIsIterable($items);
        $this->assertCount(6, $items);
    }

    /**
     * Test processExcel(Collection $dataCollection) method with invalid excel file.
     * Column names are invalid
     */
    public function test_process_excel_with_invalid_column_excel(): void
    {
        $excelFileService = new ExcelFileService();
        $dataCollection = $excelFileService->importExcel('tests/Files/items-invalid-columns.xlsx');
        //$this->expectException(InvalidExcelFile::class);
        $this->assertThrows(
            fn () => $excelFileService->processExcel($dataCollection),
            InvalidExcelFile::class
        );
    }

    /**
     * Test processExcel(Collection $dataCollection) method with invalid excel file.
     * Load data ignoring invalid rows.
     * Valid row count is 2
     */
    public function test_process_excel_with_invalid_rows_excel(): void
    {
        $excelFileService = new ExcelFileService();
        $dataCollection = $excelFileService->importExcel('tests/Files/items-invalid-rows.xlsx');
        $items = $excelFileService->processExcel($dataCollection);
        $this->assertIsIterable($items);
        $this->assertCount(2, $items);
    }
}

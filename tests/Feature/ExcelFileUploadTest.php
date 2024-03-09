<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ExcelFileUploadTest extends TestCase
{
    /**
     * Test /upload route post request without data
     */
    public function test_file_upload_route_without_data(): void
    {
        $response = $this->post('/upload');

        $response->assertStatus(302);
        $response->assertInvalid(['excel-file']);
    }

    /**
     * Test /upload route post request without data
     */
    public function test_file_upload_route_with_invalid_mime_type(): void
    {
        $file = UploadedFile::fake()->create('test.jpg', 20);
        $response = $this->post('/upload',[
            'excel-file' => $file,
            'description' => 'Test File'
        ]);

        $response->assertStatus(302);
        $response->assertInvalid(['excel-file']);
    }

    /**
     * Test /upload route post request without data
     */
    public function test_file_upload_route_with_valid_file(): void
    {
        $file = UploadedFile::fake()->createWithContent('items.xlsx', file_get_contents('tests/Files/items.xlsx'));
        $response = $this->post('/upload',[
            'excel-file' => $file,
            'description' => 'Test File'
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('message');
        $this->assertTrue(Storage::exists('public/uploads/' . $file->hashName()));

        Storage::delete('public/uploads/' . $file->hashName());
    }
}

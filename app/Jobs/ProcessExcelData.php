<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Services\Domain\ExcelFileService;

class ProcessExcelData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $excelFile;

    /**
     * Create a new job instance.
     */
    public function __construct($excelFile)
    {
        $this->excelFile = $excelFile;
    }

    /**
     * Execute the job.
     */
    public function handle(ExcelFileService $excelFileService): void
    {
        Log::channel('stderr')->info('Processing Excel File: ' . $this->excelFile->file_name . ' [stderr]');

        $rtnObj = $excelFileService->processFileData($this->excelFile);
        if ($rtnObj['error'] == true) {
            Log::channel('stderr')->error('Processing Error: ' . $this->excelFile->file_name . ' [message]: ' . $rtnObj['message']);
        } else {
            Log::channel('stderr')->info('Processing Completed: ' . $this->excelFile->file_name . ' [message]: ' . $rtnObj['message']);
        }
    }
}

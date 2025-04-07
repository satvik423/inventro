<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProductsImport;
use Illuminate\Support\Facades\Log;

class ProcessProductImport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $filePath;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $fullPath = storage_path('app/' . $this->filePath);
        Log::info('Processing file:', ['file' => $fullPath]);

        if (!file_exists($fullPath)) {
            Log::error('File does not exist!', ['file' => $fullPath]);
            throw new \Exception("File not found at: " . $fullPath);
        }

        Excel::import(new ProductsImport, $fullPath);
        Log::info('Import completed successfully');
    }
}

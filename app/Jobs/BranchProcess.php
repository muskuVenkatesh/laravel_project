<?php
namespace App\Jobs;

use Illuminate\Http\File;
use App\Imports\UsersImport;
use App\Imports\BranchImport;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;


class BranchProcess implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $base64File;
    protected $fileName;

    /**
     * Create a new job instance.
     *
     * @param string $base64File
     * @param string $fileName
     * @return void
     */
    public function __construct($base64File, $fileName)
    {
        $this->base64File = $base64File;
        $this->fileName = $fileName;


    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $fileContent = base64_decode($this->base64File);
            $tempFilePath = tempnam(sys_get_temp_dir(), 'excel');
            file_put_contents($tempFilePath, $fileContent);
            Excel::import(new BranchImport, $tempFilePath);
            unlink($tempFilePath);
            return response()->json(['message' => 'School Excel import completed successfully'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }
}

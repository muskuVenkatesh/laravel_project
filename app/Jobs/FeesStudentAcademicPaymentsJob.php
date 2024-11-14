<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\FeesStudentAcademicPayments;

class FeesStudentAcademicPaymentsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    protected $fees_academic_id;
    protected $data;

    /**
     * Create a new job instance.
     *
     * @param string $base64File
     * @param string $fileName
     * @return void
     */
    public function __construct($fees_academic_id,  $data)
    {
        $this->fees_academic_id = $fees_academic_id;
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        FeesStudentAcademicPayments::createStudentAcademicPayments($this->fees_academic_id,  $this->data);
    }
}

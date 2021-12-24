<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mail;
use App\Models\Testtaker;
use App\Http\Controllers\V1\ReportController;
use PDF;

class SendEmailPDF implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        foreach ($this->data['lists'] as $id) {
            
            $reportController = (new ReportController);
            $arrayData = $reportController->report_data($id);
            $pdf = PDF::loadView('report', $arrayData);
            
            $inputs['name'] = $arrayData['taker']['name'];
            $inputs['email'] = $arrayData['taker']['email'];

            Mail::send('mail.candidate', $inputs, function($message) use($inputs, $pdf){
                $message->to($inputs['email'], $inputs['name'])
                    ->attachData($pdf->output(), 'performance_report.pdf')
                    ->subject('Performance Report');
            });
        }
    }
}

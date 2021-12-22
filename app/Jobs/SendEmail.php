<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mail;
use App\Models\Assigncandidate;
use App\Models\Test;

class SendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;
    protected $test_url;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
        $this->test_url = env('APP_URL').'/online-test/';
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        
        $subject = $this->data['subject'];

        foreach ($this->data['lists'] as $list) {
            
            $inputs = [
                'email' => $list['email'],
                'name' => $list['email'],
                'subject' => $subject
            ];
            
            $job_role = '';
            if($list['test_id']) {
                $test = Test::where('id', $list['test_id'])->first();
                if(!empty($test)) {
                    $job_role = $test->name;
                }
            }

            $link = $this->test_url.$list['test']['public_id'].'/'.$list['id'];
            
            $text_message = str_replace(['{test_link}', '{candidate}', '{job_role}'], [$link, $list['email'], $job_role], $this->data['message']);
           
            $data = [
                        'start' => $list['start'],
                        'end' => $list['end'],
                        'resume' => $list['resume'],
                        'text_message' => $text_message
                    ];

            Mail::send('mail.share', $data, function($message) use($inputs){
                $message->to($inputs['email'], $inputs['name'])
                    ->subject($inputs['subject']);
            });

            Assigncandidate::where('id', $list['id'])->update(['status' => '0', 'share' => 1, 'updated_at' => Date('Y-m-d H:i:s')]);
        }
    }
}

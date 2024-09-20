<?php

namespace App\Jobs;

use Log;
use Exception;
use App\Models\User;
use App\Mail\SendMail;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;
    public $subject;
    // public $tries=50;

    /**
     * Create a new job instance.
     *
     * @param string $subject
     * @param $user
    //  * @param User $user
     */
    // public function __construct($subject, User $user)
    public function __construct($subject,$user)
    {
        $this->subject = $subject;
        $this->user = $user; // Correct assignment
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        try {
            Mail::to("maharjan@gmail.com")->send(new SendMail($this->subject, $this->user));
            // Mail::to($this->user->email)->send(new SendMail($this->subject, $this->user));
        } catch (Exception $e) {
$this->release(30);
        }
    }
}

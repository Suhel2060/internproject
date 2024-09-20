<?php

namespace App\Console\Commands;

use App\Jobs\ProcessMail;
use App\Mail\SendMail;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;

class TestSendMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-send-mail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
    dispatch(new ProcessMail("This is job test","suhel") );
    Artisan::call('queue:work');
    }
}

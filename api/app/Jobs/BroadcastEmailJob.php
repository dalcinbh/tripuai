<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

use App\Mail\BroadcastMail;

class BroadcastEmailJob implements ShouldQueue
{
    use Queueable;


    protected $subject;
    protected $body;

    /**
     * Create a new job instance.
     */
    public function __construct($subject, $body)
    {
        $this->connection = 'rabbitmq';
        $this->subject = $subject;
        $this->body = $body;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //send email to all users not admins
        $users = User::where('is_admin', false)->get();
        foreach ($users as $user) {
            Mail::to($user->email)->send(new BroadcastMail($this->subject, $this->body));
        }
    }
}

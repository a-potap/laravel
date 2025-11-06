<?php

namespace App\Jobs;

use App\Mail\CommentNew;
use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class CommentNotify implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public Comment $comment
    ){
        $this->onQueue('notify');
    }

    public function handle(): void
    {
        $queueName = $this->job->getQueue();
        
        if ($queueName === 'potap.comments.notify_mail') {
            Mail::to(config('app.admin_mail'))->send(new CommentNew($this->comment));
        }

        if ($queueName === 'potap.comments.notify_tel') {
            Log::info('Notify telegram');
        }
    }
}

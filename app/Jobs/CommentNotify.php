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

class CommentNotify implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Comment $comment
    ){}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to(config('app.admin_mail'))->send(new CommentNew($this->comment));
    }
}

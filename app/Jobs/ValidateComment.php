<?php

namespace App\Jobs;

use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ValidateComment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected array $commentData;


    public function __construct(array $commentData)
    {
        $this->commentData = $commentData;
        $this->onQueue('validation');
    }


    public function handle(): void
    {
        if (!empty($this->commentData['text']) && strip_tags($this->commentData['text']) === $this->commentData['text']) {
            $comment = Comment::create($this->commentData);
            CommentNotify::dispatch($comment);
        }
    }
}

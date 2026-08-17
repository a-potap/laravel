<?php

namespace App\Console\Commands;

use App\Mail\WeeklyCommentReport;
use App\Models\Comment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendWeeklyCommentReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:weekly-comments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send weekly comment count report to admin';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $startOfWeek = now()->subWeek()->startOfWeek()->toDateString();
        $endOfWeek = now()->subWeek()->endOfWeek()->toDateString();

        $commentsByBlog = Comment::leftJoin('blog', 'blog_coments.blog_id', '=', 'blog.id')
            ->whereBetween('blog_coments.date', [$startOfWeek, $endOfWeek])
            ->selectRaw('blog.id as blog_id, blog.title, COUNT(*) as comments_count')
            ->groupBy('blog.id', 'blog.title', 'blog.title_en')
            ->get();

        $report = [];
        foreach ($commentsByBlog as $item) {
            $report[] = [
                'blog_id' => $item->blog_id,
                'blog_title' => $item->title,
                'comments_count' => $item->comments_count,
            ];
        }

        $totalComments = array_sum(array_column($report, 'comments_count'));

        Mail::to(config('mail.admin'))
            ->send(new WeeklyCommentReport(
                report: $report,
                periodStart: $startOfWeek,
                periodEnd: $endOfWeek,
                totalComments: $totalComments,
            ));

        $this->info("Weekly comment report sent to admin. Total comments: {$totalComments}");

        return Command::SUCCESS;
    }
}

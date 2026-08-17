<?php

namespace App\Console\Commands;

use App\Mail\WeeklyCommentReport;
use App\Models\Blog;
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

        $commentsByBlog = Comment::whereBetween('date', [$startOfWeek, $endOfWeek])
            ->selectRaw('blog_id, COUNT(*) as comments_count')
            ->groupBy('blog_id')
            ->get();

        $report = [];
        foreach ($commentsByBlog as $item) {
            $blog = Blog::find($item->blog_id);
            $report[] = [
                'blog_id' => $item->blog_id,
                'blog_title' => $blog ? $blog->getLocalizedTitle() : 'Unknown Post',
                'comments_count' => $item->comments_count,
            ];
        }

        if (empty($report)) {
            $this->info('No comments found for the past week.');
            return Command::SUCCESS;
        }

        $totalComments = array_sum(array_column($report, 'comments_count'));

        Mail::to(config('mail.admin'))
            ->send(new WeeklyCommentReport(
                report: $report,
                periodStart: $startOfWeek,
                periodEnd: $endOfWeek,
            ));

        $this->info("Weekly comment report sent to admin. Total comments: {$totalComments}");

        return Command::SUCCESS;
    }
}

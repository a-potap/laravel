<?php

namespace Tests\Feature;

use App\Console\Commands\SendWeeklyCommentReport;
use App\Mail\WeeklyCommentReport;
use App\Models\Blog;
use App\Models\Comment;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SendWeeklyCommentReportTest extends TestCase
{
    use \Illuminate\Foundation\Testing\RefreshDatabase;
    public function test_command_returns_success_when_no_comments_exist(): void
    {
        Mail::fake();

        $this->artisan(SendWeeklyCommentReport::class)
            ->expectsOutput('No comments found for the past week.')
            ->assertExitCode(0);

        Mail::assertNothingSent();
    }

    public function test_command_sends_email_when_comments_exist(): void
    {
        Mail::fake();
        Config::set('mail.admin', 'admin@example.com');

        $blog = Blog::factory()->create([
            'title' => 'Test Blog Post',
            'title_en' => 'Test Blog Post EN',
        ]);

        $pastWeek = now()->subWeek()->startOfWeek();
        Comment::factory()->count(3)->create([
            'blog_id' => $blog->id,
            'date' => $pastWeek->copy()->addDays(2),
        ]);

        $this->artisan(SendWeeklyCommentReport::class)
            ->expectsOutput('Weekly comment report sent to admin. Total comments: 3')
            ->assertExitCode(0);

        Mail::assertSent(WeeklyCommentReport::class, function ($mail) {
            return $mail->hasTo('admin@example.com');
        });
    }

    public function test_command_sends_mail_to_configured_admin_address(): void
    {
        Mail::fake();
        Config::set('mail.admin', 'custom.admin@test.com');

        $blog = Blog::factory()->create();

        $pastWeek = now()->subWeek()->startOfWeek();
        Comment::factory()->create([
            'blog_id' => $blog->id,
            'date' => $pastWeek->copy()->addDays(1),
        ]);

        $this->artisan(SendWeeklyCommentReport::class)
            ->assertExitCode(0);

        Mail::assertSent(WeeklyCommentReport::class, function ($mail) {
            return $mail->hasTo('custom.admin@test.com');
        });
    }

    public function test_command_sends_correct_report_data(): void
    {
        Mail::fake();
        App::setLocale('en');
        Config::set('mail.admin', 'admin@example.com');

        $blog1 = Blog::factory()->create([
            'title' => 'Post One',
            'title_en' => 'English Post One',
        ]);
        $blog2 = Blog::factory()->create([
            'title' => 'Post Two',
            'title_en' => 'English Post Two',
        ]);

        $pastWeek = now()->subWeek()->startOfWeek();
        Comment::factory()->count(5)->create([
            'blog_id' => $blog1->id,
            'date' => $pastWeek->copy()->addDays(1),
        ]);
        Comment::factory()->count(3)->create([
            'blog_id' => $blog2->id,
            'date' => $pastWeek->copy()->addDays(3),
        ]);

        $this->artisan(SendWeeklyCommentReport::class)
            ->assertExitCode(0);

        Mail::assertSent(WeeklyCommentReport::class, function ($mail) {
            $this->assertCount(2, $mail->report);
            $this->assertEquals(5, $mail->report[0]['comments_count']);
            $this->assertEquals(3, $mail->report[1]['comments_count']);
            $this->assertNotNull($mail->periodStart);
            $this->assertNotNull($mail->periodEnd);

            return true;
        });
    }

    public function test_command_uses_english_title_when_locale_is_en(): void
    {
        Mail::fake();
        App::setLocale('en');
        Config::set('mail.admin', 'admin@example.com');

        $blog = Blog::factory()->create([
            'title' => 'Russian Title',
            'title_en' => 'English Title',
        ]);

        $pastWeek = now()->subWeek()->startOfWeek();
        Comment::factory()->create([
            'blog_id' => $blog->id,
            'date' => $pastWeek->copy()->addDays(2),
        ]);

        $this->artisan(SendWeeklyCommentReport::class)
            ->assertExitCode(0);

        Mail::assertSent(WeeklyCommentReport::class, function ($mail) use ($blog) {
            $this->assertEquals('English Title', $mail->report[0]['blog_title']);

            return true;
        });
    }

    public function test_command_uses_default_title_when_locale_is_not_en(): void
    {
        Mail::fake();
        App::setLocale('ru');
        Config::set('mail.admin', 'admin@example.com');

        $blog = Blog::factory()->create([
            'title' => 'Russian Title',
            'title_en' => 'English Title',
        ]);

        $pastWeek = now()->subWeek()->startOfWeek();
        Comment::factory()->create([
            'blog_id' => $blog->id,
            'date' => $pastWeek->copy()->addDays(2),
        ]);

        $this->artisan(SendWeeklyCommentReport::class)
            ->assertExitCode(0);

        Mail::assertSent(WeeklyCommentReport::class, function ($mail) {
            $this->assertEquals('Russian Title', $mail->report[0]['blog_title']);

            return true;
        });
    }

    public function test_command_handles_blog_with_null_english_title(): void
    {
        Mail::fake();
        App::setLocale('en');
        Config::set('mail.admin', 'admin@example.com');

        $blog = Blog::factory()->create([
            'title' => 'Only Russian Title',
            'title_en' => null,
        ]);

        $pastWeek = now()->subWeek()->startOfWeek();
        Comment::factory()->create([
            'blog_id' => $blog->id,
            'date' => $pastWeek->copy()->addDays(2),
        ]);

        $this->artisan(SendWeeklyCommentReport::class)
            ->assertExitCode(0);

        Mail::assertSent(WeeklyCommentReport::class, function ($mail) {
            $this->assertEquals('Only Russian Title', $mail->report[0]['blog_title']);

            return true;
        });
    }

    public function test_command_ignores_comments_outside_week_range(): void
    {
        Mail::fake();
        Config::set('mail.admin', 'admin@example.com');

        $blog = Blog::factory()->create();

        Comment::factory()->count(10)->create([
            'blog_id' => $blog->id,
            'date' => now()->subWeeks(2)->startOfWeek()->copy()->addDays(2),
        ]);

        $pastWeek = now()->subWeek()->startOfWeek();
        Comment::factory()->count(2)->create([
            'blog_id' => $blog->id,
            'date' => $pastWeek->copy()->addDays(1),
        ]);

        $this->artisan(SendWeeklyCommentReport::class)
            ->expectsOutput('Weekly comment report sent to admin. Total comments: 2')
            ->assertExitCode(0);

        Mail::assertSent(WeeklyCommentReport::class, function ($mail) {
            $this->assertEquals(2, $mail->report[0]['comments_count']);

            return true;
        });
    }

    public function test_command_does_not_send_email_when_no_recent_comments(): void
    {
        Mail::fake();
        Config::set('mail.admin', 'admin@example.com');

        Comment::factory()->count(5)->create([
            'date' => now()->subMonths(3)->startOfWeek(),
        ]);

        $this->artisan(SendWeeklyCommentReport::class)
            ->expectsOutput('No comments found for the past week.')
            ->assertExitCode(0);

        Mail::assertNothingSent();
    }

    public function test_command_aggregates_comments_per_blog_correctly(): void
    {
        Mail::fake();
        Config::set('mail.admin', 'admin@example.com');

        $blog1 = Blog::factory()->create();
        $blog2 = Blog::factory()->create();
        Blog::factory()->create();

        $pastWeek = now()->subWeek()->startOfWeek();
        Comment::factory()->count(10)->create([
            'blog_id' => $blog1->id,
            'date' => $pastWeek->copy()->addDays(1),
        ]);
        Comment::factory()->count(1)->create([
            'blog_id' => $blog2->id,
            'date' => $pastWeek->copy()->addDays(2),
        ]);

        $this->artisan(SendWeeklyCommentReport::class)
            ->expectsOutput('Weekly comment report sent to admin. Total comments: 11')
            ->assertExitCode(0);

        Mail::assertSent(WeeklyCommentReport::class, function ($mail) {
            $this->assertCount(2, $mail->report);

            return true;
        });
    }
}

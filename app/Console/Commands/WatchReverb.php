<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Process;

class WatchReverb extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:watch-reverb';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ensure the Laravel Reverb server stays running';

    /**
     * Execute the console command.
     */
public function handle()
    {
        $checkProcess = Process::run('pgrep -f "artisan reverb:start"');

        if (empty(trim($checkProcess->output()))) {
            $this->info('Reverb is stopped. Starting it now...');
            
            // Execute the start command in the background detached from this terminal
            // The "> /dev/null 2>&1 &" tells Linux to run it silently in the background
            exec('php ' . base_path('artisan') . ' reverb:start > /dev/null 2>&1 &');
        } else {
            $this->info('Reverb is already running smoothly.');
        }
    }
}

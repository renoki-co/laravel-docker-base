<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CurrentTime extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'current-time';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Output the current time.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $currentTime = now()->toIso8601String();

        $this->line("Current time: {$currentTime}");

        return Command::SUCCESS;
    }
}

<?php

namespace App\Console\Commands;

use App\Services\CleaningService;
use Illuminate\Console\Command;

class RunScheduledCleaning extends Command
{
    protected $signature = 'cleaning:run-scheduled';

    protected $description = 'Start cleaning mode if the saved cleaning time has been reached';

    public function handle(CleaningService $cleaning): int
    {
        if ($cleaning->runScheduled()) {
            $this->info('Scheduled cleaning mode started.');
        }

        return self::SUCCESS;
    }
}

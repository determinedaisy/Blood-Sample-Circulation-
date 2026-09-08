<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\BloodAnalyzerService;

class RunBloodAnalyzer extends Command
{
    protected $signature = 'app:run-blood-analyzer';
    protected $description = 'Run AI analysis on inventory and donors';

    public function handle(BloodAnalyzerService $analyzer)
    {
        $analyzer->analyzeInventory();
        $analyzer->analyzeDonors();
        $this->info('AI Blood Analysis completed successfully.');
    }
}
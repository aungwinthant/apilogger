<?php

namespace AWT\Console\Commands;

use AWT\Contracts\ApiLoggerInterface;
use Illuminate\Console\Command;

class ClearApiLogger extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'apilog:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Flush All Records of ApiLogger';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(ApiLoggerInterface $apiLogger)
    {
        $apiLogger->deleteLogs();
        
        $this->info("All records are deleted");
    }
}

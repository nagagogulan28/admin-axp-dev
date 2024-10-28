<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Repository\SettlementRepository;
class SettlementReportGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'settlementreportgenerator:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Genenarate settlement For Each merchant based on  the slot types';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(){
        $this->settlementRepo = app(SettlementRepository::class);
        $this->settlementRepo->generateSettlementreport();
    }
}

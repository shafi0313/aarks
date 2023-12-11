<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Client;
use App\Models\GeneralLedger;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DeleteOldData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:old-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete 6 years old data from database';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $clients = Client::pluck('id')->toArray();
        foreach ($clients as $client) {
            $ledgerData = GeneralLedger::where('client_id', $client)->latest('created_at')->first(['client_id', 'created_at', 'updated_at']);
            if ($ledgerData) {
                $currentDate = Carbon::now();
                $createdAt = Carbon::parse($ledgerData->created_at);
                $updatedAt = Carbon::parse($ledgerData->updated_at);

                if ($createdAt->diffInYears($currentDate) >= 6 && $updatedAt->diffInYears($currentDate) >= 6) {
                    $tables = DB::connection()->getDoctrineSchemaManager()->listTableNames();
                    foreach ($tables as $table) {
                        if (Schema::hasColumn($table, 'client_id') && Schema::hasColumn($table, 'deleted_at')) {
                            DB::table($table)->where('client_id', $client)->update(['deleted_at' => Carbon::now()]);
                        }
                    }
                }
                Client::where('id', $client)->update(['deleted_at' => Carbon::now()]);
            }
        }
        info('Delete Old Data executed successfully');
        // return Command::SUCCESS;
    }
}

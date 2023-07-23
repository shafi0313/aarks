<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ClientPaymentList;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Subscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:subs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command check client payment validity';

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
     * @return int
     */
    public function handle()
    {
        ClientPaymentList::where('is_expire', 0)->where('status', 1)->where('expire_at', '<', now())->update(['status' => 0, 'is_expire' => 1]);
        Log::info('Commands executed successfully');
    }
}

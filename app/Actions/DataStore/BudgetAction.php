<?php
namespace App\Actions\DataStore;

use Exception;
use Carbon\Carbon;
use App\Models\Client;
use App\Models\Profession;
use App\Models\BudgetEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class BudgetAction extends Controller
{
    public function store(Request $request)
    {
        $client     = Client::findOrFail($request->client_id);
        $profession = Profession::findOrFail($request->profession_id);
        $date       = Carbon::parse($request->date)->format('Y-m-d');
        $entries    = $request->entries;
        $tran_id    = transaction_id('BUE');
        // dd($entries);
        $data = [];
        foreach ($entries['chart_id'] as $i => $code) {
            $data[] = [
                'client_id'        => $client->id,
                'profession_id'    => $profession->id,
                'chart_id'         => $code,
                'date'             => $date,
                'tran_id'          => $tran_id,
                'source'           => 'budget',
                'narration'        => 'Budget Entry for ' . $client->fullname . ' for ' . $profession->name . ' at ' . $date,
                'last_year_amount' => $entries['last_year_amount'][$i],
                'old_percent'      => $entries['old_percent'][$i],
                'percent'      => $entries['percent'][$i],
                'amount'           => $entries['budget_amount'][$i],
                'updated_at'       => now(),
                'created_at'       => now(),
            ];
        }
        try {
            BudgetEntry::insert($data);
        } catch (\Exception $e) {
            throw new Exception('Something went wrong');
            // throw $e->getMessage();
        }
    }
    public function update(Request $request)
    {
        $client     = Client::findOrFail($request->client_id);
        $profession = Profession::findOrFail($request->profession_id);
        $date       = Carbon::parse($request->date)->format('Y-m-d');
        $entries    = $request->entries;
        $tran_id    = transaction_id('BUE');
        // dd($entries);
        $data = [];
        foreach ($entries['chart_id'] as $i => $code) {
            $data[] = [
                'client_id'        => $client->id,
                'profession_id'    => $profession->id,
                'chart_id'         => $code,
                'date'             => $date,
                'tran_id'          => $tran_id,
                'source'           => 'budget',
                'narration'        => 'Budget Entry for ' . $client->fullname . ' for ' . $profession->name . ' at ' . $date,
                'last_year_amount' => $entries['last_year_amount'][$i],
                'old_percent'      => $entries['old_percent'][$i],
                'percent'      => $entries['percent'][$i],
                'amount'           => $entries['budget_amount'][$i],
                'updated_at'       => now(),
                'created_at'       => now(),
            ];
        }
        DB::beginTransaction();
        try {
            BudgetEntry::whereClientId($client->id)->whereProfessionId($profession->id)->where('date', $date)->delete();
            BudgetEntry::insert($data);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw new Exception('Something went wrong');
            // throw $e->getMessage();
        }
    }
}

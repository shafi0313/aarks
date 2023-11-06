<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Gsttbl;
use App\Models\Period;
use Illuminate\Http\Request;
use App\Models\GeneralLedger;
use App\Models\Frontend\CashBook;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class CashbookDataMoveController extends Controller
{
    public function index()
    {
        // if ($error = $this->sendPermissionError('admin.comperative_financial_report.index')) {
        //     return $error;
        // }

        $clients = getClientsWithPayment();
        return view('admin.cashbook-data-move.index', compact('clients'));
    }
    public function cashbook(Client $client)
    {
        // if ($error = $this->sendPermissionError('admin.comperative_financial_report.index')) {
        //     return $error;
        // }

        $cashbooks = CashBook::select('id', 'client_id', 'profession_id', 'period_id', 'tran_id', 'tran_date')
            ->whereClientId($client->id)
            // ->take(10)
            ->orderBy('tran_date', 'DESC')
            ->whereIsPost(1)
            ->get()
            ->groupBy('tran_id');
        return view('admin.cashbook-data-move.cashbook', compact('cashbooks'));
    }

    public function show($clientId, $professionId, $trnId)
    {
        // if ($error = $this->sendPermissionError('admin.comperative_financial_report.index')) {
        //     return $error;
        // }

        $cashbooks = CashBook::select('id', 'client_id', 'profession_id', 'period_id', 'tran_id', 'tran_date')
            ->whereClientId($clientId)
            ->whereProfessionId($professionId)
            ->whereTranId($trnId)
            ->get();
        return view('admin.cashbook-data-move.show', compact('cashbooks'));
    }

    public function update(Request $request)
    {

        $date = sqlDate($request->date);
        $period = Period::whereClientId($request->client_id)
            ->whereProfessionId($request->profession_id)
            ->whereDate('start_date', '<=', $date)
            ->whereDate('end_date', '>=', $date)
            ->first();

        DB::beginTransaction();
        CashBook::whereClientId($request->client_id)
            ->whereProfessionId($request->profession_id)
            ->whereTranId($request->tran_id)->update([
                'tran_date' => $date,
            ]);


        Gsttbl::whereClientId($request->client_id)
            ->whereProfessionId($request->profession_id)
            ->whereTrnId($request->tran_id)
            ->whereSource('CBC')->update([
                'trn_date' => $date,
                'period_id' => $period->id,

            ]);

        GeneralLedger::whereClientId($request->client_id)
            ->whereProfessionId($request->profession_id)
            ->whereTransactionId($request->tran_id)
            ->whereSource('CBC')
            ->update([
                'date' => $date,
            ]);

        try {
            DB::commit();
            Alert::success('Success', 'Cashbook data updated successfully');
        } catch (\Throwable $th) {
            DB::rollback();
            Alert::error('Error', 'Cashbook data update failed');
        }
        return back();
    }
}

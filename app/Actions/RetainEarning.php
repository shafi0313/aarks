<?php
namespace App\Actions;

use App\Models\GeneralLedger;
use App\Models\ClientAccountCode;


class RetainEarning
{
    public static function retain($client_id, $profession_id, $tran_date, $ledger, $source)
    {
        if (in_array($tran_date->format('m'), range(1, 6))) {
            $start_year = $tran_date->format('Y') - 1 . '-07-01';
            $end_year   = $tran_date->format('Y') . '-06-30';
        } else {
            $start_year = $tran_date->format('Y') . '-07-01';
            $end_year   = $tran_date->format('Y')+1 . '-06-30';
        }

        $inRetain   = GeneralLedger::where('client_id', $client_id)
                    ->where('profession_id', $profession_id)
                    ->where('date', '>=', $start_year)
                    ->where('date', '<=', $end_year)
                    ->where('chart_id', 'LIKE', '1%')
                    ->where('source', $source[0])
                    ->get();
        // $inRetainData = $inRetain->where('balance_type', 2)->sum('balance') - $inRetain->where('balance_type', 1)->sum('balance');
        $inRetainData = 0;
        foreach ($inRetain as $intr) {
            if ($intr->balance_type == 2 && $intr->balance > 0) {
                $inRetainData += abs($intr->balance);
            } else {
                $inRetainData -= abs($intr->balance);
            }
        }

        $exRetain = GeneralLedger::where('client_id', $client_id)
                    ->where('profession_id', $profession_id)
                    ->where('date', '>=', $start_year)
                    ->where('date', '<=', $end_year)
                    ->where('chart_id', 'LIKE', '2%')
                    ->where('source', $source[0])
                    ->get();
        // $exRetainData = $exRetain->where('balance_type', 1)->sum('balance') - $exRetain->where('balance_type', 2)->sum('balance');
        $exRetainData = 0;
        foreach ($exRetain as $intr) {
            if ($intr->balance_type == 1 && $intr->balance > 0) {
                $exRetainData += abs($intr->balance);
            } else {
                $exRetainData -= abs($intr->balance);
            }
        }

        $retainData               = $inRetainData - $exRetainData;


        $ledger['source']                 = $source[1];
        $ledger['narration']              = $source[0].' Retain Earning';
        $ledger['chart_id']               = 999999;
        $ledger['gst']                    = 0;
        $ledger['balance']                = $retainData;
        $ledger['client_account_code_id'] = ClientAccountCode::where('client_id', $client_id)
                                    ->where('profession_id', $profession_id)
                                    ->where('code', 999999)
                                    ->first()->id;

        if ($inRetainData > $exRetainData) {
            $ledger['credit'] = abs($retainData);
            $ledger['debit']  = 0;
        } else {
            $ledger['debit']  = abs($retainData);
            $ledger['credit'] = 0;
        }

        $isRetain = GeneralLedger::where('client_id', $client_id)
                    ->where('profession_id', $profession_id)
                    ->where('date', '>=', $start_year)
                    ->where('date', '<=', $end_year)
                    ->where('chart_id', 999999)
                    ->where('source', $source[1])
                    ->first();
        if ($isRetain) {
            $isRetain->update($ledger);
        } else {
            GeneralLedger::create($ledger);
        }
    }
    public static function tranRetain($client_id, $profession_id, $tran_id, $ledger, $source)
    {
        $inTranRetain   = GeneralLedger::where('client_id', $client_id)
                ->where('profession_id', $profession_id)
                ->where('transaction_id', $tran_id)
                ->where('chart_id', 'LIKE', '1%')
                ->where('source', $source[0])
                ->get(['balance_type','balance']);

        // $inTranRetainData = $inTranRetain->where('balance_type', 2)->sum('balance') - $inTranRetain->where('balance_type', 1)->sum('balance');
        $inTranRetainData = 0;
        foreach ($inTranRetain as $intr) {
            if ($intr->balance_type == 2 && $intr->balance > 0) {
                $inTranRetainData += abs($intr->balance);
            } else {
                $inTranRetainData -= abs($intr->balance);
            }
        }


        $exTranRetain = GeneralLedger::where('client_id', $client_id)
                ->where('profession_id', $profession_id)
                ->where('transaction_id', $tran_id)
                ->where('chart_id', 'LIKE', '2%')
                ->where('source', $source[0])
                ->get();

        // $exTranRetainData = $exTranRetain->where('balance_type', 1)->sum('balance') - $exTranRetain->where('balance_type', 2)->sum('balance');
        $exTranRetainData = 0;
        foreach ($exTranRetain as $intr) {
            if ($intr->balance_type == 1 && $intr->balance > 0) {
                $exTranRetainData += abs($intr->balance);
            } else {
                $exTranRetainData -= abs($intr->balance);
            }
        }


        $tranRetainData   = $inTranRetainData - $exTranRetainData;

        $ledger['source']                 = $source[1];
        $ledger['narration']              = $source[0].' P/L Appropriation Account';
        $ledger['gst']                    = 0;
        $ledger['balance']                = $tranRetainData;
        $ledger['client_account_code_id'] = ClientAccountCode::where('client_id', $client_id)
                                    ->where('profession_id', $profession_id)
                                    ->where('code', 999998)
                                    ->first()->id;
        if ($inTranRetainData > $exTranRetainData) {
            $ledger['credit'] = abs($tranRetainData);
            $ledger['debit']  = 0;
        } else {
            $ledger['debit']  = abs($tranRetainData);
            $ledger['credit'] = 0;
        }
        $isTranRetainEar = GeneralLedger::where('transaction_id', $tran_id)
                ->where('client_id', $client_id)
                ->where('profession_id', $profession_id)
                ->where('chart_id', 999998)
                ->where('source', $source[1])
                ->first();
        $ledger['chart_id']     = 999998;

        if ($isTranRetainEar) {
            $isTranRetainEar->update($ledger);
        } else {
            GeneralLedger::create($ledger);
        }
    }
}

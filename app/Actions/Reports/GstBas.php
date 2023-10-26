<?php

namespace App\Actions\Reports;

use App\Models\Payg;
use App\Models\Client;
use App\Models\Gsttbl;
use App\Models\Period;
use App\Models\FuelTaxLtr;
use App\Models\Profession;
use Illuminate\Http\Request;
use App\Models\GeneralLedger;
use App\ProfessionAccountCode;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;

class GstBas extends Controller
{
    public function gstAbs($item)
    {
        $code     = $item->accountCodes;
        if ($code) {
            $chart_id = $code->code;
            $type     = $code->type;          // 1=Debit , 2=Credit
            if ($type == 1 && in_array(substr($chart_id, -6, 1), [1,2])) {
                if ($item->gross_amount != 0) {
                    $item->gross_amount    = - abs($item->gross_amount);
                }
                if ($item->gross_cash_amount != 0) {
                    $item->gross_cash_amount    = - abs($item->gross_cash_amount);
                }
                if ($item->gst_accrued_amount != 0) {
                    $item->gst_accrued_amount    = - abs($item->gst_accrued_amount);
                }
                if ($item->gst_cash_amount != 0) {
                    $item->gst_cash_amount    = - abs($item->gst_cash_amount);
                }
                if ($item->net_amount != 0) {
                    $item->net_amount    = - abs($item->net_amount);
                }
            }
        }
    }
    public function cash(Request $request, $path = null)
    {
        $client_id         = $request->client_id;
        $dateFrom          = makeBackendCompatibleDate($request->from_date)->format('Y-m-d');
        $dateTo            = makeBackendCompatibleDate($request->to_date)->format('Y-m-d');
        $expense_code_from = '245000';
        $expense_code_to   = '245999';
        $w1_from           = '245100';
        $w1_to             = '245199';

        $client     = Client::findOrFail($client_id);
        $profession = Profession::findOrFail($request->profession_id);
        $period     = Period::where('client_id', $client_id)
            // ->where('start_date', '>=', $dateFrom)
            ->where('end_date', '<=', $dateTo)->first();
        if (!$period || is_null($period)) {
            Alert::error('Period Data Not Found!', 'You must create a period before make report!');
            return redirect()->back();
        }
        $payg = Payg::where('client_id', $client_id)
            ->where('period_id', $period->id)
            ->first();

        $fuel_tax_ltr = FuelTaxLtr::where('client_id', $client_id)
            ->whereBetween('date', [$dateFrom, $dateTo])
            ->sum('amount');

        $data = Gsttbl::with(['accountCodes' => fn ($q) => $q->where('client_id', $client_id)
            ->whereProfessionId($profession->id)])
            ->whereBetween('trn_date', [$dateFrom, $dateTo])
            ->where('client_id', $client_id)
            ->where('source', '!=', 'INV')
            ->orderBy('chart_code')
            ->get()->groupBy('chart_code');

        $income = Gsttbl::where('client_id', $client_id)
            ->whereProfessionId($profession->id)
            ->whereBetween('trn_date', [$dateFrom, $dateTo])
            ->where('chart_code', 'like', '1%')
            ->where('source', '!=', 'INV')
            ->select('id', 'chart_code', 'gst_cash_amount', 'gross_amount')
            ->with(['accountCodes'=> fn ($q) => $q->whereClientId($client_id)->select('id', 'code', 'type', 'gst_code')])->get()->each(function ($item) {
                $this->gstAbs($item);
            }); // _________G1_________

        $incomeNonGst = Gsttbl::where('client_id', $client_id)
            ->whereProfessionId($profession->id)
            ->where('chart_code', 'like', '1%')
            ->whereIn('source', ['PIN','BST','INP'])
            ->whereBetween('trn_date', [$dateFrom, $dateTo])
            ->where('gst_accrued_amount', '<=', 0)
            ->where('gst_cash_amount', '<=', 0)
            // ->select('id', 'chart_code', 'gst_cash_amount', 'gross_amount', 'net_amount')
            ->with(['accountCodes'=> fn ($q) => $q->whereClientId($client_id)->select('id', 'code', 'type', 'gst_code')])->get()->each(function ($item) {
                $this->gstAbs($item);
            }); // _________G3_________
        $asset = Gsttbl::where('client_id', $client_id)
            ->whereProfessionId($profession->id)
            ->whereBetween('trn_date', [$dateFrom, $dateTo])
            ->whereIn('source', ['PIN','BST','INP'])
            ->where('chart_code', 'like', '56%')
            ->sum('gross_amount'); // _________G10_________

        $expense_code = Gsttbl::where('client_id', $client_id)
            ->whereProfessionId($profession->id)
            ->whereBetween('trn_date', [$dateFrom, $dateTo])
            ->whereNotBetween('chart_code', [$expense_code_from, $expense_code_to])
            ->where('chart_code', 'like', '2%')
            ->with(['accountCodes'=> fn ($q) => $q->whereClientId($client_id)->select('id', 'code', 'type', 'gst_code')])->get()->each(function ($item) {
                $this->gstAbs($item);
            });

        $sum95 = Gsttbl::where('client_id', $client_id)
            ->whereProfessionId($profession->id)
            ->whereBetween('trn_date', [$dateFrom, $dateTo])
            ->where('source', '!=', 'INV')
            ->where('chart_code', 'like', '95%')
            ->sum('gst_cash_amount');

        $expenses = Gsttbl::where('client_id', $client_id)
            ->whereProfessionId($profession->id)
            ->whereBetween('trn_date', [$dateFrom, $dateTo])
            ->where('source', '!=', 'INV')
            ->where(function ($q) {
                $q->where('chart_code', 'like', '2%')
                    ->orWhere('chart_code', 'like', '56%');
            })
            ->with(['accountCodes'=> fn ($q) => $q->whereClientId($client_id)->select('id', 'code', 'type', 'gst_code')])->get()->each(function ($item) {
                $this->gstAbs($item);
            });// _________1B_________
        $expense = $sum95 > 0 ? $expenses->sum('gst_cash_amount') - abs($sum95) : $expenses->sum('gst_cash_amount') + abs($sum95); // _________1B_________

        $w1 = Gsttbl::where('client_id', $client_id)
            ->whereProfessionId($profession->id)
            ->whereIn('source', ['PIN','BST','INP'])
            ->whereBetween('trn_date', [$dateFrom, $dateTo])
            ->whereBetween('chart_code', [$w1_from, $w1_to])
            ->with(['accountCodes'=> fn ($q) => $q->whereClientId($client_id)->select('id', 'code', 'type', 'gst_code')])->get()->each(function ($item) {
                $this->gstAbs($item);
            });

        $w2 = Gsttbl::where('client_id', $client_id)
            ->whereProfessionId($profession->id)
            ->whereBetween('trn_date', [$dateFrom, $dateTo])
            ->where('chart_code', '245103')
            ->with(['accountCodes'=> fn ($q) => $q->whereClientId($client_id)->select('id', 'code', 'type', 'gst_code')])->get()->each(function ($item) {
                $this->gstAbs($item);
            });

        activity()
            ->performedOn(new GeneralLedger())
            ->withProperties(['client' => $client->fullname, 'report' => 'Cash Basis Balance Report'])
            ->log('Report > Cash Basis Balance Report > ' . $client->fullname .' > Profession :'. $profession->name);
        return compact(['data', 'client', 'dateFrom', 'dateTo', 'income', 'asset', 'expense_code', 'expense', 'w1', 'w2', 'incomeNonGst', 'fuel_tax_ltr', 'payg']);
    }
    public function consoleCash(Request $request, $path = null)
    {
        $client_id         = $request->client_id;
        $dateFrom          = makeBackendCompatibleDate($request->from_date)->format('Y-m-d');
        $dateTo            = makeBackendCompatibleDate($request->to_date)->format('Y-m-d');
        $expense_code_from = '245000';
        $expense_code_to   = '245999';
        $w1_from           = '245100';
        $w1_to             = '245199';

        $client = Client::findOrFail($client_id);
        $professions = $client->professions->pluck('id')->toArray();
        $period = Period::where('client_id', $client_id)
            // ->where('start_date', '>=', $dateFrom)
            ->where('end_date', '<=', $dateTo)->first();

        if (!$period || is_null($period)) {
            Alert::error('Period Data Not Found!', 'You must create a period before make report!');
            return redirect()->back();
        }

        $payg = Payg::where('client_id', $client_id)
            ->where('period_id', $period->id)
            ->first();

        $payg = Payg::select('paygs.percent', 'paygs.amount', 'periods.start_date', 'periods.end_date')
            ->join('periods', 'periods.id', '=', 'paygs.period_id')
            ->where('periods.client_id', $client_id)
            ->where('periods.start_date', '>=', $dateFrom)
            ->where('periods.end_date', '<=', $dateTo)
            ->first();
        $fuel_tax_ltr = FuelTaxLtr::where('client_id', $client_id)->whereBetween('date', [$dateFrom, $dateTo])->sum('amount');

        $data = Gsttbl::with(['accountCodes' => fn ($q) => $q->where('client_id', $client_id)])
            ->whereBetween('trn_date', [$dateFrom, $dateTo])
            ->where('client_id', $client_id)
            ->whereIn('profession_id', $professions)
            ->whereNotIn('source', ['INV','RIV'])
            ->orderBy('chart_code')
            ->get()
            ->groupBy('chart_code');

        $income = Gsttbl::where('client_id', $client_id)
            ->whereBetween('trn_date', [$dateFrom, $dateTo])
            ->whereIn('profession_id', $professions)
            ->where('chart_code', 'like', '1%')
            ->whereNotIn('source', ['INV','RIV'])
            // ->whereIn('source', ['PIN','BST','INP'])
            // ->select('chart_code', 'gst_cash_amount')
            ->with(['accountCodes'=> fn ($q) => $q->whereClientId($client_id)->select('id', 'code', 'type', 'gst_code')])->get()->each(function ($item) {
                $this->gstAbs($item);
            }); // _________G1_________

        $incomeNonGst = Gsttbl::where('client_id', $client_id)
            ->whereIn('profession_id', $professions)
            ->where('chart_code', 'like', '1%')
            ->whereBetween('trn_date', [$dateFrom, $dateTo])
            ->where('gst_accrued_amount', '<=', 0)
            ->where('gst_cash_amount', '<=', 0)
            // ->select('gst_cash_amount', 'gross_amount', 'net_amount')
            ->with(['accountCodes'=> fn ($q) => $q->whereClientId($client_id)->select('id', 'code', 'type', 'gst_code')])->get()->each(function ($item) {
                $this->gstAbs($item);
            }); // _________G3_________

        $asset = Gsttbl::where('client_id', $client_id)
            ->whereIn('profession_id', $professions)
            ->whereIn('source', ['PIN','BST','INP'])
            ->whereBetween('trn_date', [$dateFrom, $dateTo])
            ->where('chart_code', 'like', '56%')
            ->sum('gross_amount'); // _________G10_________

        $expense_code = Gsttbl::where('client_id', $client_id)
            ->whereIn('profession_id', $professions)
            ->whereBetween('trn_date', [$dateFrom, $dateTo])
            ->whereNotBetween('chart_code', [$expense_code_from, $expense_code_to])
            ->where('chart_code', 'like', '2%')
            ->with(['accountCodes'=> fn ($q) => $q->whereClientId($client_id)->select('id', 'code', 'type', 'gst_code')])
            ->get()->each(function ($item) {
                $this->gstAbs($item);
            });

        $sum95 = Gsttbl::where('client_id', $client_id)
            ->whereIn('profession_id', $professions)
            ->whereBetween('trn_date', [$dateFrom, $dateTo])
            ->where('source', '!=', 'INV')
            ->where('chart_code', 'like', '95%')
            ->sum('gst_cash_amount');

        $expenses = Gsttbl::with(['accountCodes'=> fn ($q) => $q->whereClientId($client_id)->select('id', 'code', 'type', 'gst_code')])
            ->where('client_id', $client_id)
            ->whereIn('profession_id', $professions)
            ->where('source', '!=', 'INV')
            ->whereBetween('trn_date', [$dateFrom, $dateTo])
            ->where(function ($q) {
                $q->where('chart_code', 'like', '2%')
                    ->orWhere('chart_code', 'like', '56%');
            })
            ->selectRaw('sum(gst_cash_amount) as gst_cash_amount')
            ->get()->each(function ($item) {
                $this->gstAbs($item);
            }); // _________1B_________
            $expense = $sum95 > 0 ? $expenses->sum('gst_cash_amount') - abs($sum95) : $expenses->sum('gst_cash_amount') + abs($sum95); // _________1B_________

        $w1 = Gsttbl::where('client_id', $client_id)
            ->whereIn('profession_id', $professions)
            ->whereBetween('trn_date', [$dateFrom, $dateTo])
            ->whereBetween('chart_code', [$w1_from, $w1_to])
            ->with(['accountCodes'=> fn ($q) => $q->whereClientId($client_id)->select('id', 'code', 'type', 'gst_code')])
            ->get()->each(function ($item) {
                $this->gstAbs($item);
            });

        $w2 = Gsttbl::where('client_id', $client_id)
            ->whereIn('profession_id', $professions)
            ->whereBetween('trn_date', [$dateFrom, $dateTo])
            ->where('chart_code', '245103')
            ->with(['accountCodes'=> fn ($q) => $q->whereClientId($client_id)->select('id', 'code', 'type', 'gst_code')])
            ->get()->each(function ($item) {
                $this->gstAbs($item);
            });

        activity()
            ->performedOn(new GeneralLedger())
            ->withProperties(['client' => $client->fullname, 'report' => 'GST/BAS(Consol.Cash) Report'])
            ->log('Report > GST/BAS(Consol.Cash) Report > ' . $client->fullname);

        return view($path,compact(['data', 'client', 'dateFrom', 'dateTo', 'income', 'asset', 'expense_code', 'expense', 'w1', 'w2', 'incomeNonGst', 'fuel_tax_ltr', 'payg']));
    }

    public function acured(Request $request, $path = null)
    {
        $nonGst            = ['GST', 'CAP', 'INP'];
        $clientId          = $request->get('client_id');
        $dateFrom          = makeBackendCompatibleDate($request->get('from_date'))->format('Y-m-d');
        $dateTo            = makeBackendCompatibleDate($request->get('to_date'))->format('Y-m-d');
        $expense_code_from = '245000';
        $expense_code_to   = '245999';
        $w1_from           = '245100';
        $w1_to             = '245199';
        $client            = Client::findOrFail($clientId);
        $profession        = Profession::findOrFail($request->profession_id);

        $period = Period::where('client_id', $client->id)
            // ->where('start_date', '>=', $dateFrom)
            ->where('end_date', '<=', $dateTo)->first();

        if (!$period || is_null($period)) {
            Alert::error('Period Data Not Found!', 'You must create a period before make report!');
            return redirect()->back();
        }

        $payg = Payg::where('client_id', $client->id)
            ->where('period_id', $period->id)
            ->first();
        $fuel_tax_ltr = FuelTaxLtr::where('client_id', $client->id)
            ->whereBetween('date', [$dateFrom, $dateTo])
            ->sum('amount');

        $data = Gsttbl::with(['accountCodes' => fn ($q) => $q->where('client_id', $client->id)])
            ->where('client_id', $clientId)
            ->whereProfessionId($profession->id)
            ->whereBetween('trn_date', [$dateFrom, $dateTo])
            ->where('chart_code', 'not like', '551%')
            ->where('source', '!=', 'PIN')
            ->orderBy('chart_code')
            ->get()->groupBy('chart_code');
        $income = Gsttbl::where('client_id', $clientId)
            ->whereProfessionId($profession->id)
            ->whereBetween('trn_date', [$dateFrom, $dateTo])
            ->where('chart_code', 'like', '1%')
            ->where('source', '!=', 'PIN')
            ->select('gst_cash_amount', 'gross_amount', 'chart_code')
            ->with(['accountCodes'=> fn ($q) => $q->whereClientId($clientId)->select('id', 'code', 'type', 'gst_code')])->get()->each(function ($item) {
                $this->gstAbs($item);
            });
        $incomeNonGst = Gsttbl::where('client_id', $clientId)
            ->whereProfessionId($profession->id)
            ->whereBetween('trn_date', [$dateFrom, $dateTo])
            ->where('chart_code', 'like', '1%')
            ->where('gst_accrued_amount', '<=', 0)
            ->where('gst_cash_amount', '<=', 0)
            ->select('gst_cash_amount', 'gross_amount', 'net_amount', 'chart_code')
            ->with(['accountCodes'=> fn ($q) => $q->whereClientId($clientId)->select('id', 'code', 'type', 'gst_code')])->get()->each(function ($item) {
                $this->gstAbs($item);
            }); // _________G3_________
        $asset = Gsttbl::where('client_id', $clientId)
            ->whereProfessionId($profession->id)
            ->whereBetween('trn_date', [$dateFrom, $dateTo])
            ->where('chart_code', 'like', '56%')
            ->sum('gross_amount'); // _________G10_________
        $expense_code = Gsttbl::where('client_id', $clientId)
            ->whereProfessionId($profession->id)
            ->whereBetween('trn_date', [$dateFrom, $dateTo])
            ->whereNotBetween('chart_code', [$expense_code_from, $expense_code_to])
            ->where('chart_code', 'like', '2%')
            ->with(['accountCodes'=> fn ($q) => $q->whereClientId($clientId)->select('id', 'code', 'type', 'gst_code')])->get()->each(function ($item) {
                $this->gstAbs($item);
            });

        $sum95 = Gsttbl::where('client_id', $clientId)
            ->whereProfessionId($profession->id)
            ->whereBetween('trn_date', [$dateFrom, $dateTo])
            ->where('source', '!=', 'INV')
            ->where('chart_code', 'like', '95%')
            ->sum('gst_cash_amount');

        $expenses = Gsttbl::where('client_id', $clientId)
            ->whereProfessionId($profession->id)
            ->whereBetween('trn_date', [$dateFrom, $dateTo])
            ->where('source', '!=', 'INV')
            ->where(function ($q) {
                $q->where('chart_code', 'like', '2%')
                    ->orWhere('chart_code', 'like', '56%');
            })
            ->with(['accountCodes'=> fn ($q) => $q->whereClientId($clientId)->select('id', 'code', 'type', 'gst_code')])->get()->each(function ($item) {
                $this->gstAbs($item);
            });// _________1B_________
        $expense = $sum95 > 0 ? $expenses->sum('gst_cash_amount') - abs($sum95) : $expenses->sum('gst_cash_amount') + abs($sum95); // _________1B_________
        $w1 = Gsttbl::where('client_id', $clientId)
            ->whereProfessionId($profession->id)
            ->whereBetween('trn_date', [$dateFrom, $dateTo])
            ->whereBetween('chart_code', [$w1_from, $w1_to])
            ->with(['accountCodes'=> fn ($q) => $q->whereClientId($clientId)->select('id', 'code', 'type', 'gst_code')])->get()->each(function ($item) {
                $this->gstAbs($item);
            });
        $w2 = Gsttbl::where('client_id', $clientId)
            ->whereProfessionId($profession->id)
            ->whereBetween('trn_date', [$dateFrom, $dateTo])
            ->where('chart_code', '245103')
            ->with(['accountCodes'=> fn ($q) => $q->whereClientId($clientId)->select('id', 'code', 'type', 'gst_code')])->get()->each(function ($item) {
                $this->gstAbs($item);
            });
        activity()
            ->performedOn(new GeneralLedger())
            ->withProperties(['client' => $client->fullname, 'report' => 'Accrued Basis Balance Report'])
            ->log('Report > Accrued Basis Balance Report > '.$client->fullname);
        return compact(['data', 'client', 'dateFrom', 'dateTo', 'income', 'asset', 'expense_code', 'expense', 'w1', 'w2', 'incomeNonGst', 'fuel_tax_ltr', 'payg']);
    }

    public function consoleAcured(Request $request, $path = null)
    {
        $nonGst = ['GST', 'CAP', 'INP'];
        $clientId          = $request->get('client_id');
        $dateFrom          = makeBackendCompatibleDate($request->get('from_date'))->format('Y-m-d');
        $dateTo            = makeBackendCompatibleDate($request->get('to_date'))->format('Y-m-d');
        $expense_code_from = '245000';
        $expense_code_to   = '245999';
        $w1_from           = '245100';
        $w1_to             = '245199';
        $client            = Client::findOrFail($clientId);
        $professions = $client->professions->pluck('id')->toArray();

        $period = Period::where('client_id', $client->id)
            // ->where('start_date', '>=', $dateFrom)
            ->where('end_date', '<=', $dateTo)->first();

        if (!$period || is_null($period)) {
            Alert::error('Period Data Not Found!', 'You must create a period before make report!');
            return redirect()->back();
        }

        $payg = Payg::where('client_id', $client->id)
            ->where('period_id', $period->id)
            ->first();
        $fuel_tax_ltr = FuelTaxLtr::where('client_id', $client->id)
            ->whereBetween('date', [$dateFrom, $dateTo])
            ->sum('amount');

        $data = Gsttbl::with(['accountCodes' => fn ($q) => $q->where('client_id', $client->id)])
            ->where('client_id', $clientId)
            ->whereIn('profession_id', $professions)
            ->whereBetween('trn_date', [$dateFrom, $dateTo])
            ->where('chart_code', 'not like', '551%')
            ->where('source', '!=', 'PIN')
            ->orderBy('chart_code')
            ->get()->groupBy('chart_code');
        $income = Gsttbl::where('client_id', $clientId)
            ->whereIn('profession_id', $professions)
            ->whereBetween('trn_date', [$dateFrom, $dateTo])
            ->where('chart_code', 'like', '1%')
            ->where('source', '!=', 'PIN')
            ->with(['accountCodes'=> fn ($q) => $q->whereClientId($clientId)->select('id', 'code', 'type', 'gst_code')])->get()->each(function ($item) {
                $this->gstAbs($item);
            });
        $incomeNonGst = Gsttbl::where('client_id', $clientId)
            ->whereIn('profession_id', $professions)
            ->whereBetween('trn_date', [$dateFrom, $dateTo])
            ->where('chart_code', 'like', '1%')
            ->where('gst_accrued_amount', '<', 0)
            ->where('gst_cash_amount', '<', 0)
            ->with(['accountCodes'=> fn ($q) => $q->whereClientId($clientId)->select('id', 'code', 'type', 'gst_code')])->get()->each(function ($item) {
                $this->gstAbs($item);
            }); // _________G3_________
        $asset = Gsttbl::where('client_id', $clientId)
            ->whereIn('profession_id', $professions)
            ->whereBetween('trn_date', [$dateFrom, $dateTo])
            ->where('chart_code', 'like', '56%')
            ->sum('gross_amount'); // _________G10_________
        $expense_code = Gsttbl::where('client_id', $clientId)
            ->whereIn('profession_id', $professions)
            ->whereBetween('trn_date', [$dateFrom, $dateTo])
            ->whereNotBetween('chart_code', [$expense_code_from, $expense_code_to])
            ->where('chart_code', 'like', '2%')
            ->with(['accountCodes'=> fn ($q) => $q->whereClientId($clientId)->select('id', 'code', 'type', 'gst_code')])->get()->each(function ($item) {
                $this->gstAbs($item);
            });

        $sum95 = Gsttbl::where('client_id', $clientId)
        ->whereIn('profession_id', $professions)
        ->whereBetween('trn_date', [$dateFrom, $dateTo])
        ->where('source', '!=', 'INV')
        ->where('chart_code', 'like', '95%')
        ->sum('gst_cash_amount');

        $expense = Gsttbl::where('client_id', $clientId)
            ->whereIn('profession_id', $professions)
            ->whereBetween('trn_date', [$dateFrom, $dateTo])
            ->where('source', '!=', 'INV')
            ->where(function ($q) {
                $q->where('chart_code', 'like', '2%')
                    ->orWhere('chart_code', 'like', '56%');
            })
            ->with(['accountCodes'=> fn ($q) => $q->whereClientId($clientId)->select('id', 'code', 'type', 'gst_code')])->get()->each(function ($item) {
                $this->gstAbs($item);
            });// _________1B_________
        $expense = $sum95 > 0 ? $expense->sum('gst_cash_amount') - abs($sum95) : $expense->sum('gst_cash_amount') + abs($sum95); // _________1B_________
        $w1 = Gsttbl::where('client_id', $clientId)
            ->whereIn('profession_id', $professions)
            ->whereBetween('trn_date', [$dateFrom, $dateTo])
            ->whereBetween('chart_code', [$w1_from, $w1_to])
            ->with(['accountCodes'=> fn ($q) => $q->whereClientId($clientId)->select('id', 'code', 'type', 'gst_code')])->get()->each(function ($item) {
                $this->gstAbs($item);
            });
        $w2 = Gsttbl::where('client_id', $clientId)
            ->whereIn('profession_id', $professions)
            ->whereBetween('trn_date', [$dateFrom, $dateTo])
            ->where('chart_code', '245103')
            ->with(['accountCodes'=> fn ($q) => $q->whereClientId($clientId)->select('id', 'code', 'type', 'gst_code')])->get()->each(function ($item) {
                $this->gstAbs($item);
            });
        activity()
            ->performedOn(new GeneralLedger())
            ->withProperties(['client' => $client->fullname, 'report' => 'Accrued Basis Balance Report'])
            ->log('Report > Accrued Basis Balance Report > '.$client->fullname);
        return compact(['data', 'client', 'dateFrom', 'dateTo', 'income', 'asset', 'expense_code', 'expense', 'w1', 'w2', 'incomeNonGst', 'fuel_tax_ltr', 'payg']);
    }
}

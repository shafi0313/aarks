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
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;

class GstPeriodic extends Controller
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
        $profession_id     = $request->profession_id;
        $dateFrom          = $request->form_date;
        $dateTo            = $request->to_date;
        $expense_code_from = '245000';
        $expense_code_to   = '245999';
        $w1_from           = '245100';
        $w1_to             = '245199';

        $client       = Client::find($client_id);
        $profession   = Profession::find($profession_id);
        $fuel_tax_ltr = FuelTaxLtr::whereIn('period_id', $request->peroid_id)->get();


        // if ($type == 1) {
        //     $debit  = $debit == 0 ? -$credit : $debit;
        //     if (substr($chart_id, -6, 1) == 1) {
        //         $debit = - $debit;
        //     }
        // } else {
        //     $credit = $credit == 0 ? -$debit : $credit;
        //     if (substr($chart_id, -6, 1) == 2) {
        //         $credit = - $credit;
        //     }
        // }

        $income = Gsttbl::with(['accountCodes'=> fn ($q) => $q->whereClientId($client_id)->select('id', 'code', 'type', 'gst_code')])->where('client_id', $client_id)
            ->where('profession_id', $profession_id)
            ->whereIn('period_id', $request->peroid_id)
            ->where('chart_code', 'like', '1%')
            ->where('source', '!=', 'INV')
            ->orderBy('chart_code')
            ->get()->each(function ($item) {
                $this->gstAbs($item);
            });

        $sum95 = Gsttbl::with(['accountCodes'=> fn ($q) => $q->whereClientId($client_id)->select('id', 'code', 'type', 'gst_code')])->where('client_id', $client_id)
            ->whereIn('period_id', $request->peroid_id)
            ->where('source', '!=', 'INV')
            ->where('chart_code', 'like', '95%')
            ->groupBy('period_id')
            ->selectRaw('period_id, sum(gst_cash_amount) as gst_cash_amount')
            ->get()->each(function ($item) {
                $this->gstAbs($item);
            });

        $expense = Gsttbl::with(['accountCodes'=> fn ($q) => $q->whereClientId($client_id)->select('id', 'code', 'type', 'gst_code')])->where('client_id', $client_id)
            ->where('profession_id', $profession_id)
            ->whereIn('period_id', $request->peroid_id)
            ->where('source', '!=', 'INV')
            ->where(function ($q) {
                $q->where('chart_code', 'like', '2%')
                    ->orWhere('chart_code', 'like', '56%');
            })
            ->groupBy('period_id')
            ->selectRaw('period_id, sum(gst_cash_amount) as gst_cash_amount')
            ->get()->each(function ($item) {
                $this->gstAbs($item);
            }); // _________1B_________

        $incomeNonGst = Gsttbl::with(['accountCodes'=> fn ($q) => $q->whereClientId($client_id)->select('id', 'code', 'type', 'gst_code')])->where('client_id', $client_id)
            ->where('profession_id', $profession_id)
            ->whereIn('period_id', $request->peroid_id)
            ->where('source', '!=', 'INV')
            ->where('chart_code', 'like', '1%')
            ->where('gst_accrued_amount', '<', 0)
            ->where('gst_cash_amount', '<', 0)
            ->orderBy('chart_code')
            ->get()->each(function ($item) {
                $this->gstAbs($item);
            }); // _________G3_________

        $asset = Gsttbl::with(['accountCodes'=> fn ($q) => $q->whereClientId($client_id)->select('id', 'code', 'type', 'gst_code')])->where('client_id', $client_id)
            ->where('profession_id', $profession_id)
            ->whereIn('period_id', $request->peroid_id)
            ->where('source', '!=', 'INV')
            ->where('chart_code', 'like', '56%')
            ->orderBy('chart_code')
            ->get()->each(function ($item) {
                $this->gstAbs($item);
            }); // _________G10_________

        $expense_code = Gsttbl::with(['accountCodes'=> fn ($q) => $q->whereClientId($client_id)->select('id', 'code', 'type', 'gst_code')])->where('client_id', $client_id)
            ->where('profession_id', $profession_id)
            ->whereIn('period_id', $request->peroid_id)
            ->where('source', '!=', 'INV')
            ->whereNotBetween('chart_code', [$expense_code_from, $expense_code_to])
            ->where('chart_code', 'like', '2%')
            ->orderBy('chart_code')
            ->get()->each(function ($item) {
                $this->gstAbs($item);
            });

        $w1 = Gsttbl::with(['accountCodes'=> fn ($q) => $q->whereClientId($client_id)->select('id', 'code', 'type', 'gst_code')])->where('client_id', $client_id)
            ->where('profession_id', $profession_id)
            ->whereBetween('chart_code', [$w1_from, $w1_to])
            ->whereIn('period_id', $request->peroid_id)
            ->orderBy('chart_code')
            ->get()->each(function ($item) {
                $this->gstAbs($item);
            });

        $w2 = Gsttbl::with(['accountCodes'=> fn ($q) => $q->whereClientId($client_id)->select('id', 'code', 'type', 'gst_code')])->where('client_id', $client_id)
            ->where('profession_id', $profession_id)
            ->whereIn('period_id', $request->peroid_id)
            ->where('chart_code', '245103')
            ->orderBy('chart_code')
            ->get()->each(function ($item) {
                $this->gstAbs($item);
            });

        $payg = Payg::where('client_id', $client_id)
            ->whereIn('period_id', $request->peroid_id)
            ->get();

        $periods = Period::whereIn('id', $request->peroid_id)->get();

        $accrueds =  Gsttbl::with(['accountCodes'=> function ($q) use ($request) {
            $q->where('client_id', $request->client_id);
        }])
            ->where('client_id', $request->client_id)
            ->where('profession_id', $profession_id)
            ->whereIn('period_id', $periods->pluck('id')->toArray())
            ->where('source', '!=', 'INV')
            ->orderBy('chart_code')->get()->groupBy('period_id');
            
        activity()
            ->performedOn(new GeneralLedger())
            ->withProperties(['client' => $client->fullname, 'profession' => $profession->name, 'report' => 'Cash Periodic Report'])
            ->log('Report > Cash Periodic Report > '.$client->fullname .' > '. $profession->name);
        return compact(['periods', 'client', 'profession', 'accrueds', 'income', 'expense', 'incomeNonGst', 'asset', 'expense_code', 'w1', 'w2', 'payg', 'fuel_tax_ltr','sum95']);
    }
    public function consoleCash(Request $request, $path = null)
    {
        $client_id         = $request->client_id;
        $dateFrom          = $request->form_date;
        $dateTo            = $request->to_date;
        $expense_code_from = '245000';
        $expense_code_to   = '245999';
        $w1_from           = '245100';
        $w1_to             = '245199';
        $client            = Client::find($client_id);
        $fuel_tax_ltr      = FuelTaxLtr::whereIn('period_id', $request->peroid_id)->get();
        $professions = $client->professions->pluck('id')->toArray();

        $income = Gsttbl::with(['accountCodes'=> fn ($q) => $q->whereClientId($client_id)->select('id', 'code', 'type', 'gst_code')])->where('client_id', $client_id)
            ->whereIn('profession_id', $professions)
            ->whereIn('period_id', $request->peroid_id)
            ->where('chart_code', 'like', '1%')
            ->where('source', '!=', 'INV')
            ->orderBy('chart_code')
            ->get()->each(function ($item) {
                $this->gstAbs($item);
            });

        $sum95 = Gsttbl::with(['accountCodes'=> fn ($q) => $q->whereClientId($client_id)->select('id', 'code', 'type', 'gst_code')])->where('client_id', $client_id)
            ->whereIn('profession_id', $professions)
            ->whereBetween('trn_date', [$dateFrom, $dateTo])
            ->where('source', '!=', 'INV')
            ->where('chart_code', 'like', '95%')
            ->groupBy('period_id')
            ->selectRaw('period_id, sum(gst_cash_amount) as gst_cash_amount')
            ->get()->each(function ($item) {
                $this->gstAbs($item);
            });
        $expense = Gsttbl::with(['accountCodes'=> fn ($q) => $q->whereClientId($client_id)->select('id', 'code', 'type', 'gst_code')])->where('client_id', $client_id)
            ->whereIn('profession_id', $professions)
            ->whereIn('period_id', $request->peroid_id)
            ->where('source', '!=', 'INV')
            ->where(function ($q) {
                $q->where('chart_code', 'like', '2%')
                    ->orWhere('chart_code', 'like', '56%');
            })
            ->groupBy('period_id')
            ->selectRaw('period_id, sum(gst_cash_amount) as gst_cash_amount')
            ->get()->each(function ($item) {
                $this->gstAbs($item);
            }); // _________1B_________
        // return $expense->where('period_id', 314)->first();
        // $expense = $sum95 > 0 ? $expense - abs($sum95) : $expense + abs($sum95); // _________1B_________

        $incomeNonGst = Gsttbl::with(['accountCodes'=> fn ($q) => $q->whereClientId($client_id)->select('id', 'code', 'type', 'gst_code')])->where('client_id', $client_id)
            ->whereIn('profession_id', $professions)
            ->whereIn('period_id', $request->peroid_id)
            ->where('source', '!=', 'INV')
            ->where('chart_code', 'like', '1%')
            ->where('gst_accrued_amount', '<', 0)
            ->where('gst_cash_amount', '<', 0)
            ->orderBy('chart_code')
            ->get()->each(function ($item) {
                $this->gstAbs($item);
            }); // _________G3_________
        $asset = Gsttbl::with(['accountCodes'=> fn ($q) => $q->whereClientId($client_id)->select('id', 'code', 'type', 'gst_code')])->where('client_id', $client_id)
            ->whereIn('profession_id', $professions)
            ->whereIn('period_id', $request->peroid_id)
            ->where('source', '!=', 'INV')
            ->where('chart_code', 'like', '56%')
            ->orderBy('chart_code')
            ->get()->each(function ($item) {
                $this->gstAbs($item);
            }); // _________G10_________
        $expense_code = Gsttbl::with(['accountCodes'=> fn ($q) => $q->whereClientId($client_id)->select('id', 'code', 'type', 'gst_code')])->where('client_id', $client_id)
            ->whereIn('profession_id', $professions)
            ->whereIn('period_id', $request->peroid_id)
            ->where('source', '!=', 'INV')
            ->whereNotBetween('chart_code', [$expense_code_from, $expense_code_to])
            ->where('chart_code', 'like', '2%')
            ->orderBy('chart_code')
            ->get()->each(function ($item) {
                $this->gstAbs($item);
            });
        $w1 = Gsttbl::with(['accountCodes'=> fn ($q) => $q->whereClientId($client_id)->select('id', 'code', 'type', 'gst_code')])->where('client_id', $client_id)
            ->whereIn('profession_id', $professions)
            ->whereBetween('chart_code', [$w1_from, $w1_to])
            ->whereIn('period_id', $request->peroid_id)
            ->orderBy('chart_code')
            ->get()->each(function ($item) {
                $this->gstAbs($item);
            });
        $w2 = Gsttbl::with(['accountCodes'=> fn ($q) => $q->whereClientId($client_id)->select('id', 'code', 'type', 'gst_code')])->where('client_id', $client_id)
            ->whereIn('profession_id', $professions)
            ->whereIn('period_id', $request->peroid_id)
            ->where('chart_code', '245103')
            ->orderBy('chart_code')
            ->get()->each(function ($item) {
                $this->gstAbs($item);
            });
        $payg = Payg::where('client_id', $client_id)
            ->whereIn('period_id', $request->peroid_id)
            ->get();
        $periods = Period::whereIn('id', $request->peroid_id)->get();
        $accrueds =  Gsttbl::with(['accountCodes'=> function ($q) use ($request) {
            $q->where('client_id', $request->client_id);
        }])
            ->where('client_id', $request->client_id)
                ->whereIn('profession_id', $professions)
            ->whereIn('period_id', $periods->pluck('id')->toArray())
            ->where('source', '!=', 'INV')
            ->orderBy('chart_code')->get()->groupBy('period_id');
        // return $accrueds[253];
        activity()
            ->performedOn(new GeneralLedger())
            ->withProperties(['client' => $client->fullname, 'profession' => 'Console Report', 'report' => 'Cash Periodic Report'])
            ->log('Report >Console Cash Periodic Report > '.$client->fullname);
        return compact(['periods', 'client', 'accrueds', 'income', 'expense', 'incomeNonGst', 'asset', 'expense_code', 'w1', 'w2', 'payg', 'fuel_tax_ltr','sum95']);
    }

    
    public function acrued(Request $request, $path = null)
    {
        $client_id         = $request->client_id;
        $profession_id     = $request->profession_id;
        $dateFrom          = $request->form_date;
        $dateTo            = $request->to_date;
        $expense_code_from = '245000';
        $expense_code_to   = '245999';
        $w1_from           = '245100';
        $w1_to             = '245199';

        $client     = Client::find($client_id);
        $profession = Profession::find($profession_id);

        $fuel_tax_ltr = FuelTaxLtr::whereIn('period_id', $request->peroid_id)->get();

        $income = Gsttbl::with(['accountCodes'=> fn ($q) => $q->whereClientId($client_id)->select('id', 'code', 'type', 'gst_code')])->where('client_id', $client_id)
            ->where('profession_id', $profession_id)
            ->whereIn('period_id', $request->peroid_id)
            ->where('chart_code', 'like', '1%')
            ->orderBy('chart_code')
            ->get()->each(function ($item) {
                $this->gstAbs($item);
            });

        $sum95 = Gsttbl::with(['accountCodes'=> fn ($q) => $q->whereClientId($client_id)->select('id', 'code', 'type', 'gst_code')])->where('client_id', $client_id)
            ->whereBetween('trn_date', [$dateFrom, $dateTo])
            ->where('source', '!=', 'INV')
            ->where('chart_code', 'like', '95%')
            ->groupBy('period_id')
            ->selectRaw('period_id, sum(gst_cash_amount) as gst_cash_amount')
            ->get()->each(function ($item) {
                $this->gstAbs($item);
            });
        // return
        $expense = Gsttbl::with(['accountCodes'=> fn ($q) => $q->whereClientId($client_id)->select('id', 'code', 'type', 'gst_code')])->where('client_id', $client_id)
            ->where('profession_id', $profession_id)
            ->whereIn('period_id', $request->peroid_id)
            ->where('source', '!=', 'INV')
            ->where(function ($q) {
                $q->where('chart_code', 'like', '2%')
                    ->orWhere('chart_code', 'like', '56%');
            })
            ->groupBy('period_id')
            ->selectRaw('period_id, sum(gst_cash_amount) as gst_cash_amount')
            ->get()->each(function ($item) {
                $this->gstAbs($item);
            }); // _________1B_________
        // return $expense->where('period_id', 314)->first();
        // $expense = $sum95 > 0 ? $expense - abs($sum95) : $expense + abs($sum95); // _________1B_________

        $incomeNonGst = Gsttbl::with(['accountCodes'=> fn ($q) => $q->whereClientId($client_id)->select('id', 'code', 'type', 'gst_code')])->where('client_id', $client_id)
            ->where('profession_id', $profession_id)
            ->whereIn('period_id', $request->peroid_id)
            ->where('chart_code', 'like', '1%')
            ->where('gst_accrued_amount', '<', 0)
            ->where('gst_cash_amount', '<', 0)
            ->orderBy('chart_code')
            ->get()->each(function ($item) {
                $this->gstAbs($item);
            }); // _________G3_________
        $asset = Gsttbl::with(['accountCodes'=> fn ($q) => $q->whereClientId($client_id)->select('id', 'code', 'type', 'gst_code')])->where('client_id', $client_id)
            ->where('profession_id', $profession_id)
            ->whereIn('period_id', $request->peroid_id)
            ->where('chart_code', 'like', '56%')
            ->orderBy('chart_code')
            ->get()->each(function ($item) {
                $this->gstAbs($item);
            }); // _________G10_________
        $expense_code = Gsttbl::with(['accountCodes'=> fn ($q) => $q->whereClientId($client_id)->select('id', 'code', 'type', 'gst_code')])->where('client_id', $client_id)
            ->where('profession_id', $profession_id)
            ->whereIn('period_id', $request->peroid_id)
            ->whereNotBetween('chart_code', [$expense_code_from, $expense_code_to])
            ->where('chart_code', 'like', '2%')
            ->orderBy('chart_code')
            ->get()->each(function ($item) {
                $this->gstAbs($item);
            });
        $w1 = Gsttbl::with(['accountCodes'=> fn ($q) => $q->whereClientId($client_id)->select('id', 'code', 'type', 'gst_code')])->where('client_id', $client_id)
            ->where('profession_id', $profession_id)
            ->whereBetween('chart_code', [$w1_from, $w1_to])
            ->whereIn('period_id', $request->peroid_id)
            ->orderBy('chart_code')
            ->get()->each(function ($item) {
                $this->gstAbs($item);
            });
        $w2 = Gsttbl::with(['accountCodes'=> fn ($q) => $q->whereClientId($client_id)->select('id', 'code', 'type', 'gst_code')])->where('client_id', $client_id)
            ->where('profession_id', $profession_id)
            ->whereIn('period_id', $request->peroid_id)
            ->where('chart_code', '245103')
            ->orderBy('chart_code')
            ->get()->each(function ($item) {
                $this->gstAbs($item);
            });
        $payg = Payg::where('client_id', $client_id)
            ->whereIn('period_id', $request->peroid_id)
            ->get();
        $periods = Period::whereIn('id', $request->peroid_id)->get();
        $accrueds =  Gsttbl::with(['accountCodes'=> fn ($q) => $q->where('client_id', $request->client_id)])
            ->where('client_id', $request->client_id)
            ->where('profession_id', $profession_id)
            ->whereIn('period_id', $periods->pluck('id')->toArray())
            ->where('source', '!=', 'INV')
            ->orderBy('chart_code')->get()->groupBy('period_id');
        // return $accrueds[253];
        activity()
            ->performedOn(new GeneralLedger())
            ->withProperties(['client' => $client->fullname, 'profession' => $profession->name, 'report' => 'Cash Periodic Report'])
            ->log('Report > Cash Periodic Report > '.$client->fullname .' > '. $profession->name);
        return compact(['periods', 'client', 'profession', 'accrueds', 'income', 'expense', 'incomeNonGst', 'asset', 'expense_code', 'w1', 'w2', 'payg', 'fuel_tax_ltr','sum95']);
    }
    public function consoleAcrued(Request $request, $path = null)
    {
        $client_id         = $request->client_id;
        $dateFrom          = $request->form_date;
        $dateTo            = $request->to_date;
        $expense_code_from = '245000';
        $expense_code_to   = '245999';
        $w1_from           = '245100';
        $w1_to             = '245199';

        $client       = Client::find($client_id);
        $fuel_tax_ltr = FuelTaxLtr::whereIn('period_id', $request->peroid_id)->get();
        $professions = $client->professions->pluck('id')->toArray();

        $income = Gsttbl::with(['accountCodes'=> fn ($q) => $q->whereClientId($client_id)->select('id', 'code', 'type', 'gst_code')])->where('client_id', $client_id)
            ->whereIn('profession_id', $professions)
            ->whereIn('period_id', $request->peroid_id)
            ->where('chart_code', 'like', '1%')
            ->orderBy('chart_code')
            ->get()->each(function ($item) {
                $this->gstAbs($item);
            });

        $sum95 = Gsttbl::with(['accountCodes'=> fn ($q) => $q->whereClientId($client_id)->select('id', 'code', 'type', 'gst_code')])->where('client_id', $client_id)
            ->whereIn('profession_id', $professions)
            ->whereBetween('trn_date', [$dateFrom, $dateTo])
            ->where('source', '!=', 'INV')
            ->where('chart_code', 'like', '95%')
            ->groupBy('period_id')
            ->selectRaw('period_id, sum(gst_cash_amount) as gst_cash_amount')
            ->get()->each(function ($item) {
                $this->gstAbs($item);
            });
        $expense = Gsttbl::with(['accountCodes'=> fn ($q) => $q->whereClientId($client_id)->select('id', 'code', 'type', 'gst_code')])->where('client_id', $client_id)
            ->whereIn('profession_id', $professions)
            ->whereIn('period_id', $request->peroid_id)
            ->where('source', '!=', 'INV')
            ->where(function ($q) {
                $q->where('chart_code', 'like', '2%')
                    ->orWhere('chart_code', 'like', '56%');
            })
            ->groupBy('period_id')
            ->selectRaw('period_id, sum(gst_cash_amount) as gst_cash_amount')
            ->get()->each(function ($item) {
                $this->gstAbs($item);
            }); // _________1B_________
        $incomeNonGst = Gsttbl::with(['accountCodes'=> fn ($q) => $q->whereClientId($client_id)->select('id', 'code', 'type', 'gst_code')])->where('client_id', $client_id)
            ->whereIn('profession_id', $professions)
            ->whereIn('period_id', $request->peroid_id)
            ->where('chart_code', 'like', '1%')
            ->where('gst_accrued_amount', '<', 0)
            ->where('gst_cash_amount', '<', 0)
            ->orderBy('chart_code')
            ->get()->each(function ($item) {
                $this->gstAbs($item);
            }); // _________G3_________
        $asset = Gsttbl::with(['accountCodes'=> fn ($q) => $q->whereClientId($client_id)->select('id', 'code', 'type', 'gst_code')])->where('client_id', $client_id)
            ->whereIn('profession_id', $professions)
            ->whereIn('period_id', $request->peroid_id)
            ->where('chart_code', 'like', '56%')
            ->orderBy('chart_code')
            ->get()->each(function ($item) {
                $this->gstAbs($item);
            }); // _________G10_________
        $expense_code = Gsttbl::with(['accountCodes'=> fn ($q) => $q->whereClientId($client_id)->select('id', 'code', 'type', 'gst_code')])->where('client_id', $client_id)
            ->whereIn('profession_id', $professions)
            ->whereIn('period_id', $request->peroid_id)
            ->whereNotBetween('chart_code', [$expense_code_from, $expense_code_to])
            ->where('chart_code', 'like', '2%')
            ->orderBy('chart_code')
            ->get()->each(function ($item) {
                $this->gstAbs($item);
            });
        $w1 = Gsttbl::with(['accountCodes'=> fn ($q) => $q->whereClientId($client_id)->select('id', 'code', 'type', 'gst_code')])->where('client_id', $client_id)
            ->whereIn('profession_id', $professions)
            ->whereBetween('chart_code', [$w1_from, $w1_to])
            ->whereIn('period_id', $request->peroid_id)
            ->orderBy('chart_code')
            ->get()->each(function ($item) {
                $this->gstAbs($item);
            });
        $w2 = Gsttbl::with(['accountCodes'=> fn ($q) => $q->whereClientId($client_id)->select('id', 'code', 'type', 'gst_code')])->where('client_id', $client_id)
            ->whereIn('profession_id', $professions)
            ->whereIn('period_id', $request->peroid_id)
            ->where('chart_code', '245103')
            ->orderBy('chart_code')
            ->get()->each(function ($item) {
                $this->gstAbs($item);
            });
        $payg = Payg::where('client_id', $client_id)
            ->whereIn('period_id', $request->peroid_id)
            ->get();
        $periods = Period::whereIn('id', $request->peroid_id)->get();
        $accrueds =  Gsttbl::with(['accountCodes'=> function ($q) use ($request) {
            $q->where('client_id', $request->client_id);
        }])
            ->where('client_id', $request->client_id)
            ->whereIn('profession_id', $professions)
            ->whereIn('period_id', $periods->pluck('id')->toArray())
            ->where('source', '!=', 'INV')
            ->orderBy('chart_code')->get()->groupBy('period_id');
        // return $accrueds[253];
        activity()
            ->performedOn(new GeneralLedger())
            ->withProperties(['client' => $client->fullname, 'report' => 'Cash Periodic Report'])
            ->log('Report > Cash Periodic Report > '.$client->fullname);
        return compact(['periods', 'client', 'profession', 'accrueds', 'income', 'expense', 'incomeNonGst', 'asset', 'expense_code', 'w1', 'w2', 'payg', 'fuel_tax_ltr','sum95']);
    }
}

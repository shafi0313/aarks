<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Client;
use App\Models\Profession;
use Illuminate\Http\Request;
use App\Models\StandardLeave;
use App\Models\StandardWages;
use App\Models\Frontend\Dedotr;
use App\Models\ClientAccountCode;
use App\Models\ClientPaymentList;
use App\Models\Frontend\Creditor;
use App\Models\StandardDeducation;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Frontend\ClientLeave;
use App\Models\Frontend\ClientWages;
use App\Models\Frontend\PayAccumAmt;
use App\Models\ProfessionAccountCode;
use App\Models\Frontend\InvoiceLayout;
use App\Models\StandardSuperannuation;
use App\Models\Frontend\ClientDeduction;
use App\Models\Frontend\EClassification;
use Illuminate\Support\Facades\Response;
use App\Models\Frontend\DedotrQuoteOrder;
use App\Models\Frontend\InventoryRegister;
use App\Models\Frontend\ClientSuperannuation;
use App\Models\Frontend\CreditorServiceOrder;
use App\Actions\AccountCodeActions\AddClientAccountCode;

class AllPageController extends Controller
{
    // item_list
    public function item_list_index()
    {
        return view('frontend.item_list.index');
    }


    // inventory_register
    public function invRegister()
    {
        $client = Client::with('professions')->find(client()->id);
        return view('frontend.inventory.register.index', compact('client'));
    }
    public function invReport(Request $request)
    {
        $from      = makeBackendCompatibleDate($request->from);
        $to        = makeBackendCompatibleDate($request->to);
        $start     = $request->from;
        $end       = $request->to;
        $registers = InventoryRegister::with('client', 'profession', 'item')
        ->where('client_id', $request->client_id)
        ->where('profession_id', $request->profession_id)
        ->where('date', '>=', $from)
        ->where('date', '<=', $to)
        ->get();
        if (!$registers->count()) {
            toast('There is no date matching with date & profession', 'error');
            return redirect()->back();
        }
        return view('frontend.inventory.register.report', compact('registers', 'start', 'end'));
    }


    // period
    public function period_index()
    {
        return view('frontend.accounts.period.index');
    }
    public function period_create()
    {
        return view('frontend.accounts.period.create');
    }

    // invoice_layout
    public function invoiceIndex()
    {
        $client = Client::with(['invoiceLayout'])->findOrFail(client()->id);

        return view('frontend.invoice_layout.index', compact('client'));
    }
    public function invoiceStore(Request $request)
    {
        $data = $request->validate([
            'layout' => 'required',
        ]);
        $data['client_id'] = client()->id;
        try {
            InvoiceLayout::where('client_id', $data['client_id'])->delete();
            InvoiceLayout::create($data);
            toast("Invoice layout saved!", 'success');
        } catch (\Exception $e) {
            toast("Invoice layout Not saved!", 'error');
            #$e->getMessage();
        }
        return redirect()->route('index');
    }

    // period_lock
    public function period_lock_index()
    {
        return view('frontend.period_lock.index');
    }


    // add_card
    public function add_card_select_activity()
    {
        $client = Client::with('professions')->where('id', client()->id)->first();
        return view('frontend.add_card.select_activity', compact('client'));
    }
    public function add_card_select_type(Profession $profession)
    {
        return view('frontend.add_card.select_type', compact('profession'));
    }
    // public function add_card_create()
    // {
    // 	return view('frontend.add_card.create');
    // }
    public function add_card_employee()
    {
        $client = client()->id;
        $ECS = EClassification::where('client_id', $client)->get();
        $wages = ClientWages::where('client_id', $client)->get();
        $leaves = ClientLeave::where('client_id', $client)->get();
        $sups = ClientSuperannuation::where('client_id', $client)->get();
        $deducs = ClientDeduction::where('client_id', $client)->get();
        $standWages = StandardWages::all();
        $standLeaves = StandardLeave::all();
        $standDeducs = StandardDeducation::all();
        $standSups = StandardSuperannuation::all();
        return view('frontend.add_card.employee', compact('client', 'ECS', 'wages', 'leaves', 'sups', 'standWages', 'standLeaves', 'standDeducs', 'standSups', 'deducs'));
    }



    // add_list
    public function card_list_select_activity()
    {
        $client = Client::with('professions')->where('id', client()->id)->first();

        return view('frontend.card_list.select_activity', compact('client'));
    }
    public function card_list_select_type($profession)
    {
        return view('frontend.card_list.select_type', compact('profession'));
    }
    public function card_list_show()
    {
        return view('frontend.card_list.show');
    }
    public function card_list_employee_show()
    {
        return view('frontend.card_list.employee_show');
    }
    public function card_list_employee()
    {
        return view('frontend.card_list.employee');
    }

    // quote
    public function editPrintFilter(Request $request)
    {
        $start_date = makeBackendCompatibleDate($request->start_date);
        $end_date = makeBackendCompatibleDate($request->end_date);
        $client   = client();
        switch ($request->src) {
            case 'quote':
                $quotes = DedotrQuoteOrder::where('client_id', $client->id)
                        ->where('start_date', '>=', $start_date->format('Y-m-d'))
                        ->where('end_date', '<=', $end_date->format('Y-m-d'))
                        ->where('chart_id', 'not like', '551%')
                        ->whereNotNull('job_title')
                        ->get();
                return view('frontend.sales.quote.filter', compact('client', 'quotes'));
                break;
            case 'quote_item':
                $quotes = DedotrQuoteOrder::where('client_id', $client->id)
                        ->where('start_date', '>=', $start_date->format('Y-m-d'))
                        ->where('end_date', '<=', $end_date->format('Y-m-d'))
                        ->whereNotNull('item_name')
                        ->where('chart_id', 'not like', '551%')
                        ->get();
                return view('frontend.sales.quote_item.filter', compact('client', 'quotes'));
                break;
            case 'inv':
                $invoices = Dedotr::where('client_id', $client->id)
                        ->where('tran_date', '>=', $start_date->format('Y-m-d'))
                        ->where('tran_date', '<=', $end_date->format('Y-m-d'))
                        ->where('chart_id', 'not like', '551%')
                        ->get();
                return view('frontend.sales.invoice.filter', compact('client', 'invoices'));
                break;
            case 'order':
                $orders = CreditorServiceOrder::where('client_id', $client->id)
                    ->where('start_date', '>=', $start_date->format('Y-m-d'))
                    ->where('end_date', '<=', $end_date->format('Y-m-d'))
                    ->where('chart_id', 'not like', '551%')
                    ->get();
                return view('frontend.purchase.service_order.filter', compact('client', 'orders'));
                break;
            default:
                $bills = Creditor::where('client_id', $client->id)
                        ->where('tran_date', '>=', $start_date->format('Y-m-d'))
                        ->where('tran_date', '<=', $end_date->format('Y-m-d'))
                        ->where('chart_id', 'not like', '551%')
                        ->get();
                return view('frontend.purchase.enter_bill.filter', compact('client', 'bills'));
                break;
        }
    }
    public function itemQuoteCreate()
    {
        return view('frontend.sales.quote.item_quote');
    }


    // invoice
    public function invoice_select_activity()
    {
        return view('frontend.sales.invoice.select_activity');
    }
    public function invoice_create()
    {
        return view('frontend.sales.invoice.create');
    }
    public function invoice_manage()
    {
        return view('frontend.sales.invoice.manage');
    }

    // payment
    public function payslip_index()
    {
        return view('frontend.payroll.payslip.index');
    }
    public function payslip_report()
    {
        return view('frontend.payroll.payslip.report');
    }
    // transation_journal
    public function transation_journal_index()
    {
        return view('frontend.payroll.transation_journal.index');
    }

    // view_payroll_journal
    public function view_payroll_journal_index()
    {
        return view('frontend.payroll.view_payroll_journal.index');
    }
    public function view_payroll_journal_report()
    {
        return view('frontend.payroll.view_payroll_journal.report');
    }

    // account_chart
    public function account_chart_select_activity()
    {
        $client = Client::with('professions')->where('id', client()->id)->first();
        return view('frontend.accounts.account_chart.select_activity', compact('client'));
    }
    public function clientCodeProfession(Client $client, Profession $profession)
    {
        $profession->load('industryCategories');
        $client->load('professions');

        // $profession = Profession::with('industryCategories')->find(1);
        $this->updateMasterSyncAbility($profession);
        $industry_categories = $profession->industryCategories->pluck('id')->toArray();
        $profession->load([
            'accountCodeCategories' => function ($query) use ($industry_categories) {
                return $query->with([
                    'subCategoryWithoutAdditional' => function ($sub_category_query) use ($industry_categories) {
                        return $sub_category_query->whereHas('industryCategories', function ($industry_category_query) use ($industry_categories) {
                            return $industry_category_query->whereIn('industry_category_id', $industry_categories);
                        })->with([
                            'additionalCategory' => function ($additional_category_query) use ($industry_categories) {
                                return $additional_category_query
                                    ->whereHas('industryCategories', function ($industry_category_query) use ($industry_categories) {
                                        return $industry_category_query->whereIn('industry_category_id', $industry_categories);
                                    });
                            }
                        ])->orderBy('code', 'asc');
                    }
                ])->where('type', 1)->whereNull('parent_id')->orderBy('code', 'asc');
            }
        ]);
        // $accountCodes = ProfessionAccountCode::where('profession_id', $profession->id)
        $accountCodes = ClientAccountCode::where('profession_id', $profession->id)
            ->where('client_id', $client->id)
            // ->with('profession')
            ->orderBy('code', 'asc')->get();
        $industryCategories = $profession->industryCategories;
        $accountCodeCategories = $profession->accountCodeCategories;


        return view(
            'frontend.accounts.account_chart.show',
            compact(
                'client',
                'profession',
                'industryCategories',
                'accountCodeCategories',
                'accountCodes'
            )
        );
    }
    private function updateMasterSyncAbility(Profession $profession)
    {
        if ($profession->accountCodes->count() && $profession->can_perform_sync) {
            $profession->can_perform_sync = false;
            $profession->save();
        }
    }
    public function account_chart_store(Request $request, AddClientAccountCode $addAccountCode)
    {
        $request->validate([
            'profession_id'        => 'required',
            'industry_category_id' => 'required|array',
            'category'             => 'required',
            'sub_category'         => 'required',
            'additional_category'  => 'required',
            'account_code'         => 'required|unique:account_codes,code|string|min:3|max:3',
            'type'                 => 'required',
            'account_name'         => 'required',
            'gst_code'             => 'required'
        ]);
        $data = $this->prepareDataForAccountCode($request);
        DB::beginTransaction();
        try {
            $addAccountCode->setData($data)->execute();
            toast('Account Code Successfully Created', 'success');
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            return $exception->getMessage();
            toast($exception->getMessage(), 'error');
        }
        return back();
    }
    public function prepareDataForAccountCode($request)
    {
        return [
            'created_by_type'        => 2,
            'client_id'              => $request->client_id,
            'profession_id'          => $request->profession_id,
            'industry_category_id'   => $request->industry_category_id,
            'category_id'            => $request->category,
            'sub_category_id'        => $request->sub_category,
            'additional_category_id' => $request->additional_category,
            'code'                   => $request->account_code,
            'type'                   => $request->type,
            'name'                   => $request->account_name,
            'gst_code'               => $request->gst_code,
            'note'                   => $request->note,
        ];
    }
    public function account_chart_update(Request $request)
    {
        $account = ClientAccountCode::findOrFail($request->id);
        $data = [
            'name' => $request->name,
            'note' => $request->note,
        ];
        try {
            $account->update($data);
            toast('Account code updated', 'success');
        } catch (\Exception $e) {
            toast($e->getMessage(), 'error');
        }
        return back();
    }
    public function account_chart_delete(Request $request)
    {
        $account = ClientAccountCode::findOrFail($request->id);
        try {
            $account->delete();
            toast('Account code Deleted', 'success');
        } catch (\Exception $e) {
            toast($e->getMessage(), 'error');
        }
        return back();
    }

    // manage
    public function manage_manage_super()
    {
        return view('frontend.payroll.manage.manage_super');
    }
    public function manage_manage_deducation()
    {
        return view('frontend.payroll.manage.manage_deducation');
    }



    // payroll_peridoc_summery
    public function payroll_ps_select_activity()
    {
        $client = Client::with('professions')->find(client()->id);
        return view('frontend.payroll.peridoc_summery.select_activity', compact('client'));
    }
    public function payroll_ps_report(Request $r)
    {
        dd($r);
        $r->validate([
            'start_date'    => 'required',
            'end_date'      => 'required',
            'profession_id' => 'required',
            'client_id'     => 'required',
        ]);
        $client = Client::findOrFail($r->client_id);
        $start_date = makeBackendCompatibleDate($r->start_date);
        $end_date = makeBackendCompatibleDate($r->end_date);
        $payAccums = PayAccumAmt::select('*', DB::raw('sum(wages) as swages, sum(payg) as spayg, sum(deduc) as sdeduc,sum(super) as ssuper,sum(net_pay) as snet_pay'))
            ->where('client_id', $client->id)
            ->where('profession_id', $r->profession_id)
            ->where('payperiod_start', '>=', $start_date)
            ->where('payperiod_end', '<=', $end_date)
            ->groupBy('employee_card_id')
            ->get();
        $totalAccum = PayAccumAmt::where('profession_id', $r->profession_id)
            ->where('client_id', $client->id)
            ->where('payperiod_start', '>=', $start_date)
            ->where('payperiod_end', '<=', $end_date)
            ->get();

        return view('frontend.payroll.peridoc_summery.report', compact('totalAccum', 'client', 'payAccums', 'start_date', 'end_date'));
    }

    public function payment_post(Request $request)
    {
        $request->validate([
            'client_id' => 'required',
            'package'   => 'required',
            'duration'  => 'required',
            'amount'    => 'required',
            'note'      => 'required',
            'receipt'   => 'required',
        ]);
        if ($request->key != 'vuJBgi3rjad3xymO8eraAMcnzn6w2zVIaVupFYn7') {
            exit();
        }
        $package_details = DB::table('subscriptions')->where('name', $request->package)->first();

        $data = [
            'client_id'       => $request->client_id,
            'subscription_id' => @$package_details->id,
            'duration'        => $request->duration,
            'amount'          => $request->amount,
            'message'         => $request->note,
            'rcpt'            => $request->receipt,
            'started_at'      => now(),
            'expire_at'       => now()->addMonth($request->duration),
        ];
        ClientPaymentList::create($data);
        exit();
        // try {
        //     ClientPaymentList::create($data);
        // } catch (\Exception $e) {
        //     // return $e->getMessage();
        //     return response()->json(
        //         'errors' => 'Oops someting wrong.',
        //         'status' => Response::HTTP_BAD_REQUEST,
        //     ], Response::HTTP_BAD_REQUEST);
        // }
    }
}

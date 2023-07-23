<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Profession;
use Illuminate\Http\Request;
use App\Models\ClientAccountCode;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\ProfessionAccountCode;
use RealRashid\SweetAlert\Facades\Alert;

class ClientFixedAccountCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clients = Client::leftJoin('client_payment_lists', 'clients.id', '=', 'client_payment_lists.client_id')
            ->select('clients.id','clients.company', 'clients.first_name','clients.last_name','clients.email','clients.phone',
                    'client_payment_lists.status', 'client_payment_lists.is_expire', 'client_payment_lists.status')
            ->orderBy('client_payment_lists.status', 'desc')
            ->orderBy('client_payment_lists.is_expire', 'desc')
            ->get();
        return view('admin.client_fixed_code.index', compact('clients'));
    }
    public function selectProfession(Client $client)
    {
        return view('admin.client_fixed_code.select_profession', compact('client'));
    }
    public function show(Client $client, Profession $profession)
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
            'admin.client_fixed_code.codes',
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
    public function update(Request $request)
    {
        $account = ClientAccountCode::findOrFail($request->id);
        $data = [
            'name' => $request->name,
            'note' => $request->note,
        ];
        try {
            $account->update($data);
            Alert::success('Account Code Update', 'Client Account Code Successfully Updated');
        } catch (\Exception $exception) {
            Alert::error('Account Code Update', $exception->getMessage());
        }

        return redirect()->back();
    }
}

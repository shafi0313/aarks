<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Client;
use App\Models\Period;
use App\Models\FuelTaxLtr;
use App\Models\Profession;
use Illuminate\Http\Request;
use App\Models\Fuel_tax_credit;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;

class FuelTaxLtrController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $r)
    {
        $fuel = FuelTaxLtr::where('client_id', $r->client_id)->where('period_id', $r->period_id)->get();
        //   return  $fuel->sum('ltr');
        //    $fuel->count();
        $html = '';
        $html .= '<thead>';
        $html .= '    <tr>';
        $html .= '        <th>Date</th>';
        $html .= '        <th>LTR</th>';
        $html .= '        <th>Actions</th>';
        $html .= '    </tr>';
        $html .= '</thead>';

        if ($fuel->count() > 0) {
            foreach ($fuel as $row) {
                $html .= '<tr>';
                $html .= '<td>' . Carbon::parse($row->date)->format('d/m/Y')  . '</td>';
                $html .= '<td>' . $row->ltr . '</td>';
                $html .= '<td>';
                // $html .= '<a data-id="' . $row->id . '" class="btn btn-info" id="update" href="#" ><i class="fa fa-edit  "></i></a>';
                $html .= '<a data-id="' . $row->id . '" class="btn btn-danger" id="delete" href="' . route('fuel.delete', $row->id) . '"><i class="fa fa-trash  "></i></a>';
                $html .= '</td>';
                $html .= '</tr>';
            }
            $html .= '<tfooter>';
            $html .= '<tr>';
            $html .=    '<td><b>Total Amount: ' . $fuel->sum('amount') . '</b></td>';
            $html .=    '<td><b>Total LTR: ' . $fuel->sum('ltr') . '</b></td>';
            $html .=    '<td></td>';
            $html .= '</tr>';
            $html .= '</tfooter>';
            return json_encode(['status' => 'success', 'html' => $html]);
        } else {
            $html .= '<tr colspan="">';
            $html .= '<td>SORRY NO DATA FOUND!</td>';
            $html .= '<tr>';
            return json_encode(['status' => 'danger', 'html' => $html]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // $date = array_map('strrev', explode('/', strrev($request->date)));
        // return  $date = join('-', $date);
        $date      = makeBackendCompatibleDate($request->date);

        if (periodLock($request->clientId, $date)) {
            return response()->json('Your enter data period is locked, check administration', 403);
        }

        $date        = $date->format('Y-m-d');
        $ltr         = $request->ltr;
        $client      = Client::findOrFail($request->clientId);
        $profession  = Profession::findOrFail($request->profession_id);
        $period      = Period::findOrFail($request->periodId);
        $startDate   = Carbon::parse($period->start_date)->format('Y-m-d');
        $endDate     = Carbon::parse($period->end_date)->format('Y-m-d');
        $rate        = 0;
        $fuel        = Fuel_tax_credit::where('start_date', '<=', $date)->where('end_date', '>=', $date)->first();
        $checkPeriod = Period::where('client_id', $period->client_id)
                ->where('profession_id', $period->profession_id)
                // ->where('start_date', '<=', $date)
                ->where('end_date', '>=', $date)->first();

        if ($fuel) {
            $rate = $fuel->rate;
        }
        if ($checkPeriod) {
            if ($startDate <= $date && $date <= $endDate) {
                $data = [
                    'client_id'     => $client->id,
                    'profession_id' => $profession->id,
                    'period_id'     => $period->id,
                    'date'          => $date,
                    'ltr'           => $ltr,
                    'rate'          => $rate,
                    'amount'        => $ltr * $rate,
                ];
            } else {
                return 'Not Between';
            }
        }
        try {
            $ltr = FuelTaxLtr::create($data);
            return 1;
        } catch (\Exception $e) {
            return $e->getMessage();
            // return 0;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\FuelTaxLtr  $fuelTaxLtr
     * @return \Illuminate\Http\Response
     */
    public function show(FuelTaxLtr $fuelTaxLtr)
    {
    }

    public function getRecord(Request $request)
    {
        return $request;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\FuelTaxLtr  $fuelTaxLtr
     * @return \Illuminate\Http\Response
     */
    public function edit(FuelTaxLtr $fuelTaxLtr)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\FuelTaxLtr  $fuelTaxLtr
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FuelTaxLtr $fuelTaxLtr)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\FuelTaxLtr  $fuelTaxLtr
     * @return \Illuminate\Http\Response
     */
    public function destroy(FuelTaxLtr $fuelTaxLtr)
    {
        //
    }
    public function delete($r)
    {
        try {
            FuelTaxLtr::find($r)->delete();
            Alert::success('Deleted', 'LTR delete Successfully');
        } catch (\Exception $exception) {
            Alert::error('Client Period', $exception->getMessage());
        }
        return back();
    }
}

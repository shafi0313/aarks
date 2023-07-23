<?php
namespace App\Actions\Reports;

use App\Models\Client;
use App\Models\Profession;
use App\Models\BusinessPlan;
use Illuminate\Http\Request;

class BusinessPlanAction
{
    public static function report(Request $request)
    {
       $year       = $request->year;
       $start_date = $year - 1 . '-07-01';
       $end_date   = $year . '-06-30';
       $client     = Client::findOrFail($request->client_id);
       $profession = Profession::findOrFail($request->profession_id);
       $business   = BusinessPlan::where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->whereBetween('date', [$start_date, $end_date])
            ->count();
       $incomes    = BusinessPlan::with(['chart' => fn ($q) => $q->where('client_id', $client->id)])
            ->where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->whereBetween('date', [$start_date, $end_date])
            ->where('chart_id', 'like', '1%')
            ->get();
        $goods_solds = BusinessPlan::with(['chart' => fn ($q) => $q->where('client_id', $client->id)])
            ->where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->whereBetween('date', [$start_date, $end_date])
            ->where(fn ($q) => $q->where('chart_id', 'like', '21%')->orWhere('chart_id', 'like', '22%'))
            ->get();

        $administrative = BusinessPlan::with(['chart' => fn ($q) => $q->where('client_id', $client->id)])
            ->where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->whereBetween('date', [$start_date, $end_date])
            ->where('chart_id', 'like', '243%')
            ->get();
        $general = BusinessPlan::with(['chart' => fn ($q) => $q->where('client_id', $client->id)])
            ->where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->whereBetween('date', [$start_date, $end_date])
            ->where('chart_id', 'like', '245%')
            ->get();
        $selling = BusinessPlan::with(['chart' => fn ($q) => $q->where('client_id', $client->id)])
            ->where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->whereBetween('date', [$start_date, $end_date])
            ->where('chart_id', 'like', '246%')
            ->get();
        $financial = BusinessPlan::with(['chart' => fn ($q) => $q->where('client_id', $client->id)])
            ->where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->whereBetween('date', [$start_date, $end_date])
            ->where('chart_id', 'like', '249%')
            ->get();
        $current = BusinessPlan::with(['chart' => fn ($q) => $q->where('client_id', $client->id)])
            ->where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->whereBetween('date', [$start_date, $end_date])
            ->where('chart_id', 'like', '55%')
            ->get();
        $fixed = BusinessPlan::with(['chart' => fn ($q) => $q->where('client_id', $client->id)])
            ->where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->whereBetween('date', [$start_date, $end_date])
            ->where('chart_id', 'like', '56%')
            ->get();
        $tax = BusinessPlan::with(['chart' => fn ($q) => $q->where('client_id', $client->id)])
            ->where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->whereBetween('date', [$start_date, $end_date])
            ->where('chart_id', '999999')
            ->first();
        // $overheads = BusinessPlan::with(['chart' => fn ($q) => $q->where('client_id', $client->id)])
        //     ->where('client_id', $client->id)
        //     ->where('profession_id', $profession->id)
        //     ->whereBetween('date', [$start_date, $end_date])
        //     ->where(fn ($q) => $q->where('chart_id', 'like', '243%')->orWhere('chart_id', 'like', '245%')->orWhere('chart_id', 'like', '246%')->orWhere('chart_id', 'like', '249%'))
        //     ->get();
            // ->groupBy(fn ($item) => substr($item->chart_id, -6, 1));

        $lastYear = makeBackendCompatibleDate('01/06/'.$year);
        $months = [];
        foreach (range(1, 12) as $month) {
            $lastYear->addMonth();
            $months[] = $lastYear->format('M') .' '. $lastYear->format('y');
        }
        return compact(['incomes', 'goods_solds', 'months', 'client', 'profession', 'start_date', 'end_date', 'administrative', 'general', 'selling', 'financial', 'tax', 'current', 'fixed' ,'business']);
    }
}

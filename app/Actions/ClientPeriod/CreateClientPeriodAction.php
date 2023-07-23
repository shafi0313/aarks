<?php namespace App\Actions\ClientPeriod;

use App\Models\Period;
use App\Actions\Creatable;
use App\Actions\BaseAction;

class CreateClientPeriodAction extends BaseAction
{
    use Creatable;

    public function __construct(Period $period)
    {
        $this->setModel($period);
    }

    public function execute()
    {
        // dd(financialYearInArray($this->data['year']));
        $this->checkFinancialYear();
        $this->hasExistingPeriod();
        $this->checkContinuity();
        $this->create();
    }
    // 1029 958
    private function checkFinancialYear()
    {
        $financial_year = financialYearInArray($this->data['year']);

        $is_between_financial_year = (
            $this->data['start_date']->between($financial_year['first'], $financial_year['last']) &&
            $this->data['end_date']->between($financial_year['first'], $financial_year['last'])
        );

        if (!$is_between_financial_year) {
            throw new \Exception("Your selected date outside of the financial year");
        }
    }

    private function hasExistingPeriod()
    {
        $start_date = $this->data['start_date'];
        $end_date = $this->data['end_date'];
        $client_id = $this->data['client_id'];
        $profession_id = $this->data['profession_id'];
        $periods = Period::where('client_id', $client_id)
                    ->where('profession_id', $profession_id)
                    ->where(function ($query) use ($start_date, $end_date) {
                        return $query->where(function ($query2) use ($start_date, $end_date) {
                            return $query2->where('start_date', '>=', $start_date)
                                ->where('start_date', '<=', $end_date);
                        })->orWhere(function ($query2) use ($start_date, $end_date) {
                            return $query2->where('end_date', '>=', $start_date)
                                ->where('end_date', '<=', $end_date);
                        });
                    })
                    ->count();

        if ($periods) {
            throw new \Exception("Your selected date already added");
        }
    }

    private function checkContinuity()
    {
        $client_periods = Period::where('client_id', $this->data['client_id'])
                        ->where('profession_id', $this->data['profession_id'])->get();
        if (!$client_periods->count()) {
            return ;
        }
        $last_created_period_start_date = $client_periods->last()->start_date;
        $last_created_period_end_date = $client_periods->last()->end_date;
        // info($last_created_period_start_date .' === '. $this->data['start_date']);
        // info($last_created_period_end_date .' === '. $this->data['end_date']);
        // info($last_created_period_end_date->addDay(1)->isSameDay($this->data['start_date']));
        if (!$last_created_period_end_date->addDay(1)->isSameDay($this->data['start_date']) && !$last_created_period_start_date->subDay()->isSameDay($this->data['end_date'])) {
            throw new \Exception("Your selected date not been continuity........");
        }
    }
}

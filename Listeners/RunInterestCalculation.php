<?php


namespace Listeners;


use system\BaseListener;
use system\interfaces\Listener;

class RunInterestCalculation extends BaseListener implements Listener
{

    public $properties = [
        'date'
    ];

    public function __construct($event)
    {
        parent::__construct($event);
    }

    public function handle()
    {
        $executionDate = strtotime($this->date);
        $startDate = date('Y-m-01', strtotime('-1 MONTHS', $executionDate));
        $endDate = date('Y-m-t', strtotime('-1 MONTHS', $executionDate));

        $investors = (new \Models\Investor())->get();
        $service = (new \Services\InterestCalculationService());
        foreach ($investors as $investor) {
            $service->monthlyCalculation($investor->id, $startDate, $endDate);
        }

    }
}

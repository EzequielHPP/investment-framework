<?php


namespace Services;


use Models\Investment;
use Models\Investor;
use Models\Tranche;
use system\exceptions\InvalidTranche;

class InterestCalculationService
{

    /**
     * This runs the Monthly calculation for a certain Investor for a give time period
     *
     * @throws InvalidTranche
     */
    public function monthlyCalculation(int $investorId, string $startPeriod, string $endPeriod)
    {
        $rows = (new Investment())->getByInvestor($investorId, $startPeriod, $endPeriod);
        foreach ($rows as $row) {
            $dailyInterestRate = $this->calculateDailyInterestRate($row);
            $investedRate = $this->calculatePeriodInterestRate($row, $dailyInterestRate, $endPeriod);
            $totalEarnedInterest = $this->calculateEarnedInterest($row, $investedRate);

            $investor = (new Investor())->find($investorId);
            if (!empty($investor)) {
                $investor->wallet()->addFunds($totalEarnedInterest);
            }
        }
    }

    /**
     * Daily Interest Rate :  Interest rate / Days in a month
     *
     * @param $entry
     * @return float|int
     * @throws InvalidTranche
     */
    public function calculateDailyInterestRate($entry)
    {
        $tranche = (new Tranche())->find($entry->tranche_id);
        if (empty($tranche)) {
            throw new InvalidTranche('Could not find the requested Tranche');
        }

        $rate = $tranche->interest_month_pc;
        $daysInMont = date('t', strtotime($entry->transaction_date));

        return $rate / $daysInMont;
    }

    /**
     * Period interest rate: Daily interest rate * Days invested
     *
     * @param $entry
     * @param $dailyInterestRate
     * @param $endPeriod
     * @return float|int
     * @throws InvalidTranche
     */
    public function calculatePeriodInterestRate($entry, $dailyInterestRate, $endPeriod)
    {
        $tranche = (new Tranche())->find($entry->tranche_id);
        if (empty($tranche)) {
            throw new InvalidTranche('Could not find the requested Tranche');
        }

        $totalDaysInvested = $this->totalOfDays($entry->transaction_date, $endPeriod);

        return $dailyInterestRate * $totalDaysInvested;
    }

    /**
     * Return number of days between two dates
     *
     * @param $startDate
     * @param $endDate
     * @return float
     */
    private function totalOfDays($startDate, $endDate)
    {
        $start = strtotime($startDate . ' 00:00:00');
        $end = strtotime($endDate . ' 23:59:59');
        $datediff = $end - $start;

        return round($datediff / (60 * 60 * 24));
    }

    /**
     * Earned interest: Invested amount / 100 * Invested period interest rate
     * @param $entry
     * @param $investedRate
     * @return float
     */
    private function calculateEarnedInterest($entry, $investedRate)
    {
        return round(($entry->amount / 100) * $investedRate, 2);
    }
}

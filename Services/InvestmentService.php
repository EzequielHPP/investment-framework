<?php


namespace Services;


use Events\PostInvestmentCreation;
use Events\PreInvestmentCreation;
use Models\Investment;

class InvestmentService
{

    /**
     * Make an investment to a given Tranche
     * Runs events: PreInvestmentCreation & PostInvestmentCreation
     *
     * @param int $investorId
     * @param int $trancheId
     * @param float $amount
     * @param string $investmentDate
     * @return bool
     */
    public function invest(int $investorId, int $trancheId, float $amount, string $investmentDate): bool
    {
        // Run any checks and run any pre-creation actions
        event((new PreInvestmentCreation($investorId, $trancheId, $amount, $investmentDate)));

        $newInvestment = (new Investment());
        $newInvestment->investor_id = $investorId;
        $newInvestment->tranche_id = $trancheId;
        $newInvestment->amount = $amount;
        $newInvestment->transaction_date = $investmentDate;
        $newInvestment->save();

        // Run any post-creation actions
        event((new PostInvestmentCreation($newInvestment->id)));

        return true;
    }
}

<?php

namespace Services;

use Models\Tranche;
use system\exceptions\InvalidTranche;
use system\exceptions\TrancheInsufficientFundsAvailable;

class TranchesService
{
    /**
     * Update used funds for Tranche
     *
     * @param int $tranchId
     * @param float $amount
     * @throws InvalidTranche
     * @throws TrancheInsufficientFundsAvailable
     */
    public function updateFunds(int $tranchId, float $amount): void
    {
        $tranche = (new Tranche())->find($tranchId);
        if (empty($tranche)) {
            throw new InvalidTranche('Could not find Tranche matching ID ' . $tranchId);
        }

        $tranche->used += $amount;
        if ($tranche->used > $tranche->max_value) {
            throw new TrancheInsufficientFundsAvailable('We\'re sorry but this tranche doesn\'t have enough funds available.');
        }

        $tranche->save();
    }
}

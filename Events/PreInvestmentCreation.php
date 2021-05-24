<?php

namespace Events;

class PreInvestmentCreation
{

    public $investorId;
    public $trancheId;
    public $amount;
    public $transactionDate;

    public function __construct(int $investorId, int $tranchId, float $amount, string $transactionDate)
    {
        $this->investorId = $investorId;
        $this->trancheId = $tranchId;
        $this->amount = $amount;
        $this->transactionDate = $transactionDate;
    }
}

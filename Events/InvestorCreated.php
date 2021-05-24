<?php

namespace Events;

class InvestorCreated
{

    public $investorId;

    public function __construct(int $investorId)
    {
        $this->investorId = $investorId;
    }
}

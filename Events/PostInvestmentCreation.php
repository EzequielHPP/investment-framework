<?php

namespace Events;

class PostInvestmentCreation
{

    public $investmentId;

    public function __construct(int $investmentId)
    {
        $this->investmentId = $investmentId;
    }
}

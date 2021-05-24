<?php

namespace Listeners;

use system\BaseListener;
use system\exceptions\InvalidInvestor;
use system\interfaces\Listener;

class InvestorFundCheck extends BaseListener implements Listener
{
    public $properties = [
        'investorId',
        'trancheId',
        'amount',
        'transactionDate'
    ];
    public $investor;
    public $amount;

    /**
     * @throws InvalidInvestor
     */
    public function __construct($event)
    {
        parent::__construct($event);

        $this->investor = (new \Models\Investor())->find($event->investorId);
        if (empty($this->investor)) {
            throw new InvalidInvestor('Invalid Investor, Please try again');
        }
        $this->amount = $event->amount;
    }

    public function handle()
    {
        $this->investor->checkForFunds($this->amount);
    }
}

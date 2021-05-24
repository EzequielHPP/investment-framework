<?php


namespace Listeners;


use Models\Investor;
use Models\InvestorWallet;
use system\BaseListener;
use system\exceptions\InvalidTranche;
use system\interfaces\Listener;

class CreateInvestorWallet extends BaseListener implements Listener
{
    public $properties = [
        'investorId'
    ];
    public $investor;

    public function __construct($event)
    {
        parent::__construct($event);

        $this->investor = (new Investor())->find($event->investorId);
        if (empty($this->investor)) {
            throw new InvalidTranche('Invalid Investor. Please try again');
        }
    }

    public function handle()
    {
        $wallet = (new InvestorWallet());
        $wallet->investor_id = $this->investor->id;
        $wallet->funds = 0;
        $wallet->save();
    }
}

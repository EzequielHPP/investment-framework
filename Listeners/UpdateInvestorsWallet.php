<?php


namespace Listeners;


use Models\Investment;
use system\BaseListener;
use system\interfaces\Listener;

class UpdateInvestorsWallet extends BaseListener implements Listener
{
    public $properties = [
        'investmentId'
    ];
    public $investment;
    public $investor;

    public function __construct($event)
    {
        parent::__construct($event);

        $this->investment = (new Investment())->find($event->investmentId);
        if (empty($this->investment)) {
            throw new InvalidInvestor('Invalid Investor, Please try again');
        }

        $this->investor = (new \Models\Investor())->find($this->investment->investor_id);
        if (empty($this->investor)) {
            throw new InvalidInvestor('Invalid Investor, Please try again');
        }
    }

    public function handle()
    {
        $wallet = $this->investor->wallet();
        $wallet->funds -= $this->investment->amount;
        $wallet->save();
    }
}

<?php


namespace Listeners;


use Models\Tranche;
use system\BaseListener;
use system\exceptions\InvalidTranche;
use system\exceptions\TrancheInsufficientFundsAvailable;
use system\exceptions\TrancheUnavailable;
use system\interfaces\Listener;

class CheckTrancheAvailability extends BaseListener implements Listener
{
    public $properties = [
        'trancheId',
        'amount',
        'transactionDate',
    ];
    public $amount;
    public $tranche;
    public $transactionDate;

    public function __construct($event)
    {
        parent::__construct($event);

        $this->tranche = (new Tranche())->find($event->trancheId);
        if (empty($this->tranche)) {
            throw new InvalidTranche('Invalid Tranche Selected, Please try again');
        }
        $this->amount = $event->amount;
        $this->transactionDate = $event->transactionDate;
    }

    public function handle()
    {
        if (($this->tranche->used + $this->amount) > $this->tranche->max_value) {
            throw new TrancheInsufficientFundsAvailable('We\'re sorry but this tranche doesn\'t have enough funds available.');
        }
        $loan = $this->tranche->loan();
        if ($loan->start_date > $this->transactionDate || $loan->end_date < $this->transactionDate) {
            throw new TrancheUnavailable('This Tranche is unavailable at the moment. Please try again');
        }
    }
}

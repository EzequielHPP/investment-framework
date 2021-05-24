<?php


namespace Listeners;


use Models\Tranche;
use Services\TranchesService;
use system\BaseListener;
use system\exceptions\InvalidTranche;
use system\interfaces\Listener;

class UpdateTrancheFunds extends BaseListener implements Listener
{
    public $tranche;
    public $amount;
    public $properties = [
        'trancheId',
    ];

    /**
     * @throws InvalidTranche
     */
    public function __construct($event)
    {
        parent::__construct($event);

        $this->tranche = (new Tranche())->find($event->trancheId);
        if (empty($this->tranche)) {
            throw new InvalidTranche('Invalid Tranche Selected, Please try again');
        }
        $this->amount = $event->amount;
    }

    public function handle()
    {
        (new TranchesService())->updateFunds($this->tranche->id, $this->amount);
    }
}

<?php


namespace Models;


use system\BaseModel;

class InvestorWallet extends BaseModel
{
    public $table = 'investor_wallets';

    public $fields = [
        'id' => 'int',
        'investor_id' => 'int',
        'funds' => 'float'
    ];

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Add funds to the current wallet
     *
     * @param float $value
     */
    public function addFunds(float $value)
    {
        $this->funds += $value;
        $this->save();
    }
}

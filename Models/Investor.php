<?php


namespace Models;


use system\BaseModel;
use system\exceptions\InsufficientFunds;
use system\exceptions\NoWalletSetup;

class Investor extends BaseModel
{
    public $table = 'investors';

    public $fields = [
        'id' => 'int',
        'name' => 'string'
    ];

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Checks if Investor has available funds for the amount requested
     *
     * @param $amount
     * @throws InsufficientFunds
     * @throws NoWalletSetup
     */
    public function checkForFunds($amount): void
    {
        $wallet = $this->wallet();
        if (empty($wallet)) {
            throw new NoWalletSetup('You need to setup a Virtual wallet to be able to do investments');
        }
        if ($wallet->funds < $amount) {
            throw new InsufficientFunds('We\'re sorry but you don\'t have enough funds on your Virtual Wallet.');
        }
    }

    /**
     * Returns the InvestorWallet model if it finds the wallet, or null
     *
     * @return InvestorWallet|null
     */
    public function wallet()
    {
        return (new InvestorWallet())->where('investor_id', $this->id)->first();
    }
}

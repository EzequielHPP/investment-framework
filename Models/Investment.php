<?php


namespace Models;


use system\BaseModel;

class Investment extends BaseModel
{
    public $table = 'investments';

    public $fields = [
        'id' => 'int',
        'investor_id' => 'int',
        'tranche_id' => 'int',
        'amount' => 'float',
        'transaction_date' => 'dateTime'
    ];

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Return an Investment based on an Investor and time period
     *
     * @param int $investorId
     * @param string $startPeriod
     * @param string $endPeriod
     * @return array|mixed|null
     */
    public function getByInvestor(int $investorId, string $startPeriod, string $endPeriod)
    {
        return $this->where('investor_id', '=', $investorId)
            ->where('transaction_date', '>=', $startPeriod)
            ->where('transaction_date', '<', $endPeriod)
            ->get();
    }
}

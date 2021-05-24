<?php


namespace Models;


use system\BaseModel;

class Tranche extends BaseModel
{
    public $table = 'tranches';

    public $fields = [
        'id' => 'int',
        'title' => 'string',
        'loan_id' => 'int',
        'interest_month_pc' => 'float',
        'max_value' => 'float',
        'locked' => 'int'
    ];

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Return the parent loan for the Tranche
     *
     * @return Loan|null
     */
    public function loan()
    {
        return (new Loan())->find($this->loan_id);
    }
}

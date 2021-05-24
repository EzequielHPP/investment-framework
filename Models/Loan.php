<?php

namespace Models;

use system\BaseModel;

class Loan extends BaseModel
{
    public $table = 'loans';

    public $fields = [
        'id' => 'int',
        'start_date' => 'date',
        'end_date' => 'date'
    ];

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Return the associated Tranches
     *
     * @return array|mixed
     */
    public function tranches()
    {
        return (new Tranche())->where('loan_id', $this->id)->get();
    }
}

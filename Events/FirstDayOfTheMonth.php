<?php

namespace Events;

class FirstDayOfTheMonth
{

    public $date;

    public function __construct(string $date)
    {
        $this->date = $date;
    }

}

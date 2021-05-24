<?php

include 'system/boot.php';

$investor = (new \Models\Investor());
$investor->name = 'John Doe';
$investor->save();
event(new \Events\InvestorCreated($investor->id));

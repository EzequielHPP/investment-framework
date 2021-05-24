<?php


namespace system\interfaces;


interface Listener
{

    public function __construct($event);

    public function handle();
}

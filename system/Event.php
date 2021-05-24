<?php
if (!function_exists('event')) {
    function event($event)
    {
        $eventClass = get_class($event);
        $events = require(ROOT . DIRECTORY_SEPARATOR . 'Events' . DIRECTORY_SEPARATOR . 'kernel.php');

        if (array_key_exists($eventClass, $events)) {
            foreach ($events[$eventClass] as $listener) {
                (new $listener($event))->handle();
            }
        }
    }
}

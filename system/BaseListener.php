<?php


namespace system;


class BaseListener
{
    public $properties;

    /**
     * BaseListener constructor.
     * If we have properties that the Listener requires, then this checks if they are provided by the event
     * If not then throws an exception that maybe this isn't the right listener for the event
     *
     * @param $event
     * @throws \Exception
     */
    public function __construct($event)
    {
        if (is_iterable($this->properties)) {
            foreach ($this->properties as $property) {
                if (!property_exists($event, $property)) {
                    throw new \Exception('Invalid Listener for event: ' . get_class($event));
                }
                $this->$property = $event->$property;
            }
        }
    }
}

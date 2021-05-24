<?php


namespace system;


class Log
{
    /**
     * Just show a message on the screen
     *
     * @param $message
     */
    public function info($message)
    {
        echo $message;
    }

    /**
     * Show the error message, and if is an instance of Throwable then show the file and line.
     * You might also want to kill the procedure, so you can pass the second parameter as true to die.
     *
     * @param $message
     * @param false $die
     */
    public function error($message, $die = false)
    {
        echo 'ERROR:';
        if ($message instanceof \Throwable) {
            echo $message->getMessage() . ' (' . $message->getFile() . ':' . $message->getLine() . ')';
        } else {
            echo $message;
        }
        if ($die) {
            die();
        }
        echo "\n";
    }
}

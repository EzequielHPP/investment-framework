<?php
include 'system/constants.php';
include 'system/Event.php';

spl_autoload_register(function ($class_name) {
    $filename = str_replace('_', DIRECTORY_SEPARATOR, $class_name) . '.php';
    $filename = str_replace('\\', DIRECTORY_SEPARATOR, $filename);
    $fullPath = ROOT . DIRECTORY_SEPARATOR . $filename;
    if (file_exists($fullPath)) {
        require_once($fullPath);
    } else {
        return false;
    }
});

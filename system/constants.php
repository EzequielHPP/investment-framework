<?php

const ROOT = __DIR__ . DIRECTORY_SEPARATOR . '..';

if (!defined('system')) {
    define('system', ROOT . DIRECTORY_SEPARATOR . 'system' . DIRECTORY_SEPARATOR);
}
if (!defined('MODELS')) {
    define('MODELS', ROOT . DIRECTORY_SEPARATOR . 'Models' . DIRECTORY_SEPARATOR);
}
if (!defined('CONTROLLERS')) {
    define('CONTROLLERS', ROOT . DIRECTORY_SEPARATOR . 'Controllers' . DIRECTORY_SEPARATOR);
}
if (!defined('db')) {
    define('db', system . 'db' . DIRECTORY_SEPARATOR);
}
if (!defined('dbbackup')) {
    define('dbbackup', system . 'db_backup' . DIRECTORY_SEPARATOR);
}

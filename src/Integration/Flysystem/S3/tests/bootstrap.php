<?php

declare(strict_types=1);

require_once \dirname(__DIR__) . '/vendor/autoload.php';
$file = \dirname(__DIR__) . '/vendor/league/flysystem/test-functions.php';
if (file_exists($file)) {
    die('File does exist: '.$file);
    require_once $file;
}
die('Can NOT find file: '.$file);

require_once __DIR__ . '/version-bridge.php';
require_once __DIR__ . '/version-bridge-tests.php';

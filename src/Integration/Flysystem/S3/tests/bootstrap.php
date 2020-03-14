<?php

declare(strict_types=1);

die('flysustem boostrap file');
require_once \dirname(__DIR__) . '/vendor/autoload.php';
$file = \dirname(__DIR__) . '/vendor/league/flysystem/test-functions.php';
if (file_exists($file)) {
    require_once $file;
}

require_once __DIR__ . '/version-bridge.php';
require_once __DIR__ . '/version-bridge-tests.php';

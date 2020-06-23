<?php

use App\Kernel;
use Lib\App\Config\JsonConfig;

require __DIR__ . '/../vendor/autoload.php';

$projectDir = (__DIR__.'/..');
$config = new JsonConfig($projectDir.'/config/app.json');
$kernel = new Kernel($projectDir, $config);
$kernel->handle();

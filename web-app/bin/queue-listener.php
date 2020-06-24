<?php

use App\Queue\Kernel as QueueKernel;
use Lib\App\Config\JsonConfig;

require __DIR__ . '/../vendor/autoload.php';

$projectDir = (__DIR__.'/..');
$config = new JsonConfig($projectDir.'/config/app.json');
$kernel = new QueueKernel($projectDir, $config);
$kernel->handle();

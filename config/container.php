<?php

declare(strict_types=1);

use Laminas\ServiceManager\ServiceManager;

// Load configuration

$config=require 'config/autoload/database.global.php';
$capsul=new \App\Helpers\Db($config['database']);
$capsul=$capsul->getCapsule();

$config = require __DIR__ . '/config.php';

$dependencies                       = $config['dependencies'];
$dependencies['services']['config'] = $config;


return new ServiceManager($dependencies);

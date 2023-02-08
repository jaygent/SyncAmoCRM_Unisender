<?php

use Phpmig\Adapter;
use Pimple\Container;

$container = new Container();

$config=require 'config/autoload/database.global.php';
$capsul=new \App\Helpers\Db($config['database']);
$capsul=$capsul->getCapsule();

$container['db'] = function ($c) use ($capsul) {
    return $capsul;
};

$container['phpmig.adapter'] = function($c) {
    return new Adapter\Illuminate\Database($c['db'], 'migrations');
};
$container['phpmig.migrations_path'] = __DIR__ . DIRECTORY_SEPARATOR . 'migrations';

return $container;
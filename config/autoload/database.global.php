<?php

declare(strict_types=1);

$dotenv = Dotenv\Dotenv::createUnsafeImmutable(dirname(__DIR__, 2));
$dotenv->load();

return [
    'database' => [
        'driver' => 'mysql',
        'host' => getenv('MYSQL_HOST') ?: 'database',
        'database' => getenv('MYSQL_DATABASE') ?: '',
        'username' => getenv('MYSQL_USER') ?: '',
        'password' => getenv('MYSQL_PASSWORD') ?: '',
        'port' => getenv('MYSQL_PORT') ?: '3306',
        'charset' => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix' => '',
    ],
];

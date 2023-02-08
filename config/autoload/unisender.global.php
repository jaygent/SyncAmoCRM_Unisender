<?php

declare(strict_types=1);

$dotenv = Dotenv\Dotenv::createUnsafeImmutable(dirname(__DIR__, 2));
$dotenv->load();

return [
    'apikey'=>getenv('UNISENDER_API_KEY')??'',
];
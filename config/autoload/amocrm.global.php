<?php

declare(strict_types=1);

$dotenv = Dotenv\Dotenv::createUnsafeImmutable(dirname(__DIR__, 2));
$dotenv->load();

return [
    'amocrmapi' => [
        'clientId' => getenv('CLIENTIDAMO') ?? '',
        'clientSecret' => getenv('CLIENTSECRETAMO') ?? '',
        'redirectUri' => getenv('REDIRECTURL'),
    ],
];

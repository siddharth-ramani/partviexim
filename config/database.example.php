<?php
// Hosting: replace name, user and password with the credentials from your control panel.
// Environment variables with these names override the values below.
$local = __DIR__ . '/database.local.php';
if (is_file($local) && in_array($_SERVER['SERVER_ADDR'] ?? '', ['127.0.0.1', '::1'], true) && !getenv('PARTHVI_DB_NAME')) return require $local;
return [
    'host' => getenv('PARTHVI_DB_HOST') ?: '127.0.0.1',
    'port' => getenv('PARTHVI_DB_PORT') ?: '3306',
    'name' => getenv('PARTHVI_DB_NAME') ?: 'your_database',
    'user' => getenv('PARTHVI_DB_USER') ?: 'your_database_user',
    'password' => getenv('PARTHVI_DB_PASSWORD') !== false ? getenv('PARTHVI_DB_PASSWORD') : '',
];

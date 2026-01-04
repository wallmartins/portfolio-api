<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
/*
 * This file is part of Hyperf.
 *
 * @see     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
use function Hyperf\Support\env;

$driver = env('DB_DRIVER', 'sqlite');

$baseConfig = [
    'prefix' => env('DB_PREFIX', ''),
    'charset' => env('DB_CHARSET', 'utf8'),
    'collation' => env('DB_COLLATION', 'utf8_unicode_ci'),
    'foreign_key_constraints' => env('DB_FOREIGN_KEY_CONSTRAINTS', true),
    'commands' => [
        'gen:model' => [
            'path' => 'app/Model',
            'force_casts' => true,
            'inheritance' => 'Model',
        ],
    ],
];

// SQLite Configuration (Development)
$sqliteConfig = array_merge($baseConfig, [
    'driver' => 'sqlite',
    'database' => env('DB_DATABASE', BASE_PATH . '/runtime/database.sqlite'),
    'pool' => [
        'min_connections' => 1,
        'max_connections' => 1,
        'connect_timeout' => 10.0,
        'wait_timeout' => 3.0,
        'heartbeat' => -1,
        'max_idle_time' => 60.0,
    ],
]);

// PostgreSQL Configuration (Production)
$pgsqlConfig = array_merge($baseConfig, [
    'driver' => 'pgsql',
    'host' => env('DB_HOST', 'localhost'),
    'port' => env('DB_PORT', 5432),
    'database' => env('DB_DATABASE', 'portfolio'),
    'username' => env('DB_USERNAME', 'portfolio'),
    'password' => env('DB_PASSWORD', ''),
    'pool' => [
        'min_connections' => 1,
        'max_connections' => 10,
        'connect_timeout' => 10.0,
        'wait_timeout' => 3.0,
        'heartbeat' => -1,
        'max_idle_time' => 60.0,
    ],
    'options' => [
        // PDO options
        PDO::ATTR_CASE => PDO::CASE_NATURAL,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_ORACLE_NULLS => PDO::NULL_NATURAL,
        PDO::ATTR_STRINGIFY_FETCHES => false,
        PDO::ATTR_EMULATE_PREPARES => false,
    ],
]);

return [
    'default' => $driver === 'pgsql' ? $pgsqlConfig : $sqliteConfig,
];

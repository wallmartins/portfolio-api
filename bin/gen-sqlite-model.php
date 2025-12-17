#!/usr/bin/env php
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
// Get table name from command line
$tableName = $argv[1] ?? null;

if (! $tableName) {
    echo "Usage: php bin/gen-sqlite-model.php <table_name>\n";
    exit(1);
}

// Database connection
$dbPath = __DIR__ . '/../runtime/database.sqlite';
if (! file_exists($dbPath)) {
    echo "Error: Database file not found at {$dbPath}\n";
    exit(1);
}

$pdo = new PDO("sqlite:{$dbPath}");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Check if table exists
$stmt = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='{$tableName}'");
if (! $stmt->fetch()) {
    echo "Error: Table '{$tableName}' does not exist\n";
    exit(1);
}

// Get table columns
$stmt = $pdo->query("PRAGMA table_info({$tableName})");
$columns = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($columns)) {
    echo "Error: No columns found for table '{$tableName}'\n";
    exit(1);
}

// Generate model class name
$className = str_replace(' ', '', ucwords(str_replace('_', ' ', rtrim($tableName, 's'))));
if (substr($tableName, -1) !== 's') {
    $className = str_replace(' ', '', ucwords(str_replace('_', ' ', $tableName)));
}

// Prepare data
$fillable = [];
$casts = [];
$properties = [];

foreach ($columns as $column) {
    $name = $column['name'];
    $type = strtolower($column['type']);

    // Skip primary key from fillable
    if ($column['pk'] == 1) {
        continue;
    }

    // Skip timestamps from fillable (handled by Eloquent)
    if (in_array($name, ['created_at', 'updated_at'])) {
        continue;
    }

    $fillable[] = $name;
}

foreach ($columns as $column) {
    $name = $column['name'];
    $type = strtolower($column['type']);

    // Map SQLite types to PHP types
    $phpType = match (true) {
        str_contains($type, 'int') => ['cast' => 'integer', 'phpdoc' => 'int'],
        str_contains($type, 'real') || str_contains($type, 'float') || str_contains($type, 'double') => ['cast' => 'float', 'phpdoc' => 'float'],
        str_contains($type, 'bool') => ['cast' => 'boolean', 'phpdoc' => 'bool'],
        str_contains($type, 'date') || str_contains($type, 'time') => ['cast' => 'datetime', 'phpdoc' => '\Carbon\Carbon'],
        default => ['cast' => 'string', 'phpdoc' => 'string'],
    };

    $casts[$name] = $phpType['cast'];
    $properties[] = " * @property {$phpType['phpdoc']} \${$name}";
}

// Generate model file content
$modelContent = <<<PHP
<?php

declare(strict_types=1);

namespace App\\Model;

/**
{properties}
 */
class {$className} extends Model
{
    /**
     * The table associated with the model.
     */
    protected ?string \$table = '{$tableName}';

    /**
     * The attributes that are mass assignable.
     */
    protected array \$fillable = [
{fillable}
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected array \$casts = [
{casts}
    ];
}

PHP;

// Replace placeholders
$modelContent = str_replace('{properties}', implode("\n", $properties), $modelContent);
$modelContent = str_replace('{$className}', $className, $modelContent);
$modelContent = str_replace('{$tableName}', $tableName, $modelContent);
$modelContent = str_replace(
    '{fillable}',
    implode(",\n", array_map(fn ($f) => "        '{$f}'", $fillable)) . ',',
    $modelContent
);
$modelContent = str_replace(
    '{casts}',
    implode(",\n", array_map(fn ($k, $v) => "        '{$k}' => '{$v}'", array_keys($casts), $casts)) . ',',
    $modelContent
);

// Write model file
$modelPath = __DIR__ . "/../app/Model/{$className}.php";
file_put_contents($modelPath, $modelContent);

echo "Model {$className} generated successfully at {$modelPath}\n";
echo "Table: {$tableName}\n";
echo 'Columns: ' . count($columns) . "\n";

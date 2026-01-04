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
return [
    'enable' => true,
    'port' => 9500,
    'json_dir' => BASE_PATH . '/storage/swagger',
    'html' => file_exists(BASE_PATH . '/storage/swagger/index.html')
        ? file_get_contents(BASE_PATH . '/storage/swagger/index.html')
        : null,
    'url' => '/swagger',
    'auto_generate' => true,
    'scan' => [
        'paths' => [
            BASE_PATH . '/app/Controller',
        ],
    ],
    'processors' => [
        // users can append their own processors here
    ],
    'server' => [
        'http' => [
            'servers' => [
                [
                    'url' => 'http://127.0.0.1:9501',
                    'description' => 'Test Server',
                ],
            ],
            'info' => [
                'title' => 'Portfolio API',
                'description' => 'RESTful API for managing personal portfolio with blog posts, projects, professional experiences, technologies, and social links. Supports multi-language content (pt-BR and en-US).',
                'version' => '1.0.0',
                'contact' => [
                    'name' => 'API Support',
                ],
            ],
            'security' => [
                [
                    'BearerAuth' => [],
                ],
            ],
            'components' => [
                'securitySchemes' => [
                    'BearerAuth' => [
                        'type' => 'http',
                        'scheme' => 'bearer',
                        'bearerFormat' => 'JWT',
                        'description' => 'Enter JWT token obtained from GitHub OAuth authentication',
                    ],
                ],
            ],
        ],
    ],
];

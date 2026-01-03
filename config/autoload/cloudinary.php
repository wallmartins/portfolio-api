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
use function Hyperf\Support\env;

return [
    'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
    'api_key' => env('CLOUDINARY_API_KEY'),
    'api_secret' => env('CLOUDINARY_API_SECRET'),
    'secure' => true,

    'folder' => env('CLOUDINARY_FOLDER', 'portfolio'),

    'transformations' => [
        'thumbnail' => [
            'width' => 300,
            'height' => 300,
            'crop' => 'thumb',
            'quality' => 'auto',
            'fetch_format' => 'auto',
        ],
        'optimized' => [
            'width' => 1200,
            'crop' => 'limit',
            'quality' => 'auto:good',
            'fetch_format' => 'auto',
        ],
    ],

    'allowed_formats' => ['jpg', 'jpeg', 'png', 'webp', 'gif'],
    'max_file_size' => 5 * 1024 * 1024, // 5MB
];

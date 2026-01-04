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

namespace App\Controller;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'Portfolio API',
    description: 'RESTful API for managing personal portfolio with blog posts, projects, professional experiences, technologies, and social links. Supports multi-language content (pt-BR and en-US).',
)]
#[OA\Server(
    url: 'http://localhost:9501',
    description: 'Local development server'
)]
#[OA\SecurityScheme(
    securityScheme: 'BearerAuth',
    type: 'http',
    scheme: 'bearer',
    bearerFormat: 'JWT',
    description: 'JWT token obtained from GitHub OAuth authentication'
)]
#[OA\Tag(
    name: 'Authentication',
    description: 'GitHub OAuth authentication endpoints'
)]
#[OA\Tag(
    name: 'Portfolio',
    description: 'Public endpoints for portfolio content'
)]
#[OA\Tag(
    name: 'Admin - Blog',
    description: 'Admin endpoints for managing blog posts (requires authentication)'
)]
#[OA\Tag(
    name: 'Admin - Projects',
    description: 'Admin endpoints for managing projects (requires authentication)'
)]
#[OA\Tag(
    name: 'Admin - About',
    description: 'Admin endpoints for managing about information (requires authentication)'
)]
#[OA\Tag(
    name: 'Admin - Experiences',
    description: 'Admin endpoints for managing professional experiences (requires authentication)'
)]
#[OA\Tag(
    name: 'Admin - Technologies',
    description: 'Admin endpoints for managing technologies (requires authentication)'
)]
#[OA\Tag(
    name: 'Admin - Social Links',
    description: 'Admin endpoints for managing social media links (requires authentication)'
)]
class OpenApiSpec
{
}

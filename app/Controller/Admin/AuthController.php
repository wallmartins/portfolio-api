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

namespace App\Controller\Admin;

use App\Resource\Auth\AuthResource;
use App\Services\Auth\AuthService;
use App\Traits\RespondsWithResource;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Swagger\Annotation\HyperfServer;
use OnixSystemsPHP\HyperfSocialite\Contracts\Factory as SocialiteFactory;
use OpenApi\Attributes as OA;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;
use function Hyperf\Support\env;

#[HyperfServer('http')]
class AuthController
{
    use RespondsWithResource;

    #[Inject]
    protected ResponseInterface $response;

    public function __construct(
        private readonly SocialiteFactory $socialite,
        private readonly AuthService $authService
    ) {
    }

    /**
     * Redirect to GitHub OAuth.
     */
    #[OA\Get(
        path: '/auth/github/redirect',
        summary: 'Redirect to GitHub OAuth',
        tags: ['Authentication'],
        responses: [
            new OA\Response(
                response: 302,
                description: 'Redirect to GitHub OAuth authorization page'
            ),
        ]
    )]
    public function redirect(): PsrResponseInterface
    {
        return $this->socialite->driver('github')->redirect();
    }

    /**
     * Handle GitHub OAuth callback.
     */
    #[OA\Get(
        path: '/auth/github/callback',
        summary: 'Handle GitHub OAuth callback',
        tags: ['Authentication'],
        parameters: [
            new OA\Parameter(
                name: 'code',
                in: 'query',
                required: true,
                description: 'Authorization code from GitHub',
                schema: new OA\Schema(type: 'string')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Returns HTML page that sends auth data to opener window via postMessage'
            ),
            new OA\Response(response: 401, description: 'Authentication failed'),
        ]
    )]
    public function callback(): PsrResponseInterface
    {
        $githubUser = $this->socialite->driver('github')->user();

        $userData = [
            'id' => $githubUser->getId(),
            'name' => $githubUser->getName(),
            'nickname' => $githubUser->getNickname(),
            'email' => $githubUser->getEmail(),
        ];

        $authData = $this->authService->findOrCreateUser($userData);

        $authResponse = [
            'token' => $authData['accessToken'],
            'name' => $authData['user']->name,
            'email' => $authData['user']->email,
            'avatar' => $githubUser->getAvatar(),
        ];

        $frontendUrl = env('FRONTEND_URL', 'http://localhost:3000');
        $authDataJson = json_encode($authResponse);

        $html = <<<HTML
<!DOCTYPE html>
<html>
<head>
    <title>Authentication Successful</title>
    <style>
        body {
            font-family: system-ui, -apple-system, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            background: #f5f5f5;
        }
        .container {
            text-align: center;
            padding: 2rem;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .success {
            color: #22c55e;
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        h1 { margin: 0 0 0.5rem 0; }
        p { color: #666; margin: 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="success">✓</div>
        <h1>Authentication Successful</h1>
        <p>You can close this window now.</p>
    </div>
    <script>
        const authData = {$authDataJson};

        // Send auth data to the opener window
        if (window.opener) {
            window.opener.postMessage({
                type: 'AUTH_SUCCESS',
                data: authData
            }, '{$frontendUrl}');

            // Auto-close after sending
            setTimeout(() => {
                window.close();
            }, 1000);
        } else {
            console.error('No opener window found');
        }
    </script>
</body>
</html>
HTML;

        return $this->response->html($html);
    }

}

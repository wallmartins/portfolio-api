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

namespace App\Traits;

use App\Resource\JsonResource;
use App\Resource\ResourceCollection;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

trait RespondsWithResource
{
    /**
     * Return a JSON response with a resource.
     */
    protected function jsonResource(
        JsonResource|ResourceCollection $resource,
        int $status = 200
    ): PsrResponseInterface {
        return $this->response->json($resource->toArray())->withStatus($status);
    }

    /**
     * Return a created response (201) with a resource.
     */
    protected function created(JsonResource $resource): PsrResponseInterface
    {
        return $this->jsonResource($resource, 201);
    }

    /**
     * Return a success response (200) with a message.
     */
    protected function success(string $message, array $data = []): PsrResponseInterface
    {
        return $this->response->json([
            'message' => $message,
            ...$data,
        ]);
    }

    /**
     * Return a no content response (204) with an optional message.
     */
    protected function noContent(string $message = 'Operation successful'): PsrResponseInterface
    {
        return $this->response->json([
            'message' => $message,
        ])->withStatus(204);
    }

    /**
     * Return an error response with a custom status code.
     */
    protected function error(string $message, int $status = 400, array $errors = []): PsrResponseInterface
    {
        $response = [
            'message' => $message,
        ];

        if (! empty($errors)) {
            $response['errors'] = $errors;
        }

        return $this->response->json($response)->withStatus($status);
    }
}

<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait SendsResponse
{
    /**
     * @property-read mixed $data
     * @property-read \Illuminate\Http\Response $status
     */
    public function toResponse($request): JsonResponse
    {
        return new JsonResponse(
            data: $this->data,
            status: $this->status,
        );
    }
}

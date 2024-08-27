<?php

namespace App\Http\Responses;

use App\Traits\SendsResponse;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Response;

readonly class JsonApiResponse implements Responsable
{
    use SendsResponse;

    public function __construct(
        public array $data,
        public Response|int $status = Response::HTTP_OK,
    ) {}
}

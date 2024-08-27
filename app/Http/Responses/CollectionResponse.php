<?php

namespace App\Http\Responses;

use App\Traits\SendsResponse;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;

readonly class CollectionResponse implements Responsable
{
    use SendsResponse;

    public function __construct(
        public ResourceCollection $data,
        public Response|int $status = Response::HTTP_OK,
    ) {}
}

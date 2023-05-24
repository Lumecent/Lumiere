<?php

namespace App\Abstractions\Requests;

use Illuminate\Http\Request as IlluminateRequest;

class Request
{
    private IlluminateRequest $request;

    public function __construct( IlluminateRequest $request )
    {
        $this->request = $request;
    }

    public function getHeader( string $name ): ?string
    {
        return $this->request->header( $name );
    }

    public function get( string $name ): string|array|null
    {
        return $this->request->get( $name );
    }

    public function all( ?array $keys = null ): array
    {
        return $this->request->all( $keys );
    }

    public function getAuthToken( string $authType = 'Bearer' ): string
    {
        return str_replace( "$authType ", '', $this->getHeader( 'Authorization' ) ?? '' );
    }

    public function toArray(): array
    {
        return $this->request->toArray();
    }
}

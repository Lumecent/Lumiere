<?php

namespace App\Abstractions\Http\Requests;

use Illuminate\Http\Request as IlluminateRequest;

class Request
{
    private IlluminateRequest $request;

    public function __construct( IlluminateRequest $request )
    {
        $this->request = $request;
    }

    public function getUserAgent(): ?string
    {
        return $this->request->userAgent();
    }

    public function getHeader( string $name, ?string $default = null ): ?string
    {
        return $this->request->header( $name, $default );
    }

    public function get( string $name ): string|array|null
    {
        return $this->request->get( $name );
    }

    public function all( ?array $keys = null ): array
    {
        return $this->request->all( $keys );
    }

    public function getCookie( string $name, mixed $default = null ): array|string|null
    {
        return $this->request->cookie( $name, $default );
    }

    public function getAuthToken(): string
    {
        return str_replace( 'Bearer ', '', $this->getHeader( 'Authorization', $this->getHeader( 'authorization' ) ) ?? '' );
    }

    public function toArray(): array
    {
        return $this->request->toArray();
    }
}

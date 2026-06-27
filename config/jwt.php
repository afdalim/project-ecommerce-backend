<?php

return [
    'secret' => env('JWT_SECRET', 'secret'),
    'algorithm' => env('JWT_ALGORITHM', 'HS256'),
    'ttl' => env('JWT_TTL', 60),
    'refresh_ttl' => env('JWT_REFRESH_TTL', 20160),
    'user' => App\Models\User::class,
    'identifier' => 'id',
    'required_claims' => ['iss', 'iat', 'exp', 'nbf', 'jti'],
];

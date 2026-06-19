<?php

declare(strict_types=1);

namespace Core;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Symfony\Component\HttpFoundation\Request;

class AuthMiddleware
{
    private static string $secretKey;

    public static function init(string $secretKey): void
    {
        self::$secretKey = $secretKey;
    }

    public static function authenticate(Request $request): ?array
    {
        $token = self::getTokenFromRequest($request);

        if ($token === null) {
            return null;
        }

        try {
            $decoded = JWT::decode($token, new Key(self::$secretKey, 'HS256'));
            return (array) $decoded;
        } catch (\Exception $e) {
            return null;
        }
    }

    private static function getTokenFromRequest(Request $request): ?string
    {
        $authHeader = $request->headers->get('Authorization');

        if ($authHeader !== null && preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
            return $matches[1];
        }

        return null;
    }

    public static function generateToken(array $payload): string
    {
        $issuedAt = time();
        $expire = $issuedAt + 3600;

        $payload['iat'] = $issuedAt;
        $payload['exp'] = $expire;

        return JWT::encode($payload, self::$secretKey, 'HS256');
    }
}

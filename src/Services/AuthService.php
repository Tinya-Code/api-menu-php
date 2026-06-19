<?php

declare(strict_types=1);

namespace Services;

use Core\AuthMiddleware;
use Core\Database;
use Doctrine\DBAL\Connection;

class AuthService
{
    private Connection $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function login(string $email, string $password): ?array
    {
        $user = $this->findUserByEmail($email);

        if ($user === null) {
            return null;
        }

        if (password_verify($password, $user['password'])) {
            $token = AuthMiddleware::generateToken([
                'user_id' => $user['id'],
                'email' => $user['email']
            ]);

            return [
                'token' => $token,
                'user' => [
                    'id' => $user['id'],
                    'email' => $user['email']
                ]
            ];
        }

        return null;
    }

    private function findUserByEmail(string $email): ?array
    {
        $qb = $this->db->createQueryBuilder();
        $result = $qb
            ->select('*')
            ->from('users')
            ->where('email = :email')
            ->setParameter('email', $email)
            ->executeQuery()
            ->fetchAssociative();

        return $result ?: null;
    }

    public function register(string $email, string $password): array
    {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $this->db->insert('users', [
            'email' => $email,
            'password' => $hashedPassword,
            'created_at' => (new \DateTime())->format('Y-m-d H:i:s')
        ]);

        $userId = $this->db->lastInsertId();

        return [
            'id' => $userId,
            'email' => $email
        ];
    }
}

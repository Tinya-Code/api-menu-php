<?php

declare(strict_types=1);

namespace Core;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;

class Database
{
    private static ?Connection $connection = null;

    public static function getConnection(): Connection
    {
        if (self::$connection === null) {
            self::$connection = DriverManager::getConnection([
                'dbname' => $_ENV['DB_NAME'] ?? 'admin_menu',
                'user' => $_ENV['DB_USER'] ?? 'root',
                'password' => $_ENV['DB_PASSWORD'] ?? '',
                'host' => $_ENV['DB_HOST'] ?? 'localhost',
                'driver' => $_ENV['DB_DRIVER'] ?? 'pdo_mysql',
            ]);
        }

        return self::$connection;
    }
}

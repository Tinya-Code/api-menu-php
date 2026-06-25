<?php

declare(strict_types=1);

namespace Modules\Settings;

use Core\Database;
use Doctrine\DBAL\Connection;

class SettingsRepository
{
    private Connection $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function find(): ?SettingsEntity
    {
        $this->ensureTableExists();

        $qb = $this->db->createQueryBuilder();
        $result = $qb
            ->select('*')
            ->from('restaurant_settings')
            ->where('id = 1')
            ->executeQuery()
            ->fetchAssociative();

        if ($result === false) {
            return null;
        }

        $data = json_decode($result['data'], true);

        return new SettingsEntity(
            $data['restaurant'] ?? [],
            $data['settings'] ?? [],
            (int) $result['id'],
            $result['created_at'],
            $result['updated_at']
        );
    }

    public function save(RegisterSettingsDTO $dto): SettingsEntity
    {
        $this->ensureTableExists();

        $now = (new \DateTime())->format('Y-m-d H:i:s');
        $data = json_encode($dto->toArray());

        $existing = $this->db->createQueryBuilder()
            ->select('id')
            ->from('restaurant_settings')
            ->where('id = 1')
            ->executeQuery()
            ->fetchOne();

        if ($existing === false) {
            $this->db->insert('restaurant_settings', [
                'id' => 1,
                'data' => $data,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        } else {
            $this->db->update('restaurant_settings', [
                'data' => $data,
                'updated_at' => $now,
            ], ['id' => 1]);
        }

        return new SettingsEntity(
            $dto->getRestaurant(),
            $dto->getSettings(),
            1,
            $now,
            $now
        );
    }

    private function ensureTableExists(): void
    {
        $this->db->executeStatement('
            CREATE TABLE IF NOT EXISTS restaurant_settings (
                id INT PRIMARY KEY DEFAULT 1,
                data JSON NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )
        ');
    }
}

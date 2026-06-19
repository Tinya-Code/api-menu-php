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

    public function findAll(): array
    {
        $qb = $this->db->createQueryBuilder();
        $result = $qb
            ->select('*')
            ->from('settings')
            ->executeQuery()
            ->fetchAllAssociative();

        return array_map(fn($row) => new SettingsEntity(
            $row['key'],
            $row['value'],
            $row['description'],
            (int) $row['id'],
            $row['created_at'],
            $row['updated_at']
        ), $result);
    }

    public function findById(int $id): ?SettingsEntity
    {
        $qb = $this->db->createQueryBuilder();
        $result = $qb
            ->select('*')
            ->from('settings')
            ->where('id = :id')
            ->setParameter('id', $id)
            ->executeQuery()
            ->fetchAssociative();

        if ($result === false) {
            return null;
        }

        return new SettingsEntity(
            $result['key'],
            $result['value'],
            $result['description'],
            (int) $result['id'],
            $result['created_at'],
            $result['updated_at']
        );
    }

    public function findByKey(string $key): ?SettingsEntity
    {
        $qb = $this->db->createQueryBuilder();
        $result = $qb
            ->select('*')
            ->from('settings')
            ->where('`key` = :key')
            ->setParameter('key', $key)
            ->executeQuery()
            ->fetchAssociative();

        if ($result === false) {
            return null;
        }

        return new SettingsEntity(
            $result['key'],
            $result['value'],
            $result['description'],
            (int) $result['id'],
            $result['created_at'],
            $result['updated_at']
        );
    }

    public function create(RegistrarSettingsDTO $dto): SettingsEntity
    {
        $now = (new \DateTime())->format('Y-m-d H:i:s');
        
        $this->db->insert('settings', [
            'key' => $dto->getKey(),
            'value' => $dto->getValue(),
            'description' => $dto->getDescription(),
            'created_at' => $now,
            'updated_at' => $now
        ]);

        $id = (int) $this->db->lastInsertId();

        return new SettingsEntity(
            $dto->getKey(),
            $dto->getValue(),
            $dto->getDescription(),
            $id,
            $now,
            $now
        );
    }

    public function update(int $id, RegistrarSettingsDTO $dto): ?SettingsEntity
    {
        $now = (new \DateTime())->format('Y-m-d H:i:s');
        
        $affectedRows = $this->db->update('settings', [
            'key' => $dto->getKey(),
            'value' => $dto->getValue(),
            'description' => $dto->getDescription(),
            'updated_at' => $now
        ], ['id' => $id]);

        if ($affectedRows === 0) {
            return null;
        }

        return $this->findById($id);
    }

    public function delete(int $id): bool
    {
        $affectedRows = $this->db->delete('settings', ['id' => $id]);
        return $affectedRows > 0;
    }
}

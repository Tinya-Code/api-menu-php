<?php

declare(strict_types=1);

namespace Modules\Combo;

use Core\Database;
use Doctrine\DBAL\Connection;

class ComboRepository
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
            ->from('combos')
            ->executeQuery()
            ->fetchAllAssociative();

        return array_map(fn($row) => new ComboEntity(
            $row['name'],
            $row['description'],
            (float) $row['price'],
            (int) $row['id'],
            $row['created_at'],
            $row['updated_at']
        ), $result);
    }

    public function findById(int $id): ?ComboEntity
    {
        $qb = $this->db->createQueryBuilder();
        $result = $qb
            ->select('*')
            ->from('combos')
            ->where('id = :id')
            ->setParameter('id', $id)
            ->executeQuery()
            ->fetchAssociative();

        if ($result === false) {
            return null;
        }

        return new ComboEntity(
            $result['name'],
            $result['description'],
            (float) $result['price'],
            (int) $result['id'],
            $result['created_at'],
            $result['updated_at']
        );
    }

    public function create(RegistrarComboDTO $dto): ComboEntity
    {
        $now = (new \DateTime())->format('Y-m-d H:i:s');
        
        $this->db->insert('combos', [
            'name' => $dto->getName(),
            'description' => $dto->getDescription(),
            'price' => $dto->getPrice(),
            'created_at' => $now,
            'updated_at' => $now
        ]);

        $id = (int) $this->db->lastInsertId();

        return new ComboEntity(
            $dto->getName(),
            $dto->getDescription(),
            $dto->getPrice(),
            $id,
            $now,
            $now
        );
    }

    public function update(int $id, RegistrarComboDTO $dto): ?ComboEntity
    {
        $now = (new \DateTime())->format('Y-m-d H:i:s');
        
        $affectedRows = $this->db->update('combos', [
            'name' => $dto->getName(),
            'description' => $dto->getDescription(),
            'price' => $dto->getPrice(),
            'updated_at' => $now
        ], ['id' => $id]);

        if ($affectedRows === 0) {
            return null;
        }

        return $this->findById($id);
    }

    public function delete(int $id): bool
    {
        $affectedRows = $this->db->delete('combos', ['id' => $id]);
        return $affectedRows > 0;
    }
}

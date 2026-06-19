<?php

declare(strict_types=1);

namespace Modules\Category;

use Core\Database;
use Doctrine\DBAL\Connection;

class CategoryRepository
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
            ->from('categories')
            ->executeQuery()
            ->fetchAllAssociative();

        return array_map(fn($row) => new CategoryEntity(
            $row['name'],
            $row['description'],
            (int) $row['id'],
            $row['created_at'],
            $row['updated_at']
        ), $result);
    }

    public function findById(int $id): ?CategoryEntity
    {
        $qb = $this->db->createQueryBuilder();
        $result = $qb
            ->select('*')
            ->from('categories')
            ->where('id = :id')
            ->setParameter('id', $id)
            ->executeQuery()
            ->fetchAssociative();

        if ($result === false) {
            return null;
        }

        return new CategoryEntity(
            $result['name'],
            $result['description'],
            (int) $result['id'],
            $result['created_at'],
            $result['updated_at']
        );
    }

    public function create(RegistrarCategoryDTO $dto): CategoryEntity
    {
        $now = (new \DateTime())->format('Y-m-d H:i:s');
        
        $this->db->insert('categories', [
            'name' => $dto->getName(),
            'description' => $dto->getDescription(),
            'created_at' => $now,
            'updated_at' => $now
        ]);

        $id = (int) $this->db->lastInsertId();

        return new CategoryEntity(
            $dto->getName(),
            $dto->getDescription(),
            $id,
            $now,
            $now
        );
    }

    public function update(int $id, RegistrarCategoryDTO $dto): ?CategoryEntity
    {
        $now = (new \DateTime())->format('Y-m-d H:i:s');
        
        $affectedRows = $this->db->update('categories', [
            'name' => $dto->getName(),
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
        $affectedRows = $this->db->delete('categories', ['id' => $id]);
        return $affectedRows > 0;
    }
}

<?php

declare(strict_types=1);

namespace Modules\Category;

use Core\Database;
use Doctrine\DBAL\Connection;
use Ramsey\Uuid\Uuid;

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
            $row['block_id'],
            $row['id'],
            $row['created_at'],
            $row['updated_at']
        ), $result);
    }

    public function findById(string $id): ?CategoryEntity
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
            $result['block_id'],
            $result['id'],
            $result['created_at'],
            $result['updated_at']
        );
    }

    public function create(RegisterCategoryDTO $dto): CategoryEntity
    {
        $id = Uuid::uuid4()->toString();
        $now = (new \DateTime())->format('Y-m-d H:i:s');

        $this->db->insert('categories', [
            'id' => $id,
            'name' => $dto->getName(),
            'description' => $dto->getDescription(),
            'block_id' => $dto->getBlockId(),
            'created_at' => $now,
            'updated_at' => $now
        ]);

        return new CategoryEntity(
            $dto->getName(),
            $dto->getDescription(),
            $dto->getBlockId(),
            $id,
            $now,
            $now
        );
    }

    public function update(string $id, RegisterCategoryDTO $dto): ?CategoryEntity
    {
        $now = (new \DateTime())->format('Y-m-d H:i:s');

        $affectedRows = $this->db->update('categories', [
            'name' => $dto->getName(),
            'description' => $dto->getDescription(),
            'block_id' => $dto->getBlockId(),
            'updated_at' => $now
        ], ['id' => $id]);

        if ($affectedRows === 0) {
            return null;
        }

        return $this->findById($id);
    }

    public function delete(string $id): bool
    {
        $affectedRows = $this->db->delete('categories', ['id' => $id]);
        return $affectedRows > 0;
    }
}

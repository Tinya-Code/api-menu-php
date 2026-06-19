<?php

declare(strict_types=1);

namespace Modules\PriceRange;

use Core\Database;
use Doctrine\DBAL\Connection;

class PriceRangeRepository
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
            ->from('price_ranges')
            ->executeQuery()
            ->fetchAllAssociative();

        return array_map(fn($row) => new PriceRangeEntity(
            $row['name'],
            (float) $row['min_price'],
            (float) $row['max_price'],
            (int) $row['id'],
            $row['created_at'],
            $row['updated_at']
        ), $result);
    }

    public function findById(int $id): ?PriceRangeEntity
    {
        $qb = $this->db->createQueryBuilder();
        $result = $qb
            ->select('*')
            ->from('price_ranges')
            ->where('id = :id')
            ->setParameter('id', $id)
            ->executeQuery()
            ->fetchAssociative();

        if ($result === false) {
            return null;
        }

        return new PriceRangeEntity(
            $result['name'],
            (float) $result['min_price'],
            (float) $result['max_price'],
            (int) $result['id'],
            $result['created_at'],
            $result['updated_at']
        );
    }

    public function create(RegistrarPriceRangeDTO $dto): PriceRangeEntity
    {
        $now = (new \DateTime())->format('Y-m-d H:i:s');
        
        $this->db->insert('price_ranges', [
            'name' => $dto->getName(),
            'min_price' => $dto->getMinPrice(),
            'max_price' => $dto->getMaxPrice(),
            'created_at' => $now,
            'updated_at' => $now
        ]);

        $id = (int) $this->db->lastInsertId();

        return new PriceRangeEntity(
            $dto->getName(),
            $dto->getMinPrice(),
            $dto->getMaxPrice(),
            $id,
            $now,
            $now
        );
    }

    public function update(int $id, RegistrarPriceRangeDTO $dto): ?PriceRangeEntity
    {
        $now = (new \DateTime())->format('Y-m-d H:i:s');
        
        $affectedRows = $this->db->update('price_ranges', [
            'name' => $dto->getName(),
            'min_price' => $dto->getMinPrice(),
            'max_price' => $dto->getMaxPrice(),
            'updated_at' => $now
        ], ['id' => $id]);

        if ($affectedRows === 0) {
            return null;
        }

        return $this->findById($id);
    }

    public function delete(int $id): bool
    {
        $affectedRows = $this->db->delete('price_ranges', ['id' => $id]);
        return $affectedRows > 0;
    }
}

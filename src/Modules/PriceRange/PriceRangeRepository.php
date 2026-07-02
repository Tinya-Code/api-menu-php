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
            ->orderBy('quantity', 'ASC')
            ->executeQuery()
            ->fetchAllAssociative();

        return array_map(fn($row) => $this->mapRow($row), $result);
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

        return $this->mapRow($result);
    }

    public function findByProductId(int $productId): array
    {
        $qb = $this->db->createQueryBuilder();
        $result = $qb
            ->select('*')
            ->from('price_ranges')
            ->where('product_id = :product_id')
            ->setParameter('product_id', $productId)
            ->orderBy('quantity', 'ASC')
            ->executeQuery()
            ->fetchAllAssociative();

        return array_map(fn($row) => $this->mapRow($row), $result);
    }

    public function create(RegisterPriceRangeDTO $dto): PriceRangeEntity
    {
        $now = (new \DateTime())->format('Y-m-d H:i:s');

        $this->db->insert('price_ranges', [
            'product_id' => $dto->getProductId(),
            'quantity' => $dto->getQuantity(),
            'unit' => $dto->getUnit(),
            'price' => $dto->getPrice(),
            'bonus' => $dto->getBonus(),
            'is_default' => $dto->isDefault() ? 1 : 0,
            'created_at' => $now,
            'updated_at' => $now
        ]);

        $id = (int) $this->db->lastInsertId();

        return new PriceRangeEntity(
            $dto->getProductId(),
            $dto->getQuantity(),
            $dto->getPrice(),
            $dto->getUnit(),
            $dto->getBonus(),
            $dto->isDefault(),
            $id,
            $now,
            $now
        );
    }

    public function update(int $id, RegisterPriceRangeDTO $dto): ?PriceRangeEntity
    {
        $now = (new \DateTime())->format('Y-m-d H:i:s');

        $affectedRows = $this->db->update('price_ranges', [
            'product_id' => $dto->getProductId(),
            'quantity' => $dto->getQuantity(),
            'unit' => $dto->getUnit(),
            'price' => $dto->getPrice(),
            'bonus' => $dto->getBonus(),
            'is_default' => $dto->isDefault() ? 1 : 0,
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

    public function deleteByProductId(int $productId): void
    {
        $this->db->delete('price_ranges', ['product_id' => $productId]);
    }

    public function deleteMultiple(array $ids): void
    {
        if (empty($ids)) {
            return;
        }

        $this->db->delete('price_ranges', ['id' => $ids]);
    }

    public function clearDefaultForProduct(int $productId): void
    {
        $this->db->update(
            'price_ranges',
            ['is_default' => 0],
            ['product_id' => $productId, 'is_default' => 1]
        );
    }

    public function beginTransaction(): void
    {
        $this->db->beginTransaction();
    }

    public function commit(): void
    {
        $this->db->commit();
    }

    public function rollBack(): void
    {
        $this->db->rollBack();
    }

    private function mapRow(array $row): PriceRangeEntity
    {
        return new PriceRangeEntity(
            (int) $row['product_id'],
            (float) $row['quantity'],
            (float) $row['price'],
            $row['unit'] ?: null,
            $row['bonus'] ?: null,
            (bool) $row['is_default'],
            (int) $row['id'],
            $row['created_at'],
            $row['updated_at']
        );
    }
}

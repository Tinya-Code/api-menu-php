<?php

declare(strict_types=1);

namespace Modules\ProductPrice;

use Core\Database;
use Doctrine\DBAL\Connection;
use Ramsey\Uuid\Uuid;

class ProductPriceRepository
{
    private Connection $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function findAll(?string $productId = null): array
    {
        $qb = $this->db->createQueryBuilder();
        $qb
            ->select('*')
            ->from('product_prices');

        if ($productId !== null) {
            $qb
                ->where('product_id = :product_id')
                ->setParameter('product_id', $productId);
        }

        $result = $qb->executeQuery()->fetchAllAssociative();

        return array_map(fn($row) => $this->mapRow($row), $result);
    }

    public function findById(string $id): ?ProductPriceEntity
    {
        $qb = $this->db->createQueryBuilder();
        $result = $qb
            ->select('*')
            ->from('product_prices')
            ->where('id = :id')
            ->setParameter('id', $id)
            ->executeQuery()
            ->fetchAssociative();

        if ($result === false) {
            return null;
        }

        return $this->mapRow($result);
    }

    public function create(RegisterProductPriceDTO $dto): ProductPriceEntity
    {
        $id = Uuid::uuid4()->toString();

        $this->db->insert('product_prices', [
            'id' => $id,
            'product_id' => $dto->getProductId(),
            'price' => $dto->getPrice(),
            'name' => $dto->getName(),
            'description' => $dto->getDescription(),
            'start_day' => $dto->getStartDay(),
            'end_day' => $dto->getEndDay(),
            'start_datetime' => $dto->getStartDatetime(),
            'end_datetime' => $dto->getEndDatetime(),
            'rule_type' => $dto->getRuleType()
        ]);

        return new ProductPriceEntity(
            $dto->getProductId(),
            $dto->getPrice(),
            $dto->getRuleType(),
            $dto->getName(),
            $dto->getDescription(),
            $dto->getStartDay(),
            $dto->getEndDay(),
            $dto->getStartDatetime(),
            $dto->getEndDatetime(),
            $id
        );
    }

    public function update(string $id, RegisterProductPriceDTO $dto): ?ProductPriceEntity
    {
        $affectedRows = $this->db->update('product_prices', [
            'product_id' => $dto->getProductId(),
            'price' => $dto->getPrice(),
            'name' => $dto->getName(),
            'description' => $dto->getDescription(),
            'start_day' => $dto->getStartDay(),
            'end_day' => $dto->getEndDay(),
            'start_datetime' => $dto->getStartDatetime(),
            'end_datetime' => $dto->getEndDatetime(),
            'rule_type' => $dto->getRuleType()
        ], ['id' => $id]);

        if ($affectedRows === 0) {
            return null;
        }

        return $this->findById($id);
    }

    public function deleteByProductId(string $productId): void
    {
        $this->db->delete('product_prices', ['product_id' => $productId]);
    }

    public function delete(string $id): bool
    {
        $affectedRows = $this->db->delete('product_prices', ['id' => $id]);
        return $affectedRows > 0;
    }

    private function mapRow(array $row): ProductPriceEntity
    {
        return new ProductPriceEntity(
            $row['product_id'],
            (float) $row['price'],
            $row['rule_type'],
            $row['name'] ?? null,
            $row['description'] ?? null,
            $row['start_day'] !== null ? (int) $row['start_day'] : null,
            $row['end_day'] !== null ? (int) $row['end_day'] : null,
            $row['start_datetime'],
            $row['end_datetime'],
            $row['id']
        );
    }
}

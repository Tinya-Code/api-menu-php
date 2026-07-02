<?php

declare(strict_types=1);

namespace Modules\Product;

use Core\Database;
use Doctrine\DBAL\Connection;

class ProductRepository
{
    private Connection $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function findAll(int $limit, int $offset): array
    {
        $qb = $this->db->createQueryBuilder();
        $result = $qb
            ->select('*')
            ->from('products')
            ->orderBy('id', 'ASC')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->executeQuery()
            ->fetchAllAssociative();

        return array_map(fn($row) => new ProductEntity(
            $row['name'],
            $row['description'],
            (float) $row['price'],
            $row['category_id'],
            $row['price_range_id'] ? (int) $row['price_range_id'] : null,
            $row['image_url'],
            (bool) $row['is_active'],
            (int) $row['id'],
            $row['created_at'],
            $row['updated_at']
        ), $result);
    }

    public function findById(int $id): ?ProductEntity
    {
        $qb = $this->db->createQueryBuilder();
        $result = $qb
            ->select('*')
            ->from('products')
            ->where('id = :id')
            ->setParameter('id', $id)
            ->executeQuery()
            ->fetchAssociative();

        if ($result === false) {
            return null;
        }

        return new ProductEntity(
            $result['name'],
            $result['description'],
            (float) $result['price'],
            $result['category_id'],
            $result['price_range_id'] ? (int) $result['price_range_id'] : null,
            $result['image_url'],
            (bool) $result['is_active'],
            (int) $result['id'],
            $result['created_at'],
            $result['updated_at']
        );
    }

    public function create(RegisterProductDTO $dto): ProductEntity
    {
        $now = (new \DateTime())->format('Y-m-d H:i:s');
        
        $this->db->insert('products', [
            'name' => $dto->getName(),
            'description' => $dto->getDescription(),
            'price' => $dto->getPrice(),
            'category_id' => $dto->getCategoryId(),
            'price_range_id' => $dto->getPriceRangeId(),
            'image_url' => $dto->getImageUrl(),
            'is_active' => $dto->isActive() ? 1 : 0,
            'created_at' => $now,
            'updated_at' => $now
        ]);

        $id = (int) $this->db->lastInsertId();

        return new ProductEntity(
            $dto->getName(),
            $dto->getDescription(),
            $dto->getPrice(),
            $dto->getCategoryId(),
            $dto->getPriceRangeId(),
            $dto->getImageUrl(),
            $dto->isActive(),
            $id,
            $now,
            $now
        );
    }

    public function update(int $id, RegisterProductDTO $dto): ?ProductEntity
    {
        $now = (new \DateTime())->format('Y-m-d H:i:s');
        
        $affectedRows = $this->db->update('products', [
            'name' => $dto->getName(),
            'description' => $dto->getDescription(),
            'price' => $dto->getPrice(),
            'category_id' => $dto->getCategoryId(),
            'price_range_id' => $dto->getPriceRangeId(),
            'image_url' => $dto->getImageUrl(),
            'is_active' => $dto->isActive() ? 1 : 0,
            'updated_at' => $now
        ], ['id' => $id]);

        if ($affectedRows === 0) {
            return null;
        }

        return $this->findById($id);
    }

    public function delete(int $id): bool
    {
        $affectedRows = $this->db->delete('products', ['id' => $id]);
        return $affectedRows > 0;
    }

    public function findPromotions(int $limit, int $offset): array
    {
        $now = (new \DateTime())->format('Y-m-d H:i:s');

        $qb = $this->db->createQueryBuilder();
        return $qb
            ->select('pp.id', 'pp.product_id', 'pp.price', 'pp.name', 'pp.description', 'pp.start_day', 'pp.end_day', 'pp.start_datetime', 'pp.end_datetime', 'pp.rule_type', 'p.name AS product_name', 'p.price AS product_price', 'p.image_url AS product_image')
            ->from('product_prices', 'pp')
            ->innerJoin('pp', 'products', 'p', 'p.id = pp.product_id')
            ->where('pp.rule_type = :ruleType')
            ->andWhere('pp.end_datetime >= :now')
            ->setParameter('ruleType', 'PROMOTION')
            ->setParameter('now', $now)
            ->orderBy('CASE WHEN pp.start_datetime <= :nowOrder THEN 0 ELSE 1 END', 'ASC')
            ->addOrderBy('pp.start_datetime', 'ASC')
            ->setParameter('nowOrder', $now)
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->executeQuery()
            ->fetchAllAssociative();
    }

    public function countAll(): int
    {
        $qb = $this->db->createQueryBuilder();
        return (int) $qb
            ->select('COUNT(*)')
            ->from('products')
            ->executeQuery()
            ->fetchOne();
    }

    public function countPromotions(): int
    {
        $qb = $this->db->createQueryBuilder();
        return (int) $qb
            ->select('COUNT(*)')
            ->from('product_prices', 'pp')
            ->where('pp.rule_type = :ruleType')
            ->andWhere('pp.end_datetime >= :now')
            ->setParameter('ruleType', 'PROMOTION')
            ->setParameter('now', (new \DateTime())->format('Y-m-d H:i:s'))
            ->executeQuery()
            ->fetchOne();
    }
}

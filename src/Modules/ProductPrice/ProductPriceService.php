<?php

declare(strict_types=1);

namespace Modules\ProductPrice;

class ProductPriceService
{
    private ProductPriceRepository $repository;

    public function __construct()
    {
        $this->repository = new ProductPriceRepository();
    }

    public function syncForProduct(string $productId, array $pricesData): array
    {
        $this->repository->deleteByProductId($productId);

        $entities = [];
        foreach ($pricesData as $item) {
            $dto = new RegisterProductPriceDTO(
                (string) $item['product_id'],
                (float) $item['price'],
                $item['rule_type'],
                $item['description'] ?? null,
                isset($item['start_day']) ? (int) $item['start_day'] : null,
                isset($item['end_day']) ? (int) $item['end_day'] : null,
                $item['start_datetime'] ?? null,
                $item['end_datetime'] ?? null
            );
            $entities[] = $this->repository->create($dto);
        }

        return $entities;
    }

    public function getAll(?string $productId = null): array
    {
        return $this->repository->findAll($productId);
    }

    public function getById(string $id): ?ProductPriceEntity
    {
        return $this->repository->findById($id);
    }

    public function create(RegisterProductPriceDTO $dto): ProductPriceEntity
    {
        return $this->repository->create($dto);
    }

    public function update(string $id, RegisterProductPriceDTO $dto): ?ProductPriceEntity
    {
        return $this->repository->update($id, $dto);
    }

    public function delete(string $id): bool
    {
        return $this->repository->delete($id);
    }
}

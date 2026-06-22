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

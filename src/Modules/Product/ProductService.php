<?php

declare(strict_types=1);

namespace Modules\Product;

class ProductService
{
    private ProductRepository $repository;

    public function __construct()
    {
        $this->repository = new ProductRepository();
    }

    public function getAll(): array
    {
        return $this->repository->findAll();
    }

    public function getById(int $id): ?ProductEntity
    {
        return $this->repository->findById($id);
    }

    public function create(RegisterProductDTO $dto): ProductEntity
    {
        return $this->repository->create($dto);
    }

    public function update(int $id, RegisterProductDTO $dto): ?ProductEntity
    {
        return $this->repository->update($id, $dto);
    }

    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }
}

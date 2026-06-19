<?php

declare(strict_types=1);

namespace Modules\PriceRange;

class PriceRangeService
{
    private PriceRangeRepository $repository;

    public function __construct()
    {
        $this->repository = new PriceRangeRepository();
    }

    public function getAll(): array
    {
        return $this->repository->findAll();
    }

    public function getById(int $id): ?PriceRangeEntity
    {
        return $this->repository->findById($id);
    }

    public function create(RegistrarPriceRangeDTO $dto): PriceRangeEntity
    {
        return $this->repository->create($dto);
    }

    public function update(int $id, RegistrarPriceRangeDTO $dto): ?PriceRangeEntity
    {
        return $this->repository->update($id, $dto);
    }

    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }
}

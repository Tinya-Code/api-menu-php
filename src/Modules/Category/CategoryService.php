<?php

declare(strict_types=1);

namespace Modules\Category;

class CategoryService
{
    private CategoryRepository $repository;

    public function __construct()
    {
        $this->repository = new CategoryRepository();
    }

    public function getAll(): array
    {
        return $this->repository->findAll();
    }

    public function getById(string $id): ?CategoryEntity
    {
        return $this->repository->findById($id);
    }

    public function create(RegisterCategoryDTO $dto): CategoryEntity
    {
        return $this->repository->create($dto);
    }

    public function update(string $id, RegisterCategoryDTO $dto): ?CategoryEntity
    {
        return $this->repository->update($id, $dto);
    }

    public function delete(string $id): bool
    {
        return $this->repository->delete($id);
    }
}

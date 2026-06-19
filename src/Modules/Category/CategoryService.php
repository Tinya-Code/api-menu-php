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

    public function getById(int $id): ?CategoryEntity
    {
        return $this->repository->findById($id);
    }

    public function create(RegistrarCategoryDTO $dto): CategoryEntity
    {
        return $this->repository->create($dto);
    }

    public function update(int $id, RegistrarCategoryDTO $dto): ?CategoryEntity
    {
        return $this->repository->update($id, $dto);
    }

    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }
}

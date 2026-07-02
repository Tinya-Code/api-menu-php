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

    public function getAll(int $page, int $limit): array
    {
        $totalItems = $this->repository->countAll();
        $totalPages = (int) ceil($totalItems / $limit);
        $offset = ($page - 1) * $limit;

        $categories = $this->repository->findAll($limit, $offset);

        return [
            'data' => array_map(fn($c) => $c->toArray(), $categories),
            'meta' => [
                'current_page' => $page,
                'total_pages' => $totalPages,
                'total_items' => $totalItems,
                'has_next' => $page < $totalPages,
                'has_prev' => $page > 1
            ]
        ];
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

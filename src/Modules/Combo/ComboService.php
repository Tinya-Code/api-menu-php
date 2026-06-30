<?php

declare(strict_types=1);

namespace Modules\Combo;

class ComboService
{
    private ComboRepository $repository;

    public function __construct()
    {
        $this->repository = new ComboRepository();
    }

    public function getAll(): array
    {
        return $this->repository->findAll();
    }

    public function getById(int $id): ?ComboEntity
    {
        return $this->repository->findById($id);
    }

    public function create(RegisterComboDTO $dto): ComboEntity
    {
        return $this->repository->create($dto);
    }

    public function update(int $id, RegisterComboDTO $dto): ?ComboEntity
    {
        return $this->repository->update($id, $dto);
    }

    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }
}

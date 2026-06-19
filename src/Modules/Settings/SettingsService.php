<?php

declare(strict_types=1);

namespace Modules\Settings;

class SettingsService
{
    private SettingsRepository $repository;

    public function __construct()
    {
        $this->repository = new SettingsRepository();
    }

    public function getAll(): array
    {
        return $this->repository->findAll();
    }

    public function getById(int $id): ?SettingsEntity
    {
        return $this->repository->findById($id);
    }

    public function getByKey(string $key): ?SettingsEntity
    {
        return $this->repository->findByKey($key);
    }

    public function create(RegistrarSettingsDTO $dto): SettingsEntity
    {
        return $this->repository->create($dto);
    }

    public function update(int $id, RegistrarSettingsDTO $dto): ?SettingsEntity
    {
        return $this->repository->update($id, $dto);
    }

    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }
}

<?php

declare(strict_types=1);

namespace Modules\Event;

class EventService
{
    private EventRepository $repository;

    public function __construct()
    {
        $this->repository = new EventRepository();
    }

    public function getAll(int $page, int $perPage): array
    {
        return $this->repository->findAll($page, $perPage);
    }

    public function getById(int $id): ?EventEntity
    {
        return $this->repository->findById($id);
    }

    public function create(RegisterEventDTO $dto): EventEntity
    {
        return $this->repository->create($dto);
    }

    public function update(int $id, RegisterEventDTO $dto): ?EventEntity
    {
        return $this->repository->update($id, $dto);
    }

    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }
}

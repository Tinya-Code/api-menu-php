<?php

declare(strict_types=1);

namespace Modules\Gallery;

class GalleryService
{
    private GalleryRepository $repository;

    public function __construct()
    {
        $this->repository = new GalleryRepository();
    }

    public function getAll(): array
    {
        return $this->repository->findAll();
    }

    public function getById(int $id): ?GalleryEntity
    {
        return $this->repository->findById($id);
    }

    public function create(RegistrarGalleryDTO $dto): GalleryEntity
    {
        return $this->repository->create($dto);
    }

    public function update(int $id, RegistrarGalleryDTO $dto): ?GalleryEntity
    {
        return $this->repository->update($id, $dto);
    }

    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }
}

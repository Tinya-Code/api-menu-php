<?php

declare(strict_types=1);

namespace Modules\Gallery;

use Core\Database;
use Doctrine\DBAL\Connection;

class GalleryRepository
{
    private Connection $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function findAll(): array
    {
        $qb = $this->db->createQueryBuilder();
        $result = $qb
            ->select('*')
            ->from('gallery')
            ->executeQuery()
            ->fetchAllAssociative();

        return array_map(fn($row) => new GalleryEntity(
            $row['title'],
            $row['image_url'],
            $row['description'],
            (int) $row['id'],
            $row['created_at'],
            $row['updated_at']
        ), $result);
    }

    public function findById(int $id): ?GalleryEntity
    {
        $qb = $this->db->createQueryBuilder();
        $result = $qb
            ->select('*')
            ->from('gallery')
            ->where('id = :id')
            ->setParameter('id', $id)
            ->executeQuery()
            ->fetchAssociative();

        if ($result === false) {
            return null;
        }

        return new GalleryEntity(
            $result['title'],
            $result['image_url'],
            $result['description'],
            (int) $result['id'],
            $result['created_at'],
            $result['updated_at']
        );
    }

    public function create(RegistrarGalleryDTO $dto): GalleryEntity
    {
        $now = (new \DateTime())->format('Y-m-d H:i:s');
        
        $this->db->insert('gallery', [
            'title' => $dto->getTitle(),
            'image_url' => $dto->getImageUrl(),
            'description' => $dto->getDescription(),
            'created_at' => $now,
            'updated_at' => $now
        ]);

        $id = (int) $this->db->lastInsertId();

        return new GalleryEntity(
            $dto->getTitle(),
            $dto->getImageUrl(),
            $dto->getDescription(),
            $id,
            $now,
            $now
        );
    }

    public function update(int $id, RegistrarGalleryDTO $dto): ?GalleryEntity
    {
        $now = (new \DateTime())->format('Y-m-d H:i:s');
        
        $affectedRows = $this->db->update('gallery', [
            'title' => $dto->getTitle(),
            'image_url' => $dto->getImageUrl(),
            'description' => $dto->getDescription(),
            'updated_at' => $now
        ], ['id' => $id]);

        if ($affectedRows === 0) {
            return null;
        }

        return $this->findById($id);
    }

    public function delete(int $id): bool
    {
        $affectedRows = $this->db->delete('gallery', ['id' => $id]);
        return $affectedRows > 0;
    }
}



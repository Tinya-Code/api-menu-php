<?php

declare(strict_types=1);

namespace Modules\Event;

use Core\Database;
use Doctrine\DBAL\Connection;

class EventRepository
{
    private Connection $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function findAll(int $page, int $perPage): array
    {
        $offset = ($page - 1) * $perPage;

        $countResult = $this->db->createQueryBuilder()
            ->select('COUNT(*) AS total')
            ->from('events')
            ->executeQuery()
            ->fetchAssociative();

        $total = (int) ($countResult['total'] ?? 0);
        $lastPage = (int) ceil($total / $perPage);

        if ($lastPage < 1) {
            $lastPage = 1;
        }

        $result = $this->db->createQueryBuilder()
            ->select('*')
            ->from('events')
            ->orderBy('date', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($perPage)
            ->executeQuery()
            ->fetchAllAssociative();

        $data = array_map(fn($row) => new EventEntity(
            $row['name'],
            $row['date'],
            $row['description'] ?? null,
            $row['image_url'] ?? null,
            (int) $row['id'],
            $row['created_at'],
            $row['updated_at']
        ), $result);

        return [
            'data' => $data,
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'last_page' => $lastPage
        ];
    }

    public function findById(int $id): ?EventEntity
    {
        $qb = $this->db->createQueryBuilder();
        $result = $qb
            ->select('*')
            ->from('events')
            ->where('id = :id')
            ->setParameter('id', $id)
            ->executeQuery()
            ->fetchAssociative();

        if ($result === false) {
            return null;
        }

        return new EventEntity(
            $result['name'],
            $result['date'],
            $result['description'] ?? null,
            $result['image_url'] ?? null,
            (int) $result['id'],
            $result['created_at'],
            $result['updated_at']
        );
    }

    public function create(RegisterEventDTO $dto): EventEntity
    {
        $now = (new \DateTime())->format('Y-m-d H:i:s');

        $this->db->insert('events', [
            'name' => $dto->getName(),
            'description' => $dto->getDescription(),
            'date' => $dto->getDate(),
            'image_url' => $dto->getImageUrl(),
            'created_at' => $now,
            'updated_at' => $now
        ]);

        $id = (int) $this->db->lastInsertId();

        return new EventEntity(
            $dto->getName(),
            $dto->getDate(),
            $dto->getDescription(),
            $dto->getImageUrl(),
            $id,
            $now,
            $now
        );
    }

    public function update(int $id, RegisterEventDTO $dto): ?EventEntity
    {
        $now = (new \DateTime())->format('Y-m-d H:i:s');

        $affectedRows = $this->db->update('events', [
            'name' => $dto->getName(),
            'description' => $dto->getDescription(),
            'date' => $dto->getDate(),
            'image_url' => $dto->getImageUrl(),
            'updated_at' => $now
        ], ['id' => $id]);

        if ($affectedRows === 0) {
            return null;
        }

        return $this->findById($id);
    }

    public function delete(int $id): bool
    {
        $affectedRows = $this->db->delete('events', ['id' => $id]);
        return $affectedRows > 0;
    }
}

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

    public function getByProductId(int $productId): array
    {
        return $this->repository->findByProductId($productId);
    }

    public function create(RegisterPriceRangeDTO $dto): PriceRangeEntity
    {
        if ($dto->isDefault()) {
            $this->repository->clearDefaultForProduct($dto->getProductId());
        }

        return $this->repository->create($dto);
    }

    public function update(int $id, RegisterPriceRangeDTO $dto): ?PriceRangeEntity
    {
        $existing = $this->repository->findById($id);

        if ($existing === null) {
            return null;
        }

        if ($dto->isDefault()) {
            $this->repository->clearDefaultForProduct($dto->getProductId());
        }

        return $this->repository->update($id, $dto);
    }

    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }

    public function syncForProduct(int $productId, array $variants): array
    {
        $this->repository->beginTransaction();

        try {
            $existing = $this->repository->findByProductId($productId);
            $existingIds = array_map(fn($e) => $e->getId(), $existing);

            $incomingIds = [];
            foreach ($variants as $item) {
                if (isset($item['id'])) {
                    $incomingIds[] = (int) $item['id'];
                }
            }

            $toDelete = array_diff($existingIds, $incomingIds);
            if (!empty($toDelete)) {
                $this->repository->deleteMultiple(array_values($toDelete));
            }

            $hasDefault = false;
            $results = [];

            foreach ($variants as $item) {
                $isDefault = filter_var($item['is_default'] ?? false, FILTER_VALIDATE_BOOLEAN);

                if ($isDefault) {
                    $hasDefault = true;
                }

                $dto = new RegisterPriceRangeDTO(
                    productId: $productId,
                    quantity: (float) $item['quantity'],
                    price: (float) $item['price'],
                    unit: $item['unit'] ?? null,
                    bonus: $item['bonus'] ?? null,
                    sortOrder: (int) ($item['sort_order'] ?? 0),
                    isDefault: $isDefault
                );

                if (isset($item['id']) && in_array((int) $item['id'], $existingIds, true)) {
                    $results[] = $this->repository->update((int) $item['id'], $dto);
                } else {
                    $results[] = $this->repository->create($dto);
                }
            }

            if ($hasDefault) {
                $this->repository->clearDefaultForProduct($productId);
                foreach ($results as $range) {
                    if ($range !== null && $range->isDefault()) {
                        $this->repository->update($range->getId(), new RegisterPriceRangeDTO(
                            productId: $range->getProductId(),
                            quantity: $range->getQuantity(),
                            price: $range->getPrice(),
                            unit: $range->getUnit(),
                            bonus: $range->getBonus(),
                            sortOrder: $range->getSortOrder(),
                            isDefault: true
                        ));
                    }
                }
            }

            $this->repository->commit();

            return array_filter($results, fn($r) => $r !== null);
        } catch (\Throwable $e) {
            $this->repository->rollBack();
            throw $e;
        }
    }
}

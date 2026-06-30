<?php

declare(strict_types=1);

namespace Modules\PriceRange;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class PriceRangeController
{
    private PriceRangeService $service;

    public function __construct()
    {
        $this->service = new PriceRangeService();
    }

    public function index(): JsonResponse
    {
        $priceRanges = $this->service->getAll();
        $data = array_map(fn($range) => $range->toArray(), $priceRanges);
        return new JsonResponse(['data' => $data]);
    }

    public function show(int $id): JsonResponse
    {
        $range = $this->service->getById($id);

        if ($range === null) {
            return new JsonResponse(['error' => 'Price range not found'], 404);
        }

        return new JsonResponse(['data' => $range->toArray()]);
    }

    public function byProduct(int $productId): JsonResponse
    {
        $priceRanges = $this->service->getByProductId($productId);
        $data = array_map(fn($range) => $range->toArray(), $priceRanges);
        return new JsonResponse(['data' => $data]);
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            $dto = new RegisterPriceRangeDTO(
                productId: (int) $data['product_id'],
                quantity: (float) $data['quantity'],
                price: (float) $data['price'],
                unit: $data['unit'] ?? null,
                bonus: $data['bonus'] ?? null,
                sortOrder: (int) ($data['sort_order'] ?? 0),
                isDefault: filter_var($data['is_default'] ?? false, FILTER_VALIDATE_BOOLEAN)
            );

            $range = $this->service->create($dto);
            return new JsonResponse(['data' => $range->toArray()], 201);
        } catch (\Throwable $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        }
    }

    public function update(int $id, Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            $dto = new RegisterPriceRangeDTO(
                productId: (int) $data['product_id'],
                quantity: (float) $data['quantity'],
                price: (float) $data['price'],
                unit: $data['unit'] ?? null,
                bonus: $data['bonus'] ?? null,
                sortOrder: (int) ($data['sort_order'] ?? 0),
                isDefault: filter_var($data['is_default'] ?? false, FILTER_VALIDATE_BOOLEAN)
            );

            $range = $this->service->update($id, $dto);

            if ($range === null) {
                return new JsonResponse(['error' => 'Price range not found'], 404);
            }

            return new JsonResponse(['data' => $range->toArray()]);
        } catch (\Throwable $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        }
    }

    public function sync(int $productId, Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            $variants = $data['variants'] ?? [];

            $results = $this->service->syncForProduct($productId, $variants);
            $data = array_map(fn($range) => $range->toArray(), $results);

            return new JsonResponse(['data' => $data]);
        } catch (\Throwable $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->service->delete($id);

        if (!$deleted) {
            return new JsonResponse(['error' => 'Price range not found'], 404);
        }

        return new JsonResponse(['message' => 'Price range deleted successfully']);
    }
}

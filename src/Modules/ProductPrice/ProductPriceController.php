<?php

declare(strict_types=1);

namespace Modules\ProductPrice;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ProductPriceController
{
    private ProductPriceService $service;

    public function __construct()
    {
        $this->service = new ProductPriceService();
    }

    public function index(Request $request): JsonResponse
    {
        $productId = $request->query->get('product_id');
        $prices = $this->service->getAll($productId);
        $data = array_map(fn($price) => $price->toArray(), $prices);
        return new JsonResponse(['data' => $data]);
    }

    public function show(string $id): JsonResponse
    {
        $price = $this->service->getById($id);

        if ($price === null) {
            return new JsonResponse(['error' => 'ProductPrice not found'], 404);
        }

        return new JsonResponse(['data' => $price->toArray()]);
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            $dto = new RegisterProductPriceDTO(
                (string) $data['product_id'],
                (float) $data['price'],
                $data['rule_type'],
                $data['name'] ?? null,
                $data['description'] ?? null,
                isset($data['start_day']) ? (int) $data['start_day'] : null,
                isset($data['end_day']) ? (int) $data['end_day'] : null,
                $data['start_datetime'] ?? null,
                $data['end_datetime'] ?? null
            );

            $price = $this->service->create($dto);
            return new JsonResponse(['data' => $price->toArray()], 201);
        } catch (\Throwable $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        }
    }

    public function update(string $id, Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            $dto = new RegisterProductPriceDTO(
                (string) $data['product_id'],
                (float) $data['price'],
                $data['rule_type'],
                $data['name'] ?? null,
                $data['description'] ?? null,
                isset($data['start_day']) ? (int) $data['start_day'] : null,
                isset($data['end_day']) ? (int) $data['end_day'] : null,
                $data['start_datetime'] ?? null,
                $data['end_datetime'] ?? null
            );

            $price = $this->service->update($id, $dto);

            if ($price === null) {
                return new JsonResponse(['error' => 'ProductPrice not found'], 404);
            }

            return new JsonResponse(['data' => $price->toArray()]);
        } catch (\Throwable $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        }
    }

    public function destroy(string $id): JsonResponse
    {
        $deleted = $this->service->delete($id);

        if (!$deleted) {
            return new JsonResponse(['error' => 'ProductPrice not found'], 404);
        }

        return new JsonResponse(['message' => 'ProductPrice deleted successfully']);
    }
}

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

    public function store(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            $dto = new RegistrarPriceRangeDTO(
                $data['name'],
                (float) $data['min_price'],
                (float) $data['max_price']
            );
            
            $range = $this->service->create($dto);
            return new JsonResponse(['data' => $range->toArray()], 201);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        }
    }

    public function update(int $id, Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            $dto = new RegistrarPriceRangeDTO(
                $data['name'],
                (float) $data['min_price'],
                (float) $data['max_price']
            );
            
            $range = $this->service->update($id, $dto);
            
            if ($range === null) {
                return new JsonResponse(['error' => 'Price range not found'], 404);
            }

            return new JsonResponse(['data' => $range->toArray()]);
        } catch (\Exception $e) {
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

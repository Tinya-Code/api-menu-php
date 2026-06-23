<?php

declare(strict_types=1);

namespace Modules\Product;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ProductController
{
    private ProductService $service;

    public function __construct()
    {
        $this->service = new ProductService();
    }

    public function index(): JsonResponse
    {
        $products = $this->service->getAll();
        $data = array_map(fn($product) => $product->toArray(), $products);
        return new JsonResponse(['data' => $data]);
    }

    public function show(int $id): JsonResponse
    {
        $product = $this->service->getById($id);
        
        if ($product === null) {
            return new JsonResponse(['error' => 'Product not found'], 404);
        }

        return new JsonResponse(['data' => $product->toArray()]);
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            $dto = new RegisterProductDTO(
                $data['name'],
                $data['description'],
                (float) $data['price'],
                $data['category_id'] ?? null,
                $data['price_range_id'] ?? null,
                $data['image_url'] ?? null,
                $data['is_active'] ?? true
            );
            
            $product = $this->service->create($dto);
            return new JsonResponse(['data' => $product->toArray()], 201);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        }
    }

    public function update(int $id, Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            $dto = new RegisterProductDTO(
                $data['name'],
                $data['description'],
                (float) $data['price'],
                $data['category_id'] ?? null,
                $data['price_range_id'] ?? null,
                $data['image_url'] ?? null,
                $data['is_active'] ?? true
            );
            
            $product = $this->service->update($id, $dto);
            
            if ($product === null) {
                return new JsonResponse(['error' => 'Product not found'], 404);
            }

            return new JsonResponse(['data' => $product->toArray()]);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->service->delete($id);
        
        if (!$deleted) {
            return new JsonResponse(['error' => 'Product not found'], 404);
        }

        return new JsonResponse(['message' => 'Product deleted successfully']);
    }
}

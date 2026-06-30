<?php

declare(strict_types=1);

namespace Modules\Category;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class CategoryController
{
    private CategoryService $service;

    public function __construct()
    {
        $this->service = new CategoryService();
    }

    public function index(): JsonResponse
    {
        $categories = $this->service->getAll();
        $data = array_map(fn($category) => $category->toArray(), $categories);
        return new JsonResponse(['data' => $data]);
    }

    public function show(string $id): JsonResponse
    {
        $category = $this->service->getById($id);

        if ($category === null) {
            return new JsonResponse(['error' => 'Category not found'], 404);
        }

        return new JsonResponse(['data' => $category->toArray()]);
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            $dto = new RegisterCategoryDTO(
                $data['name'],
                $data['description'],
                $data['block_id']
            );

            $category = $this->service->create($dto);
            return new JsonResponse(['data' => $category->toArray()], 201);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        }
    }

    public function update(string $id, Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            $dto = new RegisterCategoryDTO(
                $data['name'],
                $data['description'],
                $data['block_id']
            );

            $category = $this->service->update($id, $dto);

            if ($category === null) {
                return new JsonResponse(['error' => 'Category not found'], 404);
            }

            return new JsonResponse(['data' => $category->toArray()]);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        }
    }

    public function destroy(string $id): JsonResponse
    {
        $deleted = $this->service->delete($id);

        if (!$deleted) {
            return new JsonResponse(['error' => 'Category not found'], 404);
        }

        return new JsonResponse(['message' => 'Category deleted successfully']);
    }
}

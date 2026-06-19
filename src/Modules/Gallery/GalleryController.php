<?php

declare(strict_types=1);

namespace Modules\Gallery;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class GalleryController
{
    private GalleryService $service;

    public function __construct()
    {
        $this->service = new GalleryService();
    }

    public function index(): JsonResponse
    {
        $galleryItems = $this->service->getAll();
        $data = array_map(fn($item) => $item->toArray(), $galleryItems);
        return new JsonResponse(['data' => $data]);
    }

    public function show(int $id): JsonResponse
    {
        $item = $this->service->getById($id);
        
        if ($item === null) {
            return new JsonResponse(['error' => 'Gallery item not found'], 404);
        }

        return new JsonResponse(['data' => $item->toArray()]);
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            $dto = new RegistrarGalleryDTO(
                $data['title'],
                $data['image_url'],
                $data['description'] ?? null
            );
            
            $item = $this->service->create($dto);
            return new JsonResponse(['data' => $item->toArray()], 201);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        }
    }

    public function update(int $id, Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            $dto = new RegistrarGalleryDTO(
                $data['title'],
                $data['image_url'],
                $data['description'] ?? null
            );
            
            $item = $this->service->update($id, $dto);
            
            if ($item === null) {
                return new JsonResponse(['error' => 'Gallery item not found'], 404);
            }

            return new JsonResponse(['data' => $item->toArray()]);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->service->delete($id);
        
        if (!$deleted) {
            return new JsonResponse(['error' => 'Gallery item not found'], 404);
        }

        return new JsonResponse(['message' => 'Gallery item deleted successfully']);
    }
}

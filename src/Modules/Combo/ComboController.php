<?php

declare(strict_types=1);

namespace Modules\Combo;

use Services\CloudinaryService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ComboController
{
    private ComboService $service;
    private CloudinaryService $cloudinary;

    public function __construct()
    {
        $this->service = new ComboService();
        $this->cloudinary = new CloudinaryService();
    }

    public function index(): JsonResponse
    {
        $combos = $this->service->getAll();
        $data = array_map(fn($combo) => $combo->toArray(), $combos);
        return new JsonResponse(['data' => $data]);
    }

    public function show(int $id): JsonResponse
    {
        $combo = $this->service->getById($id);

        if ($combo === null) {
            return new JsonResponse(['error' => 'Combo not found'], 404);
        }

        return new JsonResponse(['data' => $combo->toArray()]);
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $data = $this->getRequestData($request);

            $imageUrl = null;
            $file = $request->files->get('image');

            if ($file !== null) {
                $uploaded = $this->cloudinary->upload([
                    'tmp_name' => $file->getPathname(),
                    'name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                    'error' => $file->getError(),
                    'type' => $file->getClientMimeType(),
                ]);
                $imageUrl = $uploaded['url'];
            } else {
                $imageUrl = $data['image_url'] ?? null;
            }

            $dto = new RegisterComboDTO(
                name: $data['name'],
                price: (float) $data['price'],
                description: $data['description'] ?? null,
                imageUrl: $imageUrl
            );

            $combo = $this->service->create($dto);
            return new JsonResponse(['data' => $combo->toArray()], 201);
        } catch (\Throwable $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        }
    }

    public function update(int $id, Request $request): JsonResponse
    {
        try {
            $data = $this->getRequestData($request);

            $imageUrl = $data['image_url'] ?? null;
            $file = $request->files->get('image');

            if ($file !== null) {
                $current = $this->service->getById($id);
                $oldPublicId = $current !== null && $current->getImageUrl() !== null
                    ? $this->cloudinary->extractPublicId($current->getImageUrl())
                    : null;

                $uploaded = $this->cloudinary->replace([
                    'tmp_name' => $file->getPathname(),
                    'name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                    'error' => $file->getError(),
                    'type' => $file->getClientMimeType(),
                ], $oldPublicId);
                $imageUrl = $uploaded['url'];
            }

            $dto = new RegisterComboDTO(
                name: $data['name'],
                price: (float) $data['price'],
                description: $data['description'] ?? null,
                imageUrl: $imageUrl
            );

            $combo = $this->service->update($id, $dto);

            if ($combo === null) {
                return new JsonResponse(['error' => 'Combo not found'], 404);
            }

            return new JsonResponse(['data' => $combo->toArray()]);
        } catch (\Throwable $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        $combo = $this->service->getById($id);

        if ($combo === null) {
            return new JsonResponse(['error' => 'Combo not found'], 404);
        }

        if ($combo->getImageUrl() !== null) {
            $publicId = $this->cloudinary->extractPublicId($combo->getImageUrl());
            if ($publicId !== null) {
                $this->cloudinary->destroy($publicId);
            }
        }

        $deleted = $this->service->delete($id);

        if (!$deleted) {
            return new JsonResponse(['error' => 'Combo not found'], 404);
        }

        return new JsonResponse(['message' => 'Combo deleted successfully']);
    }

    private function getRequestData(Request $request): array
    {
        if ($request->getContentTypeFormat() === 'json') {
            $data = json_decode($request->getContent(), true) ?? [];
        } else {
            $data = $request->request->all();
        }

        return $data;
    }
}

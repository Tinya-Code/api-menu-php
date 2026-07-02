<?php

declare(strict_types=1);

namespace Modules\Event;

use Services\CloudinaryService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class EventController
{
    private EventService $service;
    private CloudinaryService $cloudinary;

    public function __construct()
    {
        $this->service = new EventService();
        $this->cloudinary = new CloudinaryService();
    }

    public function index(Request $request): JsonResponse
    {
        $page = max(1, (int) ($request->query->get('page', 1)));
        $perPage = max(1, min(100, (int) ($request->query->get('per_page', 10))));

        $result = $this->service->getAll($page, $perPage);

        $data = array_map(fn($event) => $event->toArray(), $result['data']);

        return new JsonResponse([
            'data' => $data,
            'total' => $result['total'],
            'page' => $result['page'],
            'per_page' => $result['per_page'],
            'last_page' => $result['last_page']
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $event = $this->service->getById($id);

        if ($event === null) {
            return new JsonResponse(['error' => 'Event not found'], 404);
        }

        return new JsonResponse(['data' => $event->toArray()]);
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

            $dto = new RegisterEventDTO(
                $data['name'],
                $data['date'],
                $data['description'] ?? null,
                $imageUrl
            );

            $event = $this->service->create($dto);
            return new JsonResponse(['data' => $event->toArray()], 201);
        } catch (\Throwable $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        }
    }

    public function update(int $id, Request $request): JsonResponse
    {
        try {
            $data = $this->getRequestData($request);

            $file = $request->files->get('image');
            $imageUrl = $data['image_url'] ?? null;

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

            $dto = new RegisterEventDTO(
                $data['name'],
                $data['date'],
                $data['description'] ?? null,
                $imageUrl
            );

            $event = $this->service->update($id, $dto);

            if ($event === null) {
                return new JsonResponse(['error' => 'Event not found'], 404);
            }

            return new JsonResponse(['data' => $event->toArray()]);
        } catch (\Throwable $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        $event = $this->service->getById($id);

        if ($event === null) {
            return new JsonResponse(['error' => 'Event not found'], 404);
        }

        if ($event->getImageUrl() !== null) {
            $publicId = $this->cloudinary->extractPublicId($event->getImageUrl());

            if ($publicId !== null) {
                $this->cloudinary->destroy($publicId);
            }
        }

        $deleted = $this->service->delete($id);

        if (!$deleted) {
            return new JsonResponse(['error' => 'Event not found'], 404);
        }

        return new JsonResponse(['message' => 'Event deleted successfully']);
    }

    private function getRequestData(Request $request): array
    {
        $content = $request->getContent();

        if (str_starts_with($content, "\xEF\xBB\xBF")) {
            $content = substr($content, 3);
        }

        $data = json_decode($content, true);

        if (!is_array($data) || $data === []) {
            $data = $request->request->all();
        }

        if (empty($data)) {
            $raw = file_get_contents('php://input');
            if (str_starts_with($raw, "\xEF\xBB\xBF")) {
                $raw = substr($raw, 3);
            }
            $data = json_decode($raw, true) ?? [];
        }

        $data = $this->normalizeFieldNames($data);

        return $data;
    }

    private function normalizeFieldNames(array $data): array
    {
        $fieldMap = [
            'imageUrl' => 'image_url',
            'imageURL' => 'image_url',
        ];

        foreach ($fieldMap as $frontend => $backend) {
            if (array_key_exists($frontend, $data) && !array_key_exists($backend, $data)) {
                $data[$backend] = $data[$frontend];
            }
        }

        return $data;
    }
}

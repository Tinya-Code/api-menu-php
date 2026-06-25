<?php

declare(strict_types=1);

namespace Modules\Settings;

use Services\CloudinaryService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class SettingsController
{
    private SettingsService $service;
    private CloudinaryService $cloudinary;

    public function __construct()
    {
        $this->service = new SettingsService();
        $this->cloudinary = new CloudinaryService();
    }

    public function index(): JsonResponse
    {
        $settings = $this->service->get();

        if ($settings === null) {
            $default = new SettingsEntity([], []);
            return new JsonResponse($default->toArray());
        }

        return new JsonResponse($settings->toArray());
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $data = $this->getRequestData($request);

            $file = $request->files->get('logo');

            if ($file !== null) {
                $uploaded = $this->cloudinary->upload([
                    'tmp_name' => $file->getPathname(),
                    'name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                    'error' => $file->getError(),
                    'type' => $file->getClientMimeType(),
                ], 'settings');

                $data['settings']['logo_url'] = $uploaded['url'];
            }

            $dto = new RegisterSettingsDTO($data);
            $settings = $this->service->save($dto);

            return new JsonResponse($settings->toArray());
        } catch (\Throwable $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        }
    }

    public function show(int $id): JsonResponse
    {
        return new JsonResponse(['error' => 'Not supported. Use GET /settings'], 404);
    }

    public function update(Request $request): JsonResponse
    {
        return new JsonResponse(['error' => 'Not supported. Use PUT /settings'], 404);
    }

    public function destroy(int $id): JsonResponse
    {
        return new JsonResponse(['error' => 'Not supported'], 404);
    }

    private function getRequestData(Request $request): array
    {
        $content = $request->getContent();

        if (!empty($content)) {
            return json_decode($content, true) ?? [];
        }

        return $request->request->all();
    }
}

<?php

declare(strict_types=1);

namespace Modules\Settings;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class SettingsController
{
    private SettingsService $service;

    public function __construct()
    {
        $this->service = new SettingsService();
    }

    public function index(): JsonResponse
    {
        $settings = $this->service->getAll();
        $data = array_map(fn($setting) => $setting->toArray(), $settings);
        return new JsonResponse(['data' => $data]);
    }

    public function show(int $id): JsonResponse
    {
        $setting = $this->service->getById($id);
        
        if ($setting === null) {
            return new JsonResponse(['error' => 'Setting not found'], 404);
        }

        return new JsonResponse(['data' => $setting->toArray()]);
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            $dto = new RegistrarSettingsDTO(
                $data['key'],
                $data['value'],
                $data['description'] ?? null
            );
            
            $setting = $this->service->create($dto);
            return new JsonResponse(['data' => $setting->toArray()], 201);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        }
    }

    public function update(int $id, Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            $dto = new RegistrarSettingsDTO(
                $data['key'],
                $data['value'],
                $data['description'] ?? null
            );
            
            $setting = $this->service->update($id, $dto);
            
            if ($setting === null) {
                return new JsonResponse(['error' => 'Setting not found'], 404);
            }

            return new JsonResponse(['data' => $setting->toArray()]);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->service->delete($id);
        
        if (!$deleted) {
            return new JsonResponse(['error' => 'Setting not found'], 404);
        }

        return new JsonResponse(['message' => 'Setting deleted successfully']);
    }
}

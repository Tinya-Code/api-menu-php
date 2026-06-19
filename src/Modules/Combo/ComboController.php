<?php

declare(strict_types=1);

namespace Modules\Combo;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ComboController
{
    private ComboService $service;

    public function __construct()
    {
        $this->service = new ComboService();
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
            $data = json_decode($request->getContent(), true);
            $dto = new RegistrarComboDTO(
                $data['name'],
                $data['description'],
                (float) $data['price']
            );
            
            $combo = $this->service->create($dto);
            return new JsonResponse(['data' => $combo->toArray()], 201);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        }
    }

    public function update(int $id, Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            $dto = new RegistrarComboDTO(
                $data['name'],
                $data['description'],
                (float) $data['price']
            );
            
            $combo = $this->service->update($id, $dto);
            
            if ($combo === null) {
                return new JsonResponse(['error' => 'Combo not found'], 404);
            }

            return new JsonResponse(['data' => $combo->toArray()]);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->service->delete($id);
        
        if (!$deleted) {
            return new JsonResponse(['error' => 'Combo not found'], 404);
        }

        return new JsonResponse(['message' => 'Combo deleted successfully']);
    }
}

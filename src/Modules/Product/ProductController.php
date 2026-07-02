<?php

declare(strict_types=1);

namespace Modules\Product;

use Services\CloudinaryService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ProductController
{
    private ProductService $service;
    private CloudinaryService $cloudinary;

    public function __construct()
    {
        $this->service = new ProductService();
        $this->cloudinary = new CloudinaryService();
    }

    public function index(Request $request): JsonResponse
    {
        $page = max(1, (int) $request->query->get('page', '1'));
        $limit = max(1, min(100, (int) $request->query->get('limit', '10')));

        $result = $this->service->getAll($page, $limit);
        return new JsonResponse($result);
    }

    public function promotions(Request $request): JsonResponse
    {
        $page = max(1, (int) $request->query->get('page', '1'));
        $limit = max(1, min(100, (int) $request->query->get('limit', '10')));

        $result = $this->service->getPromotions($page, $limit);
        return new JsonResponse($result);
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

            $dto = new RegisterProductDTO(
                $data['name'],
                $data['description'],
                (float) $data['price'],
                $data['category_id'] ?? null,
                isset($data['price_range_id']) ? (int) $data['price_range_id'] : null,
                $imageUrl,
                filter_var($data['is_active'] ?? true, FILTER_VALIDATE_BOOLEAN)
            );

            $prices = $this->parsePrices($data);
            $priceRanges = $this->parsePriceRanges($data);
            $product = $this->service->create($dto, $prices, $priceRanges);
            return new JsonResponse(['data' => $product->toArray()], 201);
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

            $dto = new RegisterProductDTO(
                $data['name'],
                $data['description'],
                (float) $data['price'],
                $data['category_id'] ?? null,
                isset($data['price_range_id']) ? (int) $data['price_range_id'] : null,
                $imageUrl,
                filter_var($data['is_active'] ?? true, FILTER_VALIDATE_BOOLEAN)
            );

            $prices = $this->parsePrices($data);
            $priceRanges = $this->parsePriceRanges($data);
            $product = $this->service->update($id, $dto, $prices, $priceRanges);

            if ($product === null) {
                return new JsonResponse(['error' => 'Product not found'], 404);
            }

            return new JsonResponse(['data' => $product->toArray()]);
        } catch (\Throwable $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        $product = $this->service->getById($id);

        if ($product === null) {
            return new JsonResponse(['error' => 'Product not found'], 404);
        }

        if ($product->getImageUrl() !== null) {
            $publicId = $this->cloudinary->extractPublicId($product->getImageUrl());

            if ($publicId !== null) {
                $this->cloudinary->destroy($publicId);
            }
        }

        $deleted = $this->service->delete($id);

        if (!$deleted) {
            return new JsonResponse(['error' => 'Product not found'], 404);
        }

        return new JsonResponse(['message' => 'Product deleted successfully']);
    }

    private function getRequestData(Request $request): array
    {
        if ($request->getContentTypeFormat() === 'json') {
            $data = json_decode($request->getContent(), true) ?? [];
        } else {
            $data = $request->request->all();
        }

        $data = $this->normalizeFieldNames($data);

        return $data;
    }

    private function parsePrices(array $data): ?array
    {
        $raw = $data['prices'] ?? null;

        if ($raw === null) {
            return null;
        }

        if (is_string($raw)) {
            $decoded = json_decode($raw, true);
            if (!is_array($decoded)) {
                throw new \InvalidArgumentException('Invalid JSON in prices field');
            }
            return $decoded;
        }

        if (is_array($raw)) {
            return $raw;
        }

        return null;
    }

    private function parsePriceRanges(array $data): ?array
    {
        $raw = $data['price_ranges'] ?? null;

        if ($raw === null) {
            return null;
        }

        if (is_string($raw)) {
            $decoded = json_decode($raw, true);
            if (!is_array($decoded)) {
                throw new \InvalidArgumentException('Invalid JSON in price_ranges field');
            }
            return $decoded;
        }

        if (is_array($raw)) {
            return $raw;
        }

        return null;
    }

    private function normalizeFieldNames(array $data): array
    {
        $fieldMap = [
            'categoryId' => 'category_id',
            'basePrice' => 'price',
            'status' => 'is_active',
            'prices' => 'prices',
            'priceRanges' => 'price_ranges',
        ];

        foreach ($fieldMap as $frontend => $backend) {
            if (array_key_exists($frontend, $data) && !array_key_exists($backend, $data)) {
                $data[$backend] = $data[$frontend];
            }
        }

        return $data;
    }
}

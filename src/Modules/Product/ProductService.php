<?php

declare(strict_types=1);

namespace Modules\Product;

use Modules\ProductPrice\ProductPriceService;
use Modules\PriceRange\PriceRangeService;

class ProductService
{
    private ProductRepository $repository;
    private ProductPriceService $priceService;
    private PriceRangeService $priceRangeService;

    public function __construct()
    {
        $this->repository = new ProductRepository();
        $this->priceService = new ProductPriceService();
        $this->priceRangeService = new PriceRangeService();
    }

    public function getAll(int $page, int $limit): array
    {
        $totalItems = $this->repository->countAll();
        $totalPages = (int) ceil($totalItems / $limit);
        $offset = ($page - 1) * $limit;

        $products = $this->repository->findAll($limit, $offset);
        foreach ($products as $product) {
            $product->setPrices(
                array_map(
                    fn($p) => $p->toArray(),
                    $this->priceService->getAll((string) $product->getId())
                )
            );
            $product->setPriceRanges(
                $this->priceRangeService->getByProductId($product->getId())
            );
        }

        return [
            'data' => array_map(fn($p) => $p->toArray(), $products),
            'meta' => [
                'current_page' => $page,
                'total_pages' => $totalPages,
                'total_items' => $totalItems,
                'has_next' => $page < $totalPages,
                'has_prev' => $page > 1
            ]
        ];
    }

    public function getById(int $id): ?ProductEntity
    {
        $product = $this->repository->findById($id);
        if ($product !== null) {
            $product->setPrices(
                array_map(
                    fn($p) => $p->toArray(),
                    $this->priceService->getAll((string) $id)
                )
            );
            $product->setPriceRanges(
                $this->priceRangeService->getByProductId($id)
            );
        }
        return $product;
    }

    public function create(RegisterProductDTO $dto, ?array $pricesData = null, ?array $priceRangesData = null): ProductEntity
    {
        $product = $this->repository->create($dto);

        if ($pricesData !== null) {
            $this->priceService->syncForProduct((string) $product->getId(), $pricesData);
        }

        if ($priceRangesData !== null) {
            $this->priceRangeService->syncForProduct($product->getId(), $priceRangesData);
        }

        return $this->getById($product->getId());
    }

    public function update(int $id, RegisterProductDTO $dto, ?array $pricesData = null, ?array $priceRangesData = null): ?ProductEntity
    {
        $product = $this->repository->update($id, $dto);

        if ($product === null) {
            return null;
        }

        if ($pricesData !== null) {
            $this->priceService->syncForProduct((string) $id, $pricesData);
        }

        if ($priceRangesData !== null) {
            $this->priceRangeService->syncForProduct($id, $priceRangesData);
        }

        return $this->getById($id);
    }

    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }

    public function getPromotions(int $page, int $limit): array
    {
        $totalItems = $this->repository->countPromotions();
        $totalPages = (int) ceil($totalItems / $limit);
        $offset = ($page - 1) * $limit;

        $promotions = $this->repository->findPromotions($limit, $offset);

        return [
            'data' => $promotions,
            'meta' => [
                'current_page' => $page,
                'total_pages' => $totalPages,
                'total_items' => $totalItems,
                'has_next' => $page < $totalPages,
                'has_prev' => $page > 1
            ]
        ];
    }
}

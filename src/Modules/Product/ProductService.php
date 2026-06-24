<?php

declare(strict_types=1);

namespace Modules\Product;

use Modules\ProductPrice\ProductPriceService;

class ProductService
{
    private ProductRepository $repository;
    private ProductPriceService $priceService;

    public function __construct()
    {
        $this->repository = new ProductRepository();
        $this->priceService = new ProductPriceService();
    }

    public function getAll(): array
    {
        $products = $this->repository->findAll();
        foreach ($products as $product) {
            $product->setPrices(
                array_map(
                    fn($p) => $p->toArray(),
                    $this->priceService->getAll((string) $product->getId())
                )
            );
        }
        return $products;
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
        }
        return $product;
    }

    public function create(RegisterProductDTO $dto, ?array $pricesData = null): ProductEntity
    {
        $product = $this->repository->create($dto);

        if ($pricesData !== null) {
            $this->priceService->syncForProduct((string) $product->getId(), $pricesData);
        }

        return $this->getById($product->getId());
    }

    public function update(int $id, RegisterProductDTO $dto, ?array $pricesData = null): ?ProductEntity
    {
        $product = $this->repository->update($id, $dto);

        if ($product === null) {
            return null;
        }

        if ($pricesData !== null) {
            $this->priceService->syncForProduct((string) $id, $pricesData);
        }

        return $this->getById($id);
    }

    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }
}

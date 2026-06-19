<?php

declare(strict_types=1);

namespace Modules\Product;

use Respect\Validation\Validator as v;

class RegistrarProductDTO
{
    private string $name;
    private string $description;
    private float $price;
    private ?int $categoryId;
    private ?int $priceRangeId;
    private ?string $imageUrl;
    private bool $isActive;

    public function __construct(
        string $name,
        string $description,
        float $price,
        ?int $categoryId = null,
        ?int $priceRangeId = null,
        ?string $imageUrl = null,
        bool $isActive = true
    ) {
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
        $this->categoryId = $categoryId;
        $this->priceRangeId = $priceRangeId;
        $this->imageUrl = $imageUrl;
        $this->isActive = $isActive;
        $this->validate();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getCategoryId(): ?int
    {
        return $this->categoryId;
    }

    public function getPriceRangeId(): ?int
    {
        return $this->priceRangeId;
    }

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    private function validate(): void
    {
        v::stringType()->length(1, 255)->assert($this->name);
        v::stringType()->length(0, 1000)->assert($this->description);
        v::floatType()->min(0)->assert($this->price);
        if ($this->categoryId !== null) {
            v::intType()->min(1)->assert($this->categoryId);
        }
        if ($this->priceRangeId !== null) {
            v::intType()->min(1)->assert($this->priceRangeId);
        }
        if ($this->imageUrl !== null) {
            v::url()->assert($this->imageUrl);
        }
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'category_id' => $this->categoryId,
            'price_range_id' => $this->priceRangeId,
            'image_url' => $this->imageUrl,
            'is_active' => $this->isActive
        ];
    }
}

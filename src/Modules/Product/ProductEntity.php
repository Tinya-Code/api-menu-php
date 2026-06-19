<?php

declare(strict_types=1);

namespace Modules\Product;

class ProductEntity
{
    private ?int $id;
    private string $name;
    private string $description;
    private float $price;
    private ?int $categoryId;
    private ?int $priceRangeId;
    private ?string $imageUrl;
    private bool $isActive;
    private ?string $createdAt;
    private ?string $updatedAt;

    public function __construct(
        string $name,
        string $description,
        float $price,
        ?int $categoryId = null,
        ?int $priceRangeId = null,
        ?string $imageUrl = null,
        bool $isActive = true,
        ?int $id = null,
        ?string $createdAt = null,
        ?string $updatedAt = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
        $this->categoryId = $categoryId;
        $this->priceRangeId = $priceRangeId;
        $this->imageUrl = $imageUrl;
        $this->isActive = $isActive;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    public function getCategoryId(): ?int
    {
        return $this->categoryId;
    }

    public function setCategoryId(?int $categoryId): void
    {
        $this->categoryId = $categoryId;
    }

    public function getPriceRangeId(): ?int
    {
        return $this->priceRangeId;
    }

    public function setPriceRangeId(?int $priceRangeId): void
    {
        $this->priceRangeId = $priceRangeId;
    }

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(?string $imageUrl): void
    {
        $this->imageUrl = $imageUrl;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setActive(bool $isActive): void
    {
        $this->isActive = $isActive;
    }

    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?string
    {
        return $this->updatedAt;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'category_id' => $this->categoryId,
            'price_range_id' => $this->priceRangeId,
            'image_url' => $this->imageUrl,
            'is_active' => $this->isActive,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt
        ];
    }
}

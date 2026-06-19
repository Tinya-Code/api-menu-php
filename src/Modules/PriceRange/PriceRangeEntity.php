<?php

declare(strict_types=1);

namespace Modules\PriceRange;

class PriceRangeEntity
{
    private ?int $id;
    private string $name;
    private float $minPrice;
    private float $maxPrice;
    private ?string $createdAt;
    private ?string $updatedAt;

    public function __construct(
        string $name,
        float $minPrice,
        float $maxPrice,
        ?int $id = null,
        ?string $createdAt = null,
        ?string $updatedAt = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->minPrice = $minPrice;
        $this->maxPrice = $maxPrice;
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

    public function getMinPrice(): float
    {
        return $this->minPrice;
    }

    public function setMinPrice(float $minPrice): void
    {
        $this->minPrice = $minPrice;
    }

    public function getMaxPrice(): float
    {
        return $this->maxPrice;
    }

    public function setMaxPrice(float $maxPrice): void
    {
        $this->maxPrice = $maxPrice;
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
            'min_price' => $this->minPrice,
            'max_price' => $this->maxPrice,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt
        ];
    }
}

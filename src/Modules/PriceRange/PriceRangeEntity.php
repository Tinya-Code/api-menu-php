<?php

declare(strict_types=1);

namespace Modules\PriceRange;

class PriceRangeEntity
{
    private ?int $id;
    private int $productId;
    private float $quantity;
    private ?string $unit;
    private float $price;
    private ?string $bonus;
    private bool $isDefault;
    private ?string $createdAt;
    private ?string $updatedAt;

    public function __construct(
        int $productId,
        float $quantity,
        float $price,
        ?string $unit = null,
        ?string $bonus = null,
        bool $isDefault = false,
        ?int $id = null,
        ?string $createdAt = null,
        ?string $updatedAt = null
    ) {
        $this->id = $id;
        $this->productId = $productId;
        $this->quantity = $quantity;
        $this->unit = $unit;
        $this->price = $price;
        $this->bonus = $bonus;
        $this->isDefault = $isDefault;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function setProductId(int $productId): void
    {
        $this->productId = $productId;
    }

    public function getQuantity(): float
    {
        return $this->quantity;
    }

    public function setQuantity(float $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function getUnit(): ?string
    {
        return $this->unit;
    }

    public function setUnit(?string $unit): void
    {
        $this->unit = $unit;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    public function getBonus(): ?string
    {
        return $this->bonus;
    }

    public function setBonus(?string $bonus): void
    {
        $this->bonus = $bonus;
    }

    public function isDefault(): bool
    {
        return $this->isDefault;
    }

    public function setIsDefault(bool $isDefault): void
    {
        $this->isDefault = $isDefault;
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
            'product_id' => $this->productId,
            'quantity' => $this->quantity,
            'unit' => $this->unit,
            'price' => $this->price,
            'bonus' => $this->bonus,
            'is_default' => $this->isDefault,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt
        ];
    }
}

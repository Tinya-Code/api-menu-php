<?php

declare(strict_types=1);

namespace Modules\PriceRange;

use Respect\Validation\ValidatorBuilder as v;

class RegisterPriceRangeDTO
{
    private int $productId;
    private float $quantity;
    private ?string $unit;
    private float $price;
    private ?string $bonus;
    private int $sortOrder;
    private bool $isDefault;

    public function __construct(
        int $productId,
        float $quantity,
        float $price,
        ?string $unit = null,
        ?string $bonus = null,
        int $sortOrder = 0,
        bool $isDefault = false
    ) {
        $this->productId = $productId;
        $this->quantity = $quantity;
        $this->unit = $unit;
        $this->price = $price;
        $this->bonus = $bonus;
        $this->sortOrder = $sortOrder;
        $this->isDefault = $isDefault;
        $this->validate();
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function getQuantity(): float
    {
        return $this->quantity;
    }

    public function getUnit(): ?string
    {
        return $this->unit;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getBonus(): ?string
    {
        return $this->bonus;
    }

    public function getSortOrder(): int
    {
        return $this->sortOrder;
    }

    public function isDefault(): bool
    {
        return $this->isDefault;
    }

    private function validate(): void
    {
        v::intType()->greaterThan(0)->assert($this->productId);
        v::floatType()->greaterThan(0)->assert($this->quantity);
        v::floatType()->greaterThanOrEqual(0)->assert($this->price);
        if ($this->unit !== null) {
            v::stringType()->lengthBetween(1, 50)->assert($this->unit);
        }
        if ($this->bonus !== null) {
            v::stringType()->lengthBetween(1, 255)->assert($this->bonus);
        }
        v::intType()->greaterThanOrEqual(0)->assert($this->sortOrder);
    }

    public function toArray(): array
    {
        return [
            'product_id' => $this->productId,
            'quantity' => $this->quantity,
            'unit' => $this->unit,
            'price' => $this->price,
            'bonus' => $this->bonus,
            'sort_order' => $this->sortOrder,
            'is_default' => $this->isDefault
        ];
    }
}

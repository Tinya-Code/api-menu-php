<?php

declare(strict_types=1);

namespace Modules\PriceRange;

use Respect\Validation\Validator as v;

class RegistrarPriceRangeDTO
{
    private string $name;
    private float $minPrice;
    private float $maxPrice;

    public function __construct(string $name, float $minPrice, float $maxPrice)
    {
        $this->name = $name;
        $this->minPrice = $minPrice;
        $this->maxPrice = $maxPrice;
        $this->validate();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getMinPrice(): float
    {
        return $this->minPrice;
    }

    public function getMaxPrice(): float
    {
        return $this->maxPrice;
    }

    private function validate(): void
    {
        v::stringType()->length(1, 255)->assert($this->name);
        v::floatType()->min(0)->assert($this->minPrice);
        v::floatType()->min($this->minPrice)->assert($this->maxPrice);
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'min_price' => $this->minPrice,
            'max_price' => $this->maxPrice
        ];
    }
}

<?php

declare(strict_types=1);

namespace Modules\Combo;

use Respect\Validation\ValidatorBuilder as v;

class RegisterComboDTO
{
    private string $name;
    private ?string $description;
    private float $price;

    public function __construct(string $name, float $price, ?string $description = null)
    {
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
        $this->validate();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    private function validate(): void
    {
        v::stringType()->lengthBetween(1, 255)->assert($this->name);
        if ($this->description !== null) {
            v::stringType()->lengthBetween(1, 1000)->assert($this->description);
        }
        v::floatType()->greaterThanOrEqual(0)->assert($this->price);
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price
        ];
    }
}

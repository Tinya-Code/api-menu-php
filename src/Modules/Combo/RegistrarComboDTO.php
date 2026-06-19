<?php

declare(strict_types=1);

namespace Modules\Combo;

use Respect\Validation\Validator as v;

class RegistrarComboDTO
{
    private string $name;
    private string $description;
    private float $price;

    public function __construct(string $name, string $description, float $price)
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

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    private function validate(): void
    {
        v::stringType()->length(1, 255)->assert($this->name);
        v::stringType()->length(0, 1000)->assert($this->description);
        v::floatType()->min(0)->assert($this->price);
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

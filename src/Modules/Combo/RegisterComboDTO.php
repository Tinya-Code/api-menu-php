<?php

declare(strict_types=1);

namespace Modules\Combo;

use Respect\Validation\ValidatorBuilder as v;

class RegisterComboDTO
{
    private string $name;
    private float $price;
    private ?string $description;
    private ?string $imageUrl;

    public function __construct(string $name, float $price, ?string $description = null, ?string $imageUrl = null)
    {
        $this->name = $name;
        $this->price = $price;
        $this->description = $description;
        $this->imageUrl = $imageUrl;
        $this->validate();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    private function validate(): void
    {
        v::stringType()->lengthBetween(1, 255)->assert($this->name);
        v::floatType()->greaterThanOrEqual(0)->assert($this->price);
        if ($this->description !== null) {
            v::stringType()->lengthBetween(1, 1000)->assert($this->description);
        }
        if ($this->imageUrl !== null) {
            v::stringType()->lengthBetween(1, 500)->assert($this->imageUrl);
        }
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'price' => $this->price,
            'description' => $this->description,
            'image_url' => $this->imageUrl
        ];
    }
}

<?php

declare(strict_types=1);

namespace Modules\Category;

use Respect\Validation\Validator as v;

class RegistrarCategoryDTO
{
    private string $name;
    private string $description;

    public function __construct(string $name, string $description)
    {
        $this->name = $name;
        $this->description = $description;
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

    private function validate(): void
    {
        v::stringType()->length(1, 255)->assert($this->name);
        v::stringType()->length(0, 1000)->assert($this->description);
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description
        ];
    }
}

<?php

declare(strict_types=1);

namespace Modules\Category;

use Respect\Validation\ValidatorBuilder as v;

class RegisterCategoryDTO
{
    private string $name;
    private string $description;
    private string $blockId;

    public function __construct(
        string $name,
        string $description,
        string $blockId
    ) {
        $this->name = $name;
        $this->description = $description;
        $this->blockId = $blockId;
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

    public function getBlockId(): string
    {
        return $this->blockId;
    }

    private function validate(): void
    {
        v::stringType()->lengthBetween(1, 255)->assert($this->name);
        v::stringType()->lengthBetween(0, 1000)->assert($this->description);
        v::stringType()->lengthBetween(1, 36)->assert($this->blockId);
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'block_id' => $this->blockId
        ];
    }
}

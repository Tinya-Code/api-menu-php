<?php

declare(strict_types=1);

namespace Modules\Category;

use Respect\Validation\ValidatorBuilder as v;

class RegisterCategoryDTO
{
    private string $name;
    private string $description;
    private string $blockId;
    private int $sortOrder;

    public function __construct(
        string $name,
        string $description,
        string $blockId,
        int $sortOrder = 0
    ) {
        $this->name = $name;
        $this->description = $description;
        $this->blockId = $blockId;
        $this->sortOrder = $sortOrder;
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

    public function getSortOrder(): int
    {
        return $this->sortOrder;
    }

    private function validate(): void
    {
        v::stringType()->lengthBetween(1, 255)->assert($this->name);
        v::stringType()->lengthBetween(0, 1000)->assert($this->description);
        v::stringType()->lengthBetween(1, 36)->assert($this->blockId);
        v::intType()->greaterThanOrEqual(0)->assert($this->sortOrder);
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'block_id' => $this->blockId,
            'sort_order' => $this->sortOrder
        ];
    }
}

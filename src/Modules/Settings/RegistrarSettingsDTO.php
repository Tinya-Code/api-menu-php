<?php

declare(strict_types=1);

namespace Modules\Settings;

use Respect\Validation\Validator as v;

class RegistrarSettingsDTO
{
    private string $key;
    private string $value;
    private ?string $description;

    public function __construct(string $key, string $value, ?string $description = null)
    {
        $this->key = $key;
        $this->value = $value;
        $this->description = $description;
        $this->validate();
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    private function validate(): void
    {
        v::stringType()->length(1, 255)->assert($this->key);
        v::stringType()->length(0, 5000)->assert($this->value);
        if ($this->description !== null) {
            v::stringType()->length(0, 1000)->assert($this->description);
        }
    }

    public function toArray(): array
    {
        return [
            'key' => $this->key,
            'value' => $this->value,
            'description' => $this->description
        ];
    }
}

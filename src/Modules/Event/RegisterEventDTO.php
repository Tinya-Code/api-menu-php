<?php

declare(strict_types=1);

namespace Modules\Event;

class RegisterEventDTO
{
    private string $name;
    private string $date;
    private ?string $description;
    private ?string $imageUrl;

    public function __construct(string $name, string $date, ?string $description = null, ?string $imageUrl = null)
    {
        $this->name = $name;
        $this->date = $date;
        $this->description = $description !== null && trim($description) !== '' ? $description : null;
        $this->imageUrl = $imageUrl !== null && trim($imageUrl) !== '' ? $imageUrl : null;
        $this->validate();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDate(): string
    {
        return $this->date;
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
        $len = mb_strlen($this->name);
        if ($len < 1 || $len > 255) {
            throw new \InvalidArgumentException(
                "The name field must be between 1 and 255 characters, got {$len}"
            );
        }
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'date' => $this->date,
            'description' => $this->description,
            'image_url' => $this->imageUrl
        ];
    }
}

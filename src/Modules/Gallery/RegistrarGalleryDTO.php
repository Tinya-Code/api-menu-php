<?php

declare(strict_types=1);

namespace Modules\Gallery;

use Respect\Validation\Validator as v;

class RegistrarGalleryDTO
{
    private string $title;
    private string $imageUrl;
    private ?string $description;

    public function __construct(string $title, string $imageUrl, ?string $description = null)
    {
        $this->title = $title;
        $this->imageUrl = $imageUrl;
        $this->description = $description;
        $this->validate();
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    private function validate(): void
    {
        v::stringType()->length(1, 255)->assert($this->title);
        v::url()->assert($this->imageUrl);
        if ($this->description !== null) {
            v::stringType()->length(0, 1000)->assert($this->description);
        }
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'image_url' => $this->imageUrl,
            'description' => $this->description
        ];
    }
}
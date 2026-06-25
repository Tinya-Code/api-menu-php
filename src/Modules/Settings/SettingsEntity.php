<?php

declare(strict_types=1);

namespace Modules\Settings;

class SettingsEntity
{
    private ?int $id;

    private array $restaurant;
    private array $settings;

    private ?string $createdAt;
    private ?string $updatedAt;

    public function __construct(
        array $restaurant,
        array $settings,
        ?int $id = null,
        ?string $createdAt = null,
        ?string $updatedAt = null
    ) {
        $this->id = $id;
        $this->restaurant = $restaurant;
        $this->settings = $settings;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRestaurant(): array
    {
        return $this->restaurant;
    }

    public function setRestaurant(array $restaurant): void
    {
        $this->restaurant = $restaurant;
    }

    public function getSettings(): array
    {
        return $this->settings;
    }

    public function setSettings(array $settings): void
    {
        $this->settings = $settings;
    }

    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?string
    {
        return $this->updatedAt;
    }

    public function toArray(): array
    {
        return [
            'data' => [
                'restaurant' => $this->restaurant,
                'settings' => $this->settings,
            ],
        ];
    }
}

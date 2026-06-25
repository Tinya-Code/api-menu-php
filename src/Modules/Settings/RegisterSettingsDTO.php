<?php

declare(strict_types=1);

namespace Modules\Settings;

class RegisterSettingsDTO
{
    private array $restaurant;
    private array $settings;

    public function __construct(array $data)
    {
        $this->restaurant = $data['restaurant'] ?? [];
        $this->settings = $data['settings'] ?? [];
    }

    public function getRestaurant(): array
    {
        return $this->restaurant;
    }

    public function getSettings(): array
    {
        return $this->settings;
    }

    public function getLogoUrl(): ?string
    {
        return $this->settings['logo_url'] ?? null;
    }

    public function setLogoUrl(string $url): void
    {
        $this->settings['logo_url'] = $url;
    }

    public function toArray(): array
    {
        return [
            'restaurant' => $this->restaurant,
            'settings' => $this->settings,
        ];
    }
}

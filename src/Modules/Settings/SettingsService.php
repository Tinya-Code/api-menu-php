<?php

declare(strict_types=1);

namespace Modules\Settings;

class SettingsService
{
    private SettingsRepository $repository;

    public function __construct()
    {
        $this->repository = new SettingsRepository();
    }

    public function get(): ?SettingsEntity
    {
        return $this->repository->find();
    }

    public function save(RegisterSettingsDTO $dto): SettingsEntity
    {
        return $this->repository->save($dto);
    }
}

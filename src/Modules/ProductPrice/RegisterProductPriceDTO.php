<?php

declare(strict_types=1);

namespace Modules\ProductPrice;

use Respect\Validation\ValidatorBuilder as v;

class RegisterProductPriceDTO
{
    private string $productId;
    private float $price;
    private string $ruleType;
    private ?int $startDay;
    private ?int $endDay;
    private ?string $startDatetime;
    private ?string $endDatetime;

    public function __construct(
        string $productId,
        float $price,
        string $ruleType,
        ?int $startDay = null,
        ?int $endDay = null,
        ?string $startDatetime = null,
        ?string $endDatetime = null
    ) {
        $this->productId = $productId;
        $this->price = $price;
        $this->ruleType = $ruleType;
        $this->startDay = $startDay;
        $this->endDay = $endDay;
        $this->startDatetime = $startDatetime;
        $this->endDatetime = $endDatetime;
        $this->validate();
    }

    public function getProductId(): string
    {
        return $this->productId;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getRuleType(): string
    {
        return $this->ruleType;
    }

    public function getStartDay(): ?int
    {
        return $this->startDay;
    }

    public function getEndDay(): ?int
    {
        return $this->endDay;
    }

    public function getStartDatetime(): ?string
    {
        return $this->startDatetime;
    }

    public function getEndDatetime(): ?string
    {
        return $this->endDatetime;
    }

    private function validate(): void
    {
        v::stringType()->lengthBetween(1, 255)->assert($this->productId);
        v::floatType()->greaterThanOrEqual(0)->assert($this->price);
        v::in(['DAY', 'PROMOTION'])->assert($this->ruleType);

        if ($this->ruleType === 'DAY') {
            if ($this->startDay === null) {
                throw new \InvalidArgumentException('start_day is required for DAY rule type');
            }
            if ($this->endDay === null) {
                throw new \InvalidArgumentException('end_day is required for DAY rule type');
            }
            v::intType()->between(1, 31)->assert($this->startDay);
            v::intType()->between(1, 31)->assert($this->endDay);

            if ($this->startDay > $this->endDay) {
                throw new \InvalidArgumentException('start_day must be less than or equal to end_day');
            }

            if ($this->startDatetime !== null || $this->endDatetime !== null) {
                throw new \InvalidArgumentException('start_datetime and end_datetime must be null for DAY rule type');
            }
        } else {
            if ($this->startDatetime === null) {
                throw new \InvalidArgumentException('start_datetime is required for PROMOTION rule type');
            }
            if ($this->endDatetime === null) {
                throw new \InvalidArgumentException('end_datetime is required for PROMOTION rule type');
            }
            v::stringType()->lengthBetween(1, 255)->assert($this->startDatetime);
            v::stringType()->lengthBetween(1, 255)->assert($this->endDatetime);

            if ($this->startDatetime >= $this->endDatetime) {
                throw new \InvalidArgumentException('start_datetime must be less than end_datetime');
            }

            if ($this->startDay !== null || $this->endDay !== null) {
                throw new \InvalidArgumentException('start_day and end_day must be null for PROMOTION rule type');
            }
        }
    }

    public function toArray(): array
    {
        return [
            'product_id' => $this->productId,
            'price' => $this->price,
            'rule_type' => $this->ruleType,
            'start_day' => $this->startDay,
            'end_day' => $this->endDay,
            'start_datetime' => $this->startDatetime,
            'end_datetime' => $this->endDatetime
        ];
    }
}

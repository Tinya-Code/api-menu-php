<?php

declare(strict_types=1);

namespace Modules\ProductPrice;

class ProductPriceEntity
{
    private ?string $id;
    private string $productId;
    private float $price;
    private ?string $name;
    private ?string $description;
    private ?int $startDay;
    private ?int $endDay;
    private ?string $startDatetime;
    private ?string $endDatetime;
    private string $ruleType;

    public function __construct(
        string $productId,
        float $price,
        string $ruleType,
        ?string $name = null,
        ?string $description = null,
        ?int $startDay = null,
        ?int $endDay = null,
        ?string $startDatetime = null,
        ?string $endDatetime = null,
        ?string $id = null
    ) {
        $this->id = $id;
        $this->productId = $productId;
        $this->price = $price;
        $this->ruleType = $ruleType;
        $this->name = $name;
        $this->description = $description;
        $this->startDay = $startDay;
        $this->endDay = $endDay;
        $this->startDatetime = $startDatetime;
        $this->endDatetime = $endDatetime;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getProductId(): string
    {
        return $this->productId;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getStartDay(): ?int
    {
        return $this->startDay;
    }

    public function setStartDay(?int $startDay): void
    {
        $this->startDay = $startDay;
    }

    public function getEndDay(): ?int
    {
        return $this->endDay;
    }

    public function setEndDay(?int $endDay): void
    {
        $this->endDay = $endDay;
    }

    public function getStartDatetime(): ?string
    {
        return $this->startDatetime;
    }

    public function setStartDatetime(?string $startDatetime): void
    {
        $this->startDatetime = $startDatetime;
    }

    public function getEndDatetime(): ?string
    {
        return $this->endDatetime;
    }

    public function setEndDatetime(?string $endDatetime): void
    {
        $this->endDatetime = $endDatetime;
    }

    public function getRuleType(): string
    {
        return $this->ruleType;
    }

    public function setRuleType(string $ruleType): void
    {
        $this->ruleType = $ruleType;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'product_id' => $this->productId,
            'price' => $this->price,
            'name' => $this->name,
            'description' => $this->description,
            'start_day' => $this->startDay,
            'end_day' => $this->endDay,
            'start_datetime' => $this->startDatetime,
            'end_datetime' => $this->endDatetime,
            'rule_type' => $this->ruleType
        ];
    }
}

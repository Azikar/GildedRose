<?php

declare(strict_types=1);

namespace GildedRose\Strategies;

use GildedRose\Item;

abstract class StrategyAbstraction
{
    protected $item;

    public function __construct(Item $item)
    {
        $this->item = $item;
    }

    abstract public function updateQuality(): void;
}
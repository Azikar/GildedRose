<?php

declare(strict_types=1);

namespace GildedRose\Strategies;

final class NormalItem extends StrategyAbstraction
{
    public function updateQuality(): void
    {
        $this->item->quality --;

        if ($this->item->sell_in <= 0) {
            $this->item->quality --;
        }
        if ($this->item->quality < 0) {
            $this->item->quality = 0;
        }

        $this->item->sell_in --;
    }
}
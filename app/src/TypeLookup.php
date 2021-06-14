<?php

declare(strict_types=1);

namespace GildedRose;

use GildedRose\Strategies\AgedBrie;
use GildedRose\Strategies\Backstage;
use GildedRose\Strategies\Conjured;
use GildedRose\Strategies\NormalItem;
use GildedRose\Strategies\StrategyAbstraction;
use GildedRose\Strategies\Sulfuras;

final class TypeLookup
{
     private static $itemStrategy = [
         'Aged Brie' => AgedBrie::class,
         'Backstage passes to a TAFKAL80ETC concert' => Backstage::class,
         'Sulfuras, Hand of Ragnaros' => Sulfuras::class,
         'Conjured Mana Cake' => Conjured::class,
    ];

    static function typeStrategy(Item $item): StrategyAbstraction
    {
        $strategy = new NormalItem($item);
        if (array_key_exists($item->name, self::$itemStrategy)) {
            $strategy = new self::$itemStrategy[$item->name]($item);
        }

        return $strategy;
    }
}

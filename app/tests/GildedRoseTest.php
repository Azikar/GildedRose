<?php

declare(strict_types=1);

namespace Tests;

use GildedRose\GildedRose;
use GildedRose\Item;
use PHPUnit\Framework\TestCase;

class GildedRoseTest extends TestCase
{
    public function test_age_bre_quality_before_sell_day(): void
    {
        $quality = 1;
        $items = [
            new Item('Aged Brie', 2, 0),
            new Item('Aged Brie', 1, 0),
        ];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        foreach ($items as $item) {
            $this->assertSame($quality, $item->quality);
        }
    }

    public function test_age_bre_quality_after_expiration(): void
    {
        $quality = [2, 4, 6];
        $items = [
            new Item('Aged Brie', -1, 0),
            new Item('Aged Brie', -2, 2),
            new Item('Aged Brie', -3, 4),
        ];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        foreach ($items as $key => $item) {
            $this->assertSame($quality[$key], $item->quality);
        }
    }

    public function test_age_bre_quality_never_over_50(): void
    {
        $quality = 50;
        $items = [
            new Item('Aged Brie', 1, 49),
            new Item('Aged Brie', 0, 49),
            new Item('Aged Brie', -1, 49),
            new Item('Aged Brie', -2, 49),
            new Item('Aged Brie', -3, 50),
        ];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        foreach ($items as $item) {
            $this->assertEquals($quality, $item->quality);
        }
    }

    public function test_sulfuras_quality_and_sell_in_change(): void
    {
        $quality = [80, 80, 1];
        $sellIn = [0, 1, -2];
        $items = [
            new Item('Sulfuras, Hand of Ragnaros', 0, 80),
            new Item('Sulfuras, Hand of Ragnaros', 1, 80),
            new Item('Sulfuras, Hand of Ragnaros', -2, 1),
        ];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        foreach ($items as $key => $item) {
            $this->assertSame($quality[$key], $item->quality);
            $this->assertSame($sellIn[$key], $item->sell_in);
        }
    }

    public function test_Backstage_quality_increase_regular(): void
    {
        $quality = [11, 50, 50];
        $sellIn = [19, 18, 10];
        $items = [
            new Item('Backstage passes to a TAFKAL80ETC concert', 20, 10),
            new Item('Backstage passes to a TAFKAL80ETC concert', 19, 49),
            new Item('Backstage passes to a TAFKAL80ETC concert', 11, 50),
        ];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        foreach ($items as $key => $item) {
            $this->assertSame($quality[$key], $item->quality);
            $this->assertSame($sellIn[$key], $item->sell_in);
        }
    }

    public function test_Backstage_quality_increase_never_more_50(): void
    {
        $quality = [50, 50, 50, 50];
        $items = [

            new Item('Backstage passes to a TAFKAL80ETC concert', 11, 50),
            new Item('Backstage passes to a TAFKAL80ETC concert', 10, 49),
            new Item('Backstage passes to a TAFKAL80ETC concert', 5, 49),
        ];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        foreach ($items as $key => $item) {
            $this->assertSame($quality[$key], $item->quality);
        }
    }

    public function test_Backstage_quality_drop_after_concert(): void
    {
        $quality = [0, 0, 0];
        $items = [
            new Item('Backstage passes to a TAFKAL80ETC concert', 0, 50),
            new Item('Backstage passes to a TAFKAL80ETC concert', -1, 49),
            new Item('Backstage passes to a TAFKAL80ETC concert', -3, 49),
        ];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        foreach ($items as $key => $item) {
            $this->assertSame($quality[$key], $item->quality);
        }
    }

    public function test_Backstage_quality_10_days_to(): void
    {
        $quality = 12;

        $i = 10;
        while ($i > 5) {
            $items[] = new Item('Backstage passes to a TAFKAL80ETC concert', $i, 10);
            $i --;
        }

        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        foreach ($items as $item) {
            $this->assertSame($quality, $item->quality);
        }
    }

    public function test_Backstage_quality_5_days_to(): void
    {
        $quality = 13;

        $i = 5;
        while ($i > 0) {
            $items[] = new Item('Backstage passes to a TAFKAL80ETC concert', $i, 10);
            $i --;
        }

        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        foreach ($items as $item) {
            $this->assertSame($quality, $item->quality);
        }
    }

    public function test_normal_item_quality_before_sale(): void
    {
        $i = 5;
        $qualities = [];
        $items = [];
        while ($i > 0) {
            $quality = rand (1, 10);
            $items[] = new Item('Elixir of the Mongoose', $i, $quality);
            $qualities[] = $quality - 1;
            $i --;
        }

        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        foreach ($items as $key => $item) {
            $this->assertSame($qualities[$key], $item->quality);
        }
    }

    public function test_normal_item_quality_after_sale_degrade_twice(): void
    {
        $i = 0;
        $qualities = [];
        $items = [];
        while ($i > -5) {
            $quality = rand (2, 10);
            $items[] = new Item('Elixir of the Mongoose', $i, $quality);
            $qualities[] = $quality - 2;
            $i --;
        }

        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        foreach ($items as $key => $item) {
            $this->assertSame($qualities[$key], $item->quality);
        }
    }

    public function test_normal_item_quality_never_less_than_0(): void
    {
        $i = 10;
        $assertion = 0;
        $items = [];
        while ($i > -5) {
            $quality = 1;
            $items[] = new Item('Elixir of the Mongoose', $i, $quality);
            $i --;
        }

        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        foreach ($items as $item) {
            $this->assertLessThanOrEqual($item->quality, $assertion);
        }
    }

    public function test_conjured_item_quality_must_decrease_double_normal_item_before_sell()
    {
        $i = 10;
        $qualities = [];
        $items = [];
        while ($i > 1) {
            $quality = rand (2, 10);
            $items[] = new Item('Conjured Mana Cake', $i, $quality);
            $qualities[] = $quality - 2;
            $i --;
        }

        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        foreach ($items as $key => $item) {
            $this->assertSame($qualities[$key], $item->quality);
        }
    }

    public function test_conjured_item_quality_must_decrease_double_after_sell()
    {
        $i = 0;
        $qualities = [];
        $items = [];
        while ($i > -10) {
            $quality = rand (4, 10);
            $items[] = new Item('Conjured Mana Cake', $i, $quality);
            $qualities[] = $quality - 4;
            $i --;
        }

        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        foreach ($items as $key => $item) {
            $this->assertSame($qualities[$key], $item->quality);
        }
    }

    public function test_conjured_item_quality_must_not_get_to_less_than_0()
    {
        $i = 10;
        $items = [];
        while ($i > -10) {
            $quality = rand (0, 2);
            $items[] = new Item('Conjured Mana Cake', $i, $quality);
            $i --;
        }

        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        foreach ($items as $item) {
            $this->assertLessThanOrEqual($item->quality, 0);
        }
    }
}

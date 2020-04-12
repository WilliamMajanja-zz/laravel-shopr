<?php

namespace Happypixels\Shopr\Tests\Feature\Money;

use Happypixels\Shopr\Facades\Cart;
use Happypixels\Shopr\Models\Order;
use Happypixels\Shopr\Tests\Support\Models\TestShoppable;
use Happypixels\Shopr\Tests\TestCase;

class PriceFormattingTest extends TestCase
{
    /** @test */
    public function it_formats_order_amounts()
    {
        config(['shopr.currency' => 'USD']);

        $order = new Order;
        $order->total = 100;

        $this->assertEquals('$100.00', $order->total_formatted);
        $this->assertEquals('$0.00', $order->sub_total_formatted);
        $this->assertEquals('$0.00', $order->tax_formatted);
    }

    /** @test */
    public function it_formats_order_item_amounts()
    {
        config(['shopr.currency' => 'USD']);

        $order = factory(Order::class)->create();
        $order->items()->create([
            'shoppable_type' => TestShoppable::class,
            'shoppable_id' => TestShoppable::first()->id,
            'title' => 'Test product',
            'price' => 500,
            'quantity' => 2,
        ]);

        $this->assertEquals('$500.00', $order->items->first()->price_formatted);
        $this->assertEquals('$1,000.00', $order->items->first()->total_formatted);
    }

    /** @test */
    public function it_formats_cart_summary_amounts()
    {
        config(['shopr.currency' => 'USD']);

        Cart::add(TestShoppable::first());

        $summary = Cart::get();

        $this->assertEquals('$500.00', $summary['sub_total_formatted']);
        $this->assertEquals('$0.00', $summary['tax_total_formatted']);
        $this->assertEquals('$500.00', $summary['total_formatted']);
    }

    /** @test */
    public function it_formats_cart_item_amounts()
    {
        config(['shopr.currency' => 'USD']);

        Cart::add(TestShoppable::first());

        $items = Cart::items();

        $this->assertEquals('$500.00', $items->first()->price_formatted);
    }
}

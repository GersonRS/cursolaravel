<?php

use Illuminate\Database\Seeder;
use Ecommerce\Models\Order;
use Ecommerce\Models\OrderItem;

class OrderTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Order::class,10)->create()->each(function($o){
        	for ($i=0; $i <= 2 ; $i++) { 
        		$o->items()->save(factory(OrderItem::class)->make([
        				'product_id' => rand(1,5),
        				'amount' => 2,
        				'price' => 50
        		]));
        	}
        });
    }
}

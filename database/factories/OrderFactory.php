<?php

namespace Database\Factories;

use App\Models\Cart;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $cart_items = Cart::where('user_id', auth()->id())->get();
        $order = Order::create();
    // Clear the cart by deleting all cart items for the current user
        $cart_items->each(function ($cart_item) {
            $cart_item->delete();
    });
        $order->order_items()->saveMany($cart_items);
        $user = User::inRandomOrder()->first();
        return [
            'user_id' => $user,
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'phone_no' => $this->faker->phoneNumber(),
            'email' => $this->faker->email(),
            'zipcode' => $this->faker->streetAddress(),
            'sub_county' => $this->faker->streetName(),
            'county' => $this->faker->city()
        ];
    }
}

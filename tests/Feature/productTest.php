<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class productTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_create_product()
    {
        $data = [
            'name'=> 'suite',
            'price'=> '300.00',
            'description'=> 'This is suite',
            'quantity'=> '7',
        ];
        $response = $this->post(route('products.store'), $data);

        $response->assertStatus(201);
    }
}

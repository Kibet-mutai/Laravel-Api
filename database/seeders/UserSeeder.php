<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->count(1)
            ->create()
            ->each(
                function ($user) {
                    $user->assignRole('admin');
                }
            );
        User::factory()->count(15)
            // ->has(Seller::factory(1))
            ->create()
            ->each(
                function ($user) {
                    $user->assignRole('store_owner');
                }
            );

        collect(User::factory()->count(15)
            // ->has(Customer::factory(1))
            ->create())
            ->each(
                function ($user) {
                    $user->assignRole('customer');
                }
            );
    }
}

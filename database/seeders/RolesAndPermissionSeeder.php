<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Spatie\Permission\Models\Role;

class RolesAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        //permisions for app

        $addUser = 'add_user';
        $editUser = 'edit_user';
        $destroyUser = 'delete_user';

        $createStore = 'create_store';
        $deleteStore = 'delete_store';
        $editStore = 'edit_store';

        $createProfile = 'create_profile';
        $editProfile = 'edit_profile';
        $deleteProfile = 'delete_employer';
        $viewProducts = 'view_products';

        Permission::create(['name'=>$addUser]);
        Permission::create(['name'=>$editUser]);
        Permission::create(['name'=>$destroyUser]);
        Permission::create(['name'=>$editStore]);
        Permission::create(['name'=>$deleteStore]);
        Permission::create(['name'=>$createStore]);


        Permission::create(['name'=>$createProfile]);
        Permission::create(['name'=>$editProfile]);
        Permission::create(['name'=>$deleteProfile]);
        Permission::create(['name'=>$viewProducts]);

        //Roles for users

        $superAdmin = 'admin';
        $storeOwner = 'store_owner';
        $customer = 'customer';

        Role::create(['name' => $superAdmin])
            ->givePermissionTo(Permission::all());

        Role::create(['name' => $storeOwner])
            ->givePermissionTo([
                $createStore,
                $deleteStore,
                $editStore
            ]);

        Role::create(['name' => $customer])
            ->givePermissionTo([
                $createProfile,
                $editProfile,
                $deleteProfile,
                $viewProducts
            ]);
    }

}

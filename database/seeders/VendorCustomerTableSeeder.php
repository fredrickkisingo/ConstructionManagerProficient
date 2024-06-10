<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\Role;

class VendorCustomerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::firstOrNew(['name' => 'customer']);
        if (!$role->exists) {
            $role->fill([
                'display_name' => 'Customer',
            ])->save();
        }

        $role = Role::firstOrNew(['name' => 'vendor']);
        if (!$role->exists) {
            $role->fill([
                'display_name' => 'Vendor',
            ])->save();
        }
    }
}

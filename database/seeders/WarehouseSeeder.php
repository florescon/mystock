<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Database\Seeder;

class WarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $warehouses = [
            ['name' => 'Tienda', 'city' => 'Lagos de Moreno, Jal.', 'phone' => '061234567896', 'email' => 'default@store.mx', 'country' => 'mex'],
        ];

        collect($warehouses)->each(function ($warehouse) {
            $ware = Warehouse::create($warehouse);

            if (User::first()) {
                $ware->assignedUsers()->sync(User::first());
            }
        });
    }
}

<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Customer::factory(100)->create();
        DB::unprepared(file_get_contents(public_path('/import/customers.sql')));
        DB::unprepared(file_get_contents(public_path('/import/sales.sql')));
        DB::unprepared(file_get_contents(public_path('/import/sale_details_services.sql')));
    }
}

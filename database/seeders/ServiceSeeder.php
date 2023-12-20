<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Service;
use Illuminate\Support\Str;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $services = [
            [
                'id'                => 1,
                'name'              => 'Inscripción',
                'uuid'              => Str::uuid()->toString(),
                'code'              => '16027911',
                'price'             => 400,
                'note'              => null,
                'status'            => 1,
                'tax_type'          => 0,
                'image'             => 0,
                'featured'          => 0,
                'with_input'        => 0,
                'service_type'      => 0,
                'deleted_at'        => null,
                'created_at'        => '2022-01-01 09:20:12.000000',
                'updated_at'        => '2022-01-01 09:20:12.000000',
            ],
            [
                'id'                => 2,
                'name'              => 'Mensualidad',
                'uuid'              => Str::uuid()->toString(),
                'code'              => '16027912',
                'price'             => 1500,
                'note'              => null,
                'status'            => 1,
                'tax_type'          => 0,
                'image'             => 0,
                'featured'          => 0,
                'with_input'        => 0,
                'service_type'      => 1,
                'deleted_at'        => null,
                'created_at'        => '2022-01-01 09:20:12.000000',
                'updated_at'        => '2022-01-01 09:20:12.000000',
            ],
            [
                'id'                => 3,
                'name'              => 'Pase Libre',
                'uuid'              => Str::uuid()->toString(),
                'code'              => '16027913',
                'price'             => 100,
                'note'              => null,
                'status'            => 1,
                'tax_type'          => 0,
                'image'             => 0,
                'featured'          => 0,
                'with_input'        => 1,
                'service_type'      => 2,
                'deleted_at'        => null,
                'created_at'        => '2022-01-01 09:20:12.000000',
                'updated_at'        => '2022-01-01 09:20:12.000000',
            ],
        ];

        Service::insert($services);
    }
}

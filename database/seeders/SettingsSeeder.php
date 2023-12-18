<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = [
            [
                'id'                        => 1,
                'company_name'              => 'Acuática Azul',
                'company_email'             => 'contact@aqua.com',
                'company_phone'             => '212 5 22 22 22 22',
                'company_logo'              => 'logo.png',
                'company_address'           => 'Hernando de Martell',
                'company_tax'               => '0',
                'telegram_channel'          => '',
                'default_currency_id'       => 1,
                'default_currency_position' => 'right',
                'default_date_format'       => 'd-m-Y',
                'default_client_id'         => '1',
                'default_warehouse_id'      => null,
                'default_language'          => 'fr',
                'invoice_header'            => '',
                'invoice_footer'            => '',
                'invoice_footer_text'       => 'Gracias por su compra',
                'is_rtl'                    => '1',
                'sale_prefix'               => 'VENTA-000',
                'saleReturn_prefix'         => 'VENTADEV-000',
                'purchase_prefix'           => 'COMPRA-000',
                'purchaseReturn_prefix'     => 'COMPRADEV-000',
                'quotation_prefix'          => 'COT-000',
                'salePayment_prefix'        => 'PAGOVEN-000',
                'purchasePayment_prefix'    => 'PAGOCOM-000',
                'show_email'                => '1',
                'show_address'              => '1',
                'show_order_tax'            => '1',
                'show_discount'             => '1',
                'show_shipping'             => '1',
                'created_at'                => now(),
            ],
        ];

        Setting::insert($settings);
    }
}

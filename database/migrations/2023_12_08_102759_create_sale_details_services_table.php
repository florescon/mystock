<?php

declare(strict_types=1);

use App\Models\Service;
use App\Models\Sale;
use App\Models\Customer;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_details_services', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(Sale::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Service::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Customer::class)->nullOnDelete();
            $table->string('name');
            $table->string('code');
            $table->integer('quantity');
            $table->integer('price');
            $table->integer('unit_price');
            $table->integer('sub_total');
            $table->integer('product_discount_amount');
            $table->string('product_discount_type')->default('fixed');
            $table->integer('product_tax_amount');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sale_details_services');
    }
};

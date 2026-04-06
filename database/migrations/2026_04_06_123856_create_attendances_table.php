<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\SaleDetailsService;
use App\Models\Customer;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(SaleDetailsService::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Customer::class)->nullOnDelete();
            $table->foreignIdFor(User::class)->cascadeOnDelete();
            $table->string('time_day');
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
        Schema::dropIfExists('attendances');
    }
};

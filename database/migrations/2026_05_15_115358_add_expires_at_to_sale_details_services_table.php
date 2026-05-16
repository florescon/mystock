<?php

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
        Schema::table('sale_details_services', function (Blueprint $table) {
            $table->timestamp('expires_at')->nullable();
        });

        DB::table('sale_details_services')->update([
            'expires_at' => DB::raw('DATE_ADD(created_at, INTERVAL 30 DAY)')
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sale_details_services', function (Blueprint $table) {
            //
        });
    }
};

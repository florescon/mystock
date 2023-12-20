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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->string('name');
            $table->string('code')->unique()->nullable();
            $table->integer('price');
            $table->text('note')->nullable();
            $table->boolean('status')->default(1);
            $table->tinyInteger('tax_type')->nullable();
            $table->text('image')->nullable();
            $table->boolean('featured')->default(0);
            $table->boolean('with_input')->default(0);
            $table->boolean('with_days')->default(0);
            $table->string('service_type');
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
        Schema::dropIfExists('services');
    }
};

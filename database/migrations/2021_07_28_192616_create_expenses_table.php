<?php

declare(strict_types=1);

use App\Models\ExpenseCategory;
use App\Models\User;
use App\Models\Warehouse;
use App\Models\Cash;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(ExpenseCategory::class, 'category_id')->restrictOnDelete();
            $table->foreignIdFor(User::class)->nullable()->restrictOnDelete();
            $table->foreignIdFor(Warehouse::class)->nullable()->restrictOnDelete();

            $table->date('date');
            $table->string('reference', 192);
            $table->string('details', 192)->nullable();
            $table->float('amount', 10, 0);
            $table->string('document')->nullable();
            $table->boolean('is_expense')->default(1);
            $table->foreignIdFor(Cash::class)->nullable()->cascadeOnDelete();
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
        Schema::dropIfExists('expenses');
    }
};

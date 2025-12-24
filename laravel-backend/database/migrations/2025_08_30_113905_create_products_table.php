<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('sku')->unique();
        $table->text('alias')->nullable();
        $table->decimal('price', 10, 2);
        $table->decimal('regular_price', 10, 2);
        $table->boolean('status')->default(1);
        $table->timestamps();     // created_at & updated_at
        $table->softDeletes();    // deleted_at for soft deletes
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

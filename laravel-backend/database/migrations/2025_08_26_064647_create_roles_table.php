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
       Schema::create('roles', function (Blueprint $table) {
        $table->id();
        $table->string('name')->unique(); // admin, vendor, customer
        $table->timestamps();
    });

         // insert default roles
    DB::table('roles')->insert([
        ['name' => 'admin', 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'vendor', 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'customer', 'created_at' => now(), 'updated_at' => now()],
    ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};

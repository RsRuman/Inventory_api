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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_id')->references('id')->on('inventories');
            $table->string('name')->index();
            $table->text('description');
            $table->string('photo')->nullable();
            $table->integer('quantity');
            $table->softDeletes();
            $table->timestamps();

            $table->unique(['inventory_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};

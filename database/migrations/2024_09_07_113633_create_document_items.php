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
        Schema::create('document_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')
                ->constrained('documents')
                ->onDelete('CASCADE')
                ->onUpdate('CASCADE');
            $table->foreignId('product_id')
                ->constrained('products')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
            $table->unsignedInteger('price')->nullable();
            $table->unsignedInteger('quantity');
            $table->unsignedInteger('remainder');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_items');
    }
};

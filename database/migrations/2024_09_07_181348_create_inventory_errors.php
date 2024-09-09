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
        Schema::create('inventory_errors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_item_id')
                ->constrained('document_items')
                ->onDelete('CASCADE')
                ->onUpdate('CASCADE');

            $table->integer('error');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_errors');
    }
};

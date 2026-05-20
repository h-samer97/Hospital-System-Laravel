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
        Schema::create('service_group', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')
                  ->constrained()
                  ->cascadeOnDelete();
            $table->foreignId('service_id')
                  ->constrained()
                  ->cascadeOnDelete();
            // quantity في الـ pivot لأنها خاصية العلاقة وليس الـ model
            $table->unsignedInteger('quantity')->default(1);
            // سعر وقت الحفظ — لأن السعر قد يتغير لاحقاً
            $table->decimal('unit_price', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_group');
    }
};

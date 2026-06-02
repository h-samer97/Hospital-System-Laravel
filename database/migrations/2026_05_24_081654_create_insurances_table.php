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
        Schema::create('insurances', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('insurance_code');
            $table->decimal('discount_percentage', 5, 2); # 5 - 2 = 3 => 999.99 
            $table->decimal('company_rate', 5, 2); # $$5 \text{ (الكل)} - 2 \text{ (بعد الفاصلة)} = 3 \text{ (قبل الفاصلة)}$$
            $table->boolean('is_active')->default(true);
            $table->string('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('insurances');
    }
};

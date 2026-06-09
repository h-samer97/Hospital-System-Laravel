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
        Schema::create('single_invoices', function (Blueprint $table) {
            $table->id();
            $table->date('invoice_date');

            $table->foreignId('patient_id')
                  ->constrained('patients')->cascadeOnDelete();
            $table->foreignId('doctor_id')
                  ->constrained('doctors')->cascadeOnDelete();
            $table->foreignId('section_id')
                  ->constrained('sections')->cascadeOnDelete();
            $table->foreignId('service_id')
                  ->constrained('services')->cascadeOnDelete();

            $table->decimal('price', 10, 2)->default(0); # RAW PRICE
            $table->decimal('discount_value', 10, 2)->default(0);
            $table->decimal('tax_rate', 10, 2)->default(17); # Default TAX = 17%
            $table->decimal('tax_value', 10, 2)->default(0);
            $table->decimal('total_with_tax')->default(0);
            $table->enum('type', ['cash', 'deferred'])->default('cash');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('single_invoices');
    }
};

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
        Schema::create('payment_accounts', function (Blueprint $table) {
            $table->id();
            $table->date('date');

            $table->foreignId('patient_id')
                ->constrained('patients')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->decimal('amount', 8, 2);
            $table->string('description')->nullable();
            $table->softDeletes();

            $table->index(['patient_id', 'date'], 'idx_payment_patients_date');
            $table->index('date', 'idx_payment_date');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_accounts');
    }
};

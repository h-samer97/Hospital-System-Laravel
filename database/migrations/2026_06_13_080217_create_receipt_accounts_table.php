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
        Schema::create('receipt_accounts', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignId('patient_id')
                  ->constrained('patients')
                  ->cascadeOnDelete();
            $table->decimal('debit', 10, 2);
            $table->string('description');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receipt_accounts');
    }
};

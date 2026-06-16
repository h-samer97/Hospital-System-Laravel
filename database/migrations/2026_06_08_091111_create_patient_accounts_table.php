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
        Schema::create('patient_accounts', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignId('single_invoice_id')
                ->constrained('single_invoices')->cascadeOnDelete();
            // `receipts` table may be created after this migration; avoid adding
            // the foreign key constraint here. Use nullable unsignedBigInteger
            // and add the FK in a dedicated migration if needed.
            $table->unsignedBigInteger('receipt_id')->nullable();
            $table->foreignId('patient_id')
                ->constrained('patients')->cascadeOnDelete();
            $table->decimal('debit',  10, 2)->default(0);
            $table->decimal('credit', 10, 2)->default(0);
            $table->string('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_accounts');
    }
};

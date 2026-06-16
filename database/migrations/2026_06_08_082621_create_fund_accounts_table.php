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
        Schema::create('fund_accounts', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignId('single_invoice_id')
                ->constrained('single_invoices')->cascadeOnDelete();
            // `receipts` table may be created after this migration; avoid adding
            // the foreign key constraint here to prevent "foreign key
            // constraint is incorrectly formed" errors. Define as nullable
            // unsignedBigInteger and add constraint in a later migration if
            // needed.
            $table->unsignedBigInteger('receipt_id')->nullable();
            $table->decimal('debit',  10, 2)->default(0);
            $table->decimal('credit', 10, 2)->default(0);
            $table->string('notes')->nullable();
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('fund_accounts');
    }
};

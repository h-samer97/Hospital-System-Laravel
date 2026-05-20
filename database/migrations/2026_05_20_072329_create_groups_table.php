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
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('notes')->nullable();
            // الحسابات تُخزَّن في DB للاستعلام لاحقاً
            $table->decimal('subtotal',    10, 2)->default(0);
            $table->decimal('discount',    10, 2)->default(0);
            $table->decimal('tax_percent', 5,  2)->default(17);
            $table->decimal('total',       10, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('groups');
    }
};

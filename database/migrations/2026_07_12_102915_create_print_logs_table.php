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
        Schema::create('print_logs', function (Blueprint $table) {
            $table->id();
            $table->morphs('printable');
            $table->foreignId('admin_id')->constrained('admins')->cascadeOnDelete()->cascadeOnUpdate();
            $table->enum('action', ['view', 'download'])->default('view');
            $table->string('ip_address', 45); # 45 becuase for IP V6
            $table->string('user_agent')->nullable();
            $table->index(['printable_type', 'printable_id', 'created_at'], 'idx_print_logs_printable_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('print_logs');
    }
};

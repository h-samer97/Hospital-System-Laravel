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
        if (! Schema::hasColumn('single_services', 'name') || ! Schema::hasColumn('single_services', 'price')) {
            Schema::table('single_services', function (Blueprint $table) {
                if (! Schema::hasColumn('single_services', 'name')) {
                    $table->string('name')->nullable(false);
                }

                if (! Schema::hasColumn('single_services', 'price')) {
                    $table->decimal('price', 10, 2)->default(0);
                }
            });
        }

        Schema::table('groups', function (Blueprint $table) {
            $table->string('name')->nullable(false);
            $table->text('notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('single_services', 'name') || Schema::hasColumn('single_services', 'price')) {
            Schema::table('single_services', function (Blueprint $table) {
                $columns = [];

                if (Schema::hasColumn('single_services', 'name')) {
                    $columns[] = 'name';
                }

                if (Schema::hasColumn('single_services', 'price')) {
                    $columns[] = 'price';
                }

                if (! empty($columns)) {
                    $table->dropColumn($columns);
                }
            });
        }

        if (Schema::hasColumn('groups', 'name') || Schema::hasColumn('groups', 'notes')) {
            Schema::table('groups', function (Blueprint $table) {
                $columns = [];

                if (Schema::hasColumn('groups', 'name')) {
                    $columns[] = 'name';
                }

                if (Schema::hasColumn('groups', 'notes')) {
                    $columns[] = 'notes';
                }

                if (! empty($columns)) {
                    $table->dropColumn($columns);
                }
            });
        }
    }
};

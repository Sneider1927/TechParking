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
        if (!Schema::hasColumn('roles', 'custom_name')) {
            Schema::table('roles', function (Blueprint $table) {
                $table->string('custom_name')->nullable()->after('name');
            });
        }

        if (!Schema::hasColumn('roles', 'description')) {
            Schema::table('roles', function (Blueprint $table) {
                $table->text('description')->nullable()->after('custom_name');
            });
        }

        if (!Schema::hasColumn('roles', 'active')) {
            Schema::table('roles', function (Blueprint $table) {
                $table->boolean('active')->default(1)->after('description');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            if (Schema::hasColumn('roles', 'custom_name')) {
                $table->dropColumn('custom_name');
            }
            if (Schema::hasColumn('roles', 'description')) {
                $table->dropColumn('description');
            }
            if (Schema::hasColumn('roles', 'active')) {
                $table->dropColumn('active');
            }
        });
    }
};

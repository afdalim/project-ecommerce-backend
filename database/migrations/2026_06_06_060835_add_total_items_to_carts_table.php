<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('carts', 'total_items')) {
            Schema::table('carts', function (Blueprint $table) {
                $table->integer('total_items')
                      ->default(0)
                      ->after('user_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('carts', 'total_items')) {
            Schema::table('carts', function (Blueprint $table) {
                $table->dropColumn('total_items');
            });
        }
    }
};
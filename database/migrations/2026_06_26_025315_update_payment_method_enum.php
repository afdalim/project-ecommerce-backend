<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {

            $table->string('payment_method')
                  ->change();

        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {

            $table->enum(
                'payment_method',
                [
                    'stripe',
                    'paypal',
                    'credit_card',
                    'bank_transfer'
                ]
            )->default('stripe')->change();

        });
    }
};
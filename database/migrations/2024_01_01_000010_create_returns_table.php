<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('returns', function (Blueprint $table) {
            $table->id();
            $table->string('return_number', 50)->unique();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['requested', 'approved', 'rejected', 'completed'])->default('requested');
            $table->enum('reason', ['defective', 'not_as_described', 'wrong_item', 'changed_mind', 'other'])->default('other');
            $table->text('description');
            $table->json('images')->nullable();
            $table->decimal('refund_amount', 10, 2);
            $table->enum('refund_status', ['pending', 'processed', 'failed'])->default('pending');
            $table->timestamp('requested_at')->useCurrent();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['order_id', 'user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('returns');
    }
};

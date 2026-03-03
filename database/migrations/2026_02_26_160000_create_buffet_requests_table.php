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
        Schema::create('buffet_requests', function (Blueprint $table) {
            $table->id();
            $table->string('public_code')->unique();

            $table->string('customer_name')->nullable();
            $table->string('phone', 30);
            $table->string('company_name')->nullable();
            $table->unsignedInteger('people_count')->nullable();
            $table->timestamp('event_at')->nullable();
            $table->text('details')->nullable();

            $table->string('status')->default('new');

            $table->decimal('quote_amount', 10, 2)->nullable();
            $table->text('quote_message')->nullable();
            $table->timestamp('quoted_at')->nullable();

            $table->foreignId('created_by_user_id')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();

            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buffet_requests');
    }
};

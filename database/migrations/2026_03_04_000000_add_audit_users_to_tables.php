<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('created_by_user_id')->nullable()->after('is_available')->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by_user_id')->nullable()->after('created_by_user_id')->constrained('users')->nullOnDelete();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('created_by_user_id')->nullable()->after('status')->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by_user_id')->nullable()->after('created_by_user_id')->constrained('users')->nullOnDelete();
        });

        Schema::table('works', function (Blueprint $table) {
            $table->foreignId('created_by_user_id')->nullable()->after('published_at')->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by_user_id')->nullable()->after('created_by_user_id')->constrained('users')->nullOnDelete();
        });

        Schema::table('buffet_requests', function (Blueprint $table) {
            if (! Schema::hasColumn('buffet_requests', 'created_by_user_id')) {
                $table->foreignId('created_by_user_id')->nullable()->after('quoted_at')->constrained('users')->nullOnDelete();
            }

            $table->foreignId('updated_by_user_id')->nullable()->after('created_by_user_id')->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropConstrainedForeignId('updated_by_user_id');
            $table->dropConstrainedForeignId('created_by_user_id');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropConstrainedForeignId('updated_by_user_id');
            $table->dropConstrainedForeignId('created_by_user_id');
        });

        Schema::table('works', function (Blueprint $table) {
            $table->dropConstrainedForeignId('updated_by_user_id');
            $table->dropConstrainedForeignId('created_by_user_id');
        });

        Schema::table('buffet_requests', function (Blueprint $table) {
            $table->dropConstrainedForeignId('updated_by_user_id');
        });
    }
};

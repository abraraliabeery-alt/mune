<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('table_number', 20)->nullable()->after('location_url');
            $table->string('public_code', 16)->nullable()->after('table_number');
            $table->unique('public_code');
        });

        $orders = DB::table('orders')->select('id')->whereNull('public_code')->get();

        foreach ($orders as $order) {
            DB::table('orders')
                ->where('id', $order->id)
                ->update(['public_code' => bin2hex(random_bytes(6))]);
        }
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropUnique(['public_code']);
            $table->dropColumn(['table_number', 'public_code']);
        });
    }
};

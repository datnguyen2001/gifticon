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
        Schema::table('carts', function (Blueprint $table) {
            $table->integer('type')->after('is_selected')->nullable()->comment("1 là chọn mua từ cart; 2 là chọn mua ngay");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

    }
};

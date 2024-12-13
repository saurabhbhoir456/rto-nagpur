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
        Schema::table('echallan_sms_logs', function (Blueprint $table) {
            $table->unsignedBigInteger('job_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('echallan_sms_logs', function (Blueprint $table) {
            $table->unsignedBigInteger('job_id')->change();
        });
    }
};

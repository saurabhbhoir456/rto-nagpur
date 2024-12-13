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
        Schema::create('echallan_sms_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('echallan_id');
            $table->string('mobile_number');
            $table->text('sms_message');
            $table->string('message_id');
            $table->string('job_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('echallan_sms_logs');
    }
};

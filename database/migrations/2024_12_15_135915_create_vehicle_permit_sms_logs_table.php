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
        Schema::create('vehicle_permit_sms_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_permit_id')->constrained()->onDelete('cascade');
            $table->string('mobile_number');
            $table->text('sms_message');
            $table->string('message_id')->nullable();
            $table->string('job_id')->nullable();
            $table->text('error_message')->nullable();
            $table->text('request_data')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_permit_sms_logs');
    }
};

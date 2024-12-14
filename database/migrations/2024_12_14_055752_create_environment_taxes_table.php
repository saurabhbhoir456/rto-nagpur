<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnvironmentTaxesTable extends Migration
{
    public function up()
    {
        Schema::create('environment_taxes', function (Blueprint $table) {
            $table->id();
            $table->string('vehicle_number');
            $table->string('mobile_number');
            $table->date('expiry_date');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('environment_taxes');
    }
}

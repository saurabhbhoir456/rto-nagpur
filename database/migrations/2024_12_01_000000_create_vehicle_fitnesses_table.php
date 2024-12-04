<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehicleFitnessesTable extends Migration
{
    public function up()
    {
        Schema::create('vehicle_fitnesses', function (Blueprint $table) {
            $table->id();
            $table->string('sr_no');
            $table->string('vehicle_number');
            $table->date('fitness_expiry');
            $table->string('mobile_number');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('vehicle_fitnesses');
    }
}

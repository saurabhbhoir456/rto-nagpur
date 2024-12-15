<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateVehicleFitnessesTable extends Migration
{
    public function up()
    {
        Schema::table('vehicle_fitnesses', function (Blueprint $table) {
            $table->dropColumn('sr_no');
            $table->renameColumn('fitness_expiry', 'expiry_date');
        });
    }

    public function down()
    {
        Schema::table('vehicle_fitnesses', function (Blueprint $table) {
            $table->string('sr_no');
            $table->renameColumn('expiry_date', 'fitness_expiry');
        });
    }
}

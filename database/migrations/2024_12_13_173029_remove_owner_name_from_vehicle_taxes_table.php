<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveOwnerNameFromVehicleTaxesTable extends Migration
{
    public function up()
    {
        // Check if the column exists before dropping it
        if (Schema::hasColumn('vehicle_taxes', 'owner_name')) {
            Schema::table('vehicle_taxes', function (Blueprint $table) {
                $table->dropColumn('owner_name');
            });
        }
    }

    public function down()
    {
        // Optionally add the column back in case of rollback
        if (!Schema::hasColumn('vehicle_taxes', 'owner_name')) {
            Schema::table('vehicle_taxes', function (Blueprint $table) {
                $table->string('owner_name')->nullable();
            });
        }
    }
}
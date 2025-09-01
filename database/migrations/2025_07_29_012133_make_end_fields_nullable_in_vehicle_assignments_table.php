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
        Schema::table('vehicle_assignments', function (Blueprint $table) {
            $table->date('end_datetime')->nullable()->change();
            $table->string('end_odometer')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicle_assignments', function (Blueprint $table) {
            $table->date('end_datetime')->nullable(false)->change();
            $table->string('end_odometer')->nullable(false)->change();
        });
    }
};

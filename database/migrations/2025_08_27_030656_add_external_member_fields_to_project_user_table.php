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
        Schema::table('project_user', function (Blueprint $table) {
            // Set user_id jadi nullable
            $table->unsignedBigInteger('user_id')->nullable()->change();

            $table->string('external_member_name')->nullable()->after('user_id');
            $table->string('role_in_project')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_user', function (Blueprint $table) {
            $table->dropColumn('external_member_name');
        });
    }
};

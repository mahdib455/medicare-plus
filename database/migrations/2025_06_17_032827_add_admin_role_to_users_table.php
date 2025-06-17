<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Modify the role enum to include 'admin'
            $table->enum('role', ['admin', 'doctor', 'patient'])->default('patient')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Revert back to original enum values
            $table->enum('role', ['doctor', 'patient'])->default('patient')->change();
        });
    }
};

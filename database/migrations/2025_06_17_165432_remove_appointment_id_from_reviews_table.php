<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // First, find and drop any foreign key constraints on appointment_id
        $foreignKeys = DB::select("
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = 'reviews'
            AND COLUMN_NAME = 'appointment_id'
            AND REFERENCED_TABLE_NAME IS NOT NULL
        ");

        foreach ($foreignKeys as $fk) {
            DB::statement("ALTER TABLE reviews DROP FOREIGN KEY {$fk->CONSTRAINT_NAME}");
        }

        // Then drop the column
        DB::statement('ALTER TABLE reviews DROP COLUMN IF EXISTS appointment_id');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reviews', function (Blueprint $table) {
            // Restore appointment_id
            $table->unsignedBigInteger('appointment_id')->nullable()->after('id');
            $table->foreign('appointment_id')->references('id')->on('appointments')->onDelete('cascade');
        });
    }
};

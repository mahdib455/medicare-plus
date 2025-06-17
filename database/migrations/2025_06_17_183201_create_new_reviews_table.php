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
        // Drop existing reviews table and recreate with proper structure
        Schema::dropIfExists('reviews');

        Schema::create('reviews', function (Blueprint $table) {
            $table->id(); // Primary key, auto increment

            // Foreign key columns
            $table->unsignedBigInteger('consultation_id')->nullable();
            $table->unsignedBigInteger('doctor_id');
            $table->unsignedBigInteger('patient_id');

            // Review data
            $table->integer('rating'); // 1-5 rating
            $table->text('comment')->nullable();
            $table->boolean('is_anonymous')->default(false);
            $table->enum('status', ['active', 'inactive', 'pending'])->default('active');

            $table->timestamps();

            // Indexes for performance
            $table->index(['doctor_id', 'status']);
            $table->index(['patient_id', 'consultation_id']);
            $table->index('rating');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reviews');
    }
};

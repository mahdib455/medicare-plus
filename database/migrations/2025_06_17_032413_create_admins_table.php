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
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('admin_level', ['super', 'regular', 'moderator'])->default('regular');
            $table->json('permissions')->nullable();
            $table->string('department')->default('Administration');
            $table->tinyInteger('access_level')->default(8); // 1-10 scale
            $table->timestamp('last_login_at')->nullable();
            $table->integer('login_count')->default(0);
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('notes')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('admin_level');
            $table->index('access_level');
            $table->index('last_login_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admins');
    }
};

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
        Schema::create('credit_applications', function (Blueprint $table) {
            $table->id();
            $table->dateTime('application_date')->nullable();
            $table->decimal('income', 12, 2);
            $table->enum('status', ['tertunda', 'disetujui', 'ditolak'])->nullable();
            $table->unsignedBigInteger('user_id')->constrained();
            $table->unsignedBigInteger('car_id')->constrained();
            $table->timestamps();

            $table->foreign('user_id')->on('users')->references('id')->cascadeOnDelete();
            $table->foreign('car_id')->on('cars')->references('id')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credit_applications');
    }
};

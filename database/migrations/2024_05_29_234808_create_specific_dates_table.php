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
        Schema::create('specific_dates', function (Blueprint $table) {
            $table->id();
            $table->date("data");
            $table->time("start_hour")->nullable();
            $table->time("end_hour")->nullable();
         
            $table->unsignedBigInteger("academia_id");
            $table->foreign('academia_id')->references('id')->on('academias')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('specific_dates');
    }
};

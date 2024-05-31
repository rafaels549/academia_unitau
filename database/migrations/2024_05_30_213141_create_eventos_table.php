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
Schema::create('eventos', function (Blueprint $table) {
$table->id();
$table->date("date");
$table->time("time");

$table->boolean("confirm")->nullable();
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
Schema::dropIfExists('eventos');
}
};


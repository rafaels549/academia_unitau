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
            Schema::create('open_day_times', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger("open_day_id");
                $table->time("opening_time");
                $table->time("closing_time");
                $table->foreign('open_day_id')->references('id')->on('open_days')->onDelete('cascade');
                $table->timestamps();
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('open_day_times');
        }
    };

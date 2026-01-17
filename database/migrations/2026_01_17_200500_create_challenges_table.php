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
        Schema::create('challenges', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('frequency')->default('daily');
            $table->boolean('is_public')->default(false);
            $table->date('start_date');
            $table->date('end_date');
            $table->time('checkin_deadline');
            $table->bigInteger('price_per_miss');
            $table->bigInteger('price_early_leave');
            $table->integer('coins_per_checkin');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('challenges');
    }
};

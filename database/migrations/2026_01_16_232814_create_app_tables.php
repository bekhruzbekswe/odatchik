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
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('type'); // USD, UZS, COIN
            $table->bigInteger('balance')->default(0);
            $table->timestamps();

            $table->unique(['user_id', 'type']);
        });

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

        Schema::create('checkins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('challenge_id')->constrained()->cascadeOnDelete();
            $table->date('due_date');
            $table->string('status')->default('pending');
            $table->string('proof_data')->nullable();
            $table->timestamps();
        });

        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wallet_id')->constrained()->cascadeOnDelete();
            $table->bigInteger('amount'); // +/- value
            $table->string('description');
            $table->foreignId('checkin_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('payment_id')->nullable(); // For Payme/Click
            $table->timestamps();
        });

        Schema::create('users_challenges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('role'); // owner, admin, member
            $table->foreignId('challenge_id')->constrained()->cascadeOnDelete();
            $table->timestamp('agreed_to_terms_at')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });

        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('provider'); // click, payme, uzum
            $table->string('token'); // The recurring payment token
            $table->string('card_mask')->nullable(); // e.g. 8600 **** **** 1234
            $table->boolean('is_default')->default(false); // Charge this one for penalties
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('checkins');
        Schema::dropIfExists('users_challenges');
        Schema::dropIfExists('challenges');
        Schema::dropIfExists('wallets');
    }
};

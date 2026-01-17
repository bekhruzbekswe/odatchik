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
        Schema::table('users', function (Blueprint $table) {
            // Drop old google_id unique constraint
            $table->dropUnique('users_google_id_unique');

            // Drop google_id and avatar columns
            $table->dropColumn(['google_id', 'avatar']);

            // Make password nullable for OAuth users
            $table->string('password')->nullable()->change();

            // Add OAuth fields
            $table->string('oauth_provider')->nullable();
            $table->string('oauth_id')->nullable();
            $table->unique(['oauth_provider', 'oauth_id']);

            // Add telegram field
            $table->string('telegram_id')->nullable()->unique();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop new columns
            $table->dropUnique(['oauth_provider', 'oauth_id']);
            $table->dropColumn(['oauth_provider', 'oauth_id']);
            $table->dropUnique('users_telegram_id_unique');
            $table->dropColumn('telegram_id');

            // Restore password as not nullable
            $table->string('password')->nullable(false)->change();

            // Restore google_id and avatar
            $table->string('google_id')->nullable();
            $table->string('avatar')->nullable();
            $table->unique('google_id');
        });
    }
};

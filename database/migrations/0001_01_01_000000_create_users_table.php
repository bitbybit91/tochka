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
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->string('username', 16)->unique();
            $table->string('passphrase_hash');
            $table->timestamp('registration_date');
            $table->timestamp('last_login_date')->nullable();
            $table->string('language', 10)->default('en');
            $table->string('currency', 10)->default('USD');
            $table->string('bitcoin')->nullable();
            $table->text('bitcoin_multisig_public_key')->nullable();
            $table->string('bitmessage')->nullable();
            $table->string('tox')->nullable();
            $table->string('email')->nullable();
            $table->text('pgp')->nullable();
            $table->string('description', 140)->nullable();
            $table->text('long_description')->nullable();
            $table->uuid('invite_code')->unique();
            $table->boolean('two_factor_authentication')->default(false);
            $table->boolean('has_top_banner')->default(false);
            $table->boolean('banned')->default(false);
            $table->boolean('possible_scammer')->default(false);
            $table->boolean('vacation_mode')->default(false);
            $table->boolean('has_avatar')->default(false);
            $table->boolean('is_seller')->default(false);
            $table->boolean('is_trusted_seller')->default(false);
            $table->boolean('is_tester')->default(false);
            $table->boolean('is_admin')->default(false);
            $table->boolean('is_staff')->default(false);
            $table->rememberToken();
            $table->timestamps();
            
            $table->index('username');
            $table->index('is_seller');
            $table->index('is_admin');
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};

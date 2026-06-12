<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('email_otp_code_hash', 64)->nullable()->after('login_magic_token_expires_at');
            $table->timestamp('email_otp_expires_at')->nullable()->after('email_otp_code_hash');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'email_otp_code_hash',
                'email_otp_expires_at',
            ]);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // 1) Add email_verified_at (for MustVerifyEmail)
            if (! Schema::hasColumn('users', 'email_verified_at')) {
                $table->timestamp('email_verified_at')
                    ->nullable()
                    ->after('email');
            }
            // 2) Add remember_token (for “remember me”)
            if (! Schema::hasColumn('users', 'remember_token')) {
                $table->string('remember_token', 100)
                    ->nullable()
                    ->after('email_verified_at');
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['email_verified_at', 'remember_token']);
        });
    }
};

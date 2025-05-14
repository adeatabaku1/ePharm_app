<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // 1) Add email_verified_at for Laravel's MustVerifyEmail
            if (! Schema::hasColumn('users', 'email_verified_at')) {
                $table->timestamp('email_verified_at')->nullable()->after('email');
            }

            // 2) Ensure remember_token exists
            if (! Schema::hasColumn('users', 'remember_token')) {
                $table->string('remember_token', 100)->nullable()->after('email_verified_at');
            }

            // 3) (Optional) Drop tenant_id and user_type if unused
            if (Schema::hasColumn('users', 'tenant_id')) {
                $table->dropColumn('tenant_id');
            }
            if (Schema::hasColumn('users', 'user_type')) {
                $table->dropColumn('user_type');
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Reverse the above
            if (Schema::hasColumn('users', 'email_verified_at')) {
                $table->dropColumn('email_verified_at');
            }
            if (Schema::hasColumn('users', 'remember_token')) {
                $table->dropColumn('remember_token');
            }
            // Re-add dropped columns (optional):
            $table->unsignedBigInteger('tenant_id')->nullable()->after('password');
            $table->string('user_type')->nullable()->after('tenant_id');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // 1) Add pharmacy_id FK
            $table->unsignedBigInteger('pharmacy_id')->nullable()->after('phone');
            $table->foreign('pharmacy_id')
                ->references('id')->on('pharmacies')
                ->onDelete('set null');

            // 2) Add a role column
            $table->enum('role', [
                'super_admin',
                'pharmacy_owner',
                'pharmacist',
                'patient'
            ])->default('patient')->after('pharmacy_id');

            // 3) (Optional) If you still have tenant_id and no longer need it,
            //    you could drop it here:
            // $table->dropColumn('tenant_id');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // revert in reverse order
            $table->dropForeign(['pharmacy_id']);
            $table->dropColumn('pharmacy_id');
            $table->dropColumn('role');
            // $table->unsignedBigInteger('tenant_id')->nullable(); // if you dropped it
        });
    }
};

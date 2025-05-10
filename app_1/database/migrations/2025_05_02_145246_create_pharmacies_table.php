<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('pharmacies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text   ('arbk_name');
            $table->text   ('trade_name')->nullable();
            $table->text   ('business_type')->nullable();
            $table->text   ('registration_num')->unique();
            $table->text   ('business_num')->nullable();
            $table->text   ('fiscal_num')->nullable();
            $table->integer('employee_count')->nullable();
            $table->date   ('registration_date')->nullable();
            $table->text   ('municipality')->nullable();
            $table->text   ('address')->nullable();
            $table->text   ('phone')->nullable();
            $table->text   ('email')->nullable();
            $table->decimal('capital',15,2)->nullable();
            $table->text   ('arbk_status')->nullable();
            $table->timestampTz('verified_at')->nullable();
            $table->timestampsTz();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pharmacies');
    }


};

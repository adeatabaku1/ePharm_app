<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up() {
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade'); // Pharmacy
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Patient
            $table->foreignId('doctor_id')->nullable()->constrained('users')->onDelete('set null'); // Doctor who approves it
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('prescriptions');
    }
};

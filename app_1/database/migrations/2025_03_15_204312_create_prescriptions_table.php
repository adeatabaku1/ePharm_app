<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up() {
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained()->onDelete('cascade');
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->text('diagnosis');
            $table->text('notes')->nullable();
            $table->boolean('is_sent_to_patient')->default(false);
            $table->foreignId('discount_code_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
        });

    }

    public function down() {
        Schema::dropIfExists('prescriptions');
    }
};

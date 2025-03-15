<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up() {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade'); // Pharmacy
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Patient who placed the order
            $table->foreignId('prescription_id')->nullable()->constrained()->onDelete('set null'); // Linked prescription
            $table->enum('status', ['pending', 'processed', 'shipped', 'delivered'])->default('pending');
            $table->decimal('total_price', 10, 2);
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('orders');
    }
};

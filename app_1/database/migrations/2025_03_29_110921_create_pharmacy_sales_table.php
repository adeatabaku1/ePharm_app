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
        Schema::create('pharmacy_sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pharmacy_id')->constrained('tenants')->onDelete('cascade');
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->decimal('total_amount', 10, 2);
            $table->boolean('credit_awarded')->default(false);
            $table->timestamp('sale_date');
            $table->foreignId('processed_by')->constrained('users')->onDelete('set null')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pharmacy_sales');
    }
};

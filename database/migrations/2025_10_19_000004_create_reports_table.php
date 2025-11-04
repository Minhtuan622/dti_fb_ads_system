<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->timestamp('start_at')->nullable();
            $table->timestamp('end_at')->nullable();
            $table->decimal('revenue', 15, 2)->default(0);
            $table->decimal('spend', 15, 2)->default(0);
            $table->decimal('catse_cost', 15, 2)->default(0);
            $table->decimal('expected_revenue', 15, 2)->default(0);
            $table->decimal('expected_profit', 15, 2)->default(0);
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('reports');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('ads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('facebook_account_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('facebook_page_id')->nullable()->constrained()->nullOnDelete();
            $table->string('ad_id')->index();
            $table->string('post_id')->nullable()->index();
            $table->string('status')->default('active');
            $table->decimal('spend', 15, 2)->default(0);
            $table->unsignedBigInteger('impressions')->default(0);
            $table->unsignedBigInteger('clicks')->default(0);
            $table->timestamps();
            $table->unique(['ad_id']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('ads');
    }
};
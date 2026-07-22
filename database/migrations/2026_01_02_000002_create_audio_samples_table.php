<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audio_samples', function (Blueprint $table): void {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            // FK wewnątrz modułu; usunięcie kategorii → próbka bez kategorii
            $table->foreignId('category_id')->nullable()->constrained('audio_categories')->nullOnDelete();
            $table->text('description')->nullable();
            // media_id: referencja do Media BEZ FK cross-module (§0.8) — odczyt przez MediaLibrary
            $table->unsignedBigInteger('media_id');
            $table->unsignedInteger('order')->default(0);
            $table->string('status')->default('draft')->index();
            $table->timestamp('published_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audio_samples');
    }
};

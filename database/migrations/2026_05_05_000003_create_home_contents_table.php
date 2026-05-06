<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('home_contents', function (Blueprint $table) {
            $table->id();
            $table->string('hero_badge')->nullable();
            $table->string('hero_title');
            $table->text('hero_subtitle')->nullable();
            $table->string('hero_image_url')->nullable();
            $table->string('hero_cta_text')->nullable();
            $table->string('hero_cta_url')->nullable();
            $table->string('destinations_badge')->nullable();
            $table->string('destinations_title')->nullable();
            $table->text('destinations_subtitle')->nullable();
            $table->string('packages_badge')->nullable();
            $table->string('packages_title')->nullable();
            $table->text('packages_subtitle')->nullable();
            $table->string('experiences_badge')->nullable();
            $table->string('experiences_title')->nullable();
            $table->text('experiences_subtitle')->nullable();
            $table->string('stories_badge')->nullable();
            $table->string('stories_title')->nullable();
            $table->text('stories_subtitle')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('home_contents');
    }
};

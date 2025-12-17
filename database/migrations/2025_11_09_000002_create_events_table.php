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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_title_id')->constrained('event_titles')->onDelete('cascade');
            $table->string('site');
            $table->string('event_id');
            $table->string('category')->nullable();
            $table->string('artist')->nullable();
            $table->string('img')->nullable();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->string('location')->nullable();
            $table->string('link')->nullable();
            $table->foreignId('city_id')->nullable()->constrained('cities')->onDelete('set null');
            $table->string('source')->nullable();
            $table->text('description')->nullable();
            $table->string('price')->nullable();
            $table->boolean('is_active')->default(1);
            $table->text('debug_html')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['site', 'event_id']);
            $table->index('start_date');
            $table->index('city_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};

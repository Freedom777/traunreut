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
        Schema::create('cities', function (Blueprint $table) {
            $table->collation = 'utf8mb4_0900_as_cs';
            $table->id();
            $table->string('zip_code')->nullable()->index();
            $table->string('name');
            $table->string('state_code', 2)->nullable();
            $table->foreign('state_code')->references('code')->on('states');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cities');
    }
};

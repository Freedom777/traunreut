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
        Schema::table('events_ru', function (Blueprint $table) {
            $table->string('title_de')->after('id');
            $table->renameColumn('title', 'title_ru');
            $table->string('title_ru')->nullable()->default(null)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events_ru', function (Blueprint $table) {
            $table->string('title')->nullable(false)->change();
            $table->renameColumn('title_ru', 'title');
            $table->removeColumn('title_de');
        });
    }
};

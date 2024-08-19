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
        Schema::create('pageviews', function (Blueprint $table) {
            $table->id();
            $table->string('path');
            $table->string('method', 12);
            $table->text('useragent');
            $table->string('visitorid');
            $table->text('referer')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pageviews');
    }
};

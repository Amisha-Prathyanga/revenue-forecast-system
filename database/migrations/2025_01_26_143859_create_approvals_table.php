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
        Schema::create('approvals', function (Blueprint $table) {
            $table->id();
            $table->string('objectid'); // Unique identifier from API data
            $table->boolean('ds_approved')->default(false);
            $table->boolean('district_approved')->default(false);
            $table->boolean('national_approved')->default(false);
            $table->timestamps();

            // Ensure objectid is unique to prevent duplicates
            $table->unique('objectid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approvals');
    }
};

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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('accMngr_id');
            $table->string('client_name');
            $table->string('industry_sector');
            $table->string('controlling_ministry')->nullable();
            $table->string('ministry_contact')->nullable();
            $table->string('key_client_contact_name')->nullable();
            $table->string('key_client_contact_designation')->nullable();
            $table->text('key_projects_or_sales_activity')->nullable();
            $table->string('account_servicing_persons_initials')->nullable();
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('accMngr_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};

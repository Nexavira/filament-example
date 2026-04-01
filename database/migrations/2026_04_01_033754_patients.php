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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            
            $table->string('full_name');
            $table->string('nik', 16)->nullable();
            $table->string('phone_number')->nullable();
            $table->date('date_of_birth');
            $table->enum('gender', ['M', 'F']);
            $table->text('address')->nullable();

            $table->integer('is_active')->default(1);
            $table->integer('version')->default(0);
            $table->userFootprints();
            $table->epochTimestamps();
            $table->epochSoftDeletes();

            $table->uniqueSoftDelete(['nik']);
            
            $table->index(['full_name', 'nik', 'phone_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};

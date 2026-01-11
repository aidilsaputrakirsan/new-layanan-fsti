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
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_id')->constrained()->onDelete('cascade');
            $table->string('tracking_number', 20)->unique();
            $table->string('email');
            $table->json('data');
            $table->enum('status', ['pending', 'in_review', 'needs_revision', 'approved', 'rejected', 'completed'])
                ->default('pending');
            $table->text('admin_notes')->nullable();
            $table->timestamps();
            
            $table->index('tracking_number');
            $table->index('status');
            $table->index('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};

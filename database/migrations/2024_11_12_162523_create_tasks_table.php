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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\User::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->boolean('is_completed')->default(false);
            $table->timestamps();

            // ---- Appended production-ready fields ----
            // Time & scheduling
            $table->timestamp('start_date')->nullable()->index();
            $table->timestamp('due_date')->nullable()->index();
            $table->timestamp('completed_at')->nullable()->index();
            $table->timestamp('reminder_at')->nullable()->index();

            // Categorization
            $table->string('priority', 20)->default('normal')->index(); // e.g., low, normal, high, urgent
            $table->string('status', 30)->default('pending')->index(); // e.g., pending, in_progress, completed, blocked

            // Collaboration
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();

            // System/metadata
            $table->boolean('archived')->default(false)->index();
            $table->softDeletes();
            // ---- End appended fields ----
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};

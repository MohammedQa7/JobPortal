<?php

use App\Enum\ProposalStatus;
use App\Enum\ProposalTypes;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('proposals', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->foreignId('job_post_id')->constrained('job_posts')->cascadeOnDelete();
            $table->unsignedBigInteger('client_id')->nullable();
            $table->unsignedBigInteger('job_seeker_id');
            $table->foreign('client_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('job_seeker_id')->references('id')->on('users')->cascadeOnDelete();
            $table->text('cover_letter');
            $table->string('bid');
            $table->string('duration');
            $table->boolean('is_reviewed')->default(false);
            $table->boolean('is_accepted')->nullable()->default(false);
            $table->timestamp('editing_ends_at')->nullable();
            $table->timestamp('deadline')->nullable();
            $table->string('status')->default(ProposalStatus::PENDING->value);
            $table->enum('type', ['proposal', 'offer'])->default(ProposalTypes::PROPOSAL->value);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proposals');
    }
};
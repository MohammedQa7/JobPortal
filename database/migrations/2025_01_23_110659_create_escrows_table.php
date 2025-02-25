<?php

use App\Enum\EscrowStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('escrows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposal_id')->constrained('proposals')->cascadeOnDelete();
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('job_seeker_id');
            $table->foreign('client_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('job_seeker_id')->references('id')->on('users')->cascadeOnDelete();
            $table->decimal('amount');
            $table->enum('status', [EscrowStatus::PENDING->value, EscrowStatus::RESERVED->value])->default(EscrowStatus::PENDING->value);
            $table->timestamp('offer_accepted_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('escrows');
    }
};
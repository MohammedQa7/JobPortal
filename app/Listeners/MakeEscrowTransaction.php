<?php

namespace App\Listeners;

use App\Enum\EscrowStatus;
use App\Enum\JobPostStatus;
use App\Events\TransferEscrowMoney;
use App\Models\Escrow;
use App\Models\JobPost;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class MakeEscrowTransaction
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TransferEscrowMoney $event): void
    {
        $event->proposal->load([
            'job' => function ($query) {
                $query->withoutGlobalScopes();
            }
        ]);



        match ($event->proposal->job->status) {
            JobPostStatus::COMPLETED->value => self::TransferMoneyFromClientToSeeker($event),
            JobPostStatus::CANCELED->value => self::TransferMoneyBackToClient($event),
        };

    }


    protected function TransferMoneyFromClientToSeeker($event)
    {
        $escrow = Escrow::where('proposal_id', $event->proposal->id)
            ->where('status', EscrowStatus::PENDING->value)->firstOrFail();

        $job_seeker = User::where('id', $escrow->job_seeker_id)->firstOrFail();

        $job_seeker->update([
            'balance' => $job_seeker->balance + $escrow->amount,
        ]);

        $escrow->update([
            'status' => EscrowStatus::RESERVED->value,
        ]);
    }

    protected function TransferMoneyBackToClient($event)
    {
        $escrow = Escrow::where('proposal_id', $event->proposal->id)
            ->where('status', EscrowStatus::PENDING->value)->firstOrFail();

        $client = User::findOrFail($escrow->client_id);
        $client->update([
            'balance' => $client->balance + $escrow->amount,
        ]);

        $escrow->delete();


    }
}
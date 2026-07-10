<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ShowSeat;
use Carbon\Carbon;

class ReleaseExpiredSeats extends Command
{
    protected $signature = 'seats:release-expired';

    protected $description = 'Release expired locked seats';


    public function handle()
    {

        $expiredSeats = ShowSeat::where('status', ShowSeat::LOCKED)
            ->whereNotNull('locked_until')
            ->where('locked_until', '<', now())
            ->get();



        foreach ($expiredSeats as $seat) {

            $seat->update([

                'status' => ShowSeat::AVAILABLE,

                'locked_by' => null,

                'locked_until' => null,

            ]);

        }


        $this->info(
            $expiredSeats->count().' seats released.'
        );

    }
}
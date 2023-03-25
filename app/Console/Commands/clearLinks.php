<?php

namespace App\Console\Commands;

use App\Models\ReservationLink;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;

class clearLinks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:clearlinks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Nettoie les liens passés';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    // delete outdated links
    public function handle()
    {
        $now = Carbon::now();
        $outdatedLinks = ReservationLink::whereHas('reservation', function(Builder $query) use ($now) {
            $query->where('departuredate', '<', $now )
                ->where('confirmed', true)
                ->orWhere('departuredate', '<', $now->subYear());
        })->delete();

        $this->info($outdatedLinks." liens supprimés");
         
    }
}

<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Company;
use Illuminate\Console\Command;

class DeactivateExpiredRestaurants extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'restaurants:deactivate-expired';
    

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deactivate restaurants whose expiry date has passed';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today();

        // fetch restaurant
        $restaurants = Company::where('expiry_date', '<', $today->toDateString())
                            ->where('status', config('constants.ACTIVE_RESTAURANT'))
                            ->get();

        // deactivate restaurant
        foreach ($restaurants as $restaurant) {
            $restaurant->status = config('constants.IN_ACTIVE_RESTAURANT');
            $restaurant->save();
        }

        $this->info('Expired restaurants deactivated successfully.');
    }
}

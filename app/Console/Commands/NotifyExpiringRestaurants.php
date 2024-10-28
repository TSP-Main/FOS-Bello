<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Company;
use Illuminate\Console\Command;
use App\Notifications\ExpiryNotification;
use Illuminate\Support\Facades\Notification;

class NotifyExpiringRestaurants extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'restaurants:notify-expiring';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify restaurant admin 7 days before their expiry date';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $targetDate = Carbon::today()->addDays(7);

        $restaurants = Company::where('expiry_date', '=', $targetDate->toDateString())
                            ->where('status', config('constants.ACTIVE_RESTAURANT'))
                            ->get();

        foreach ($restaurants as $restaurant) {
            $admins = User::where('role', 2)
                ->where('company_id', $restaurant->id)
                ->get();
                
            if ($admins) {
                Notification::send($admins, new ExpiryNotification($restaurant->id));
            }
        }

        $this->info('Notified admins of expiring restaurants successfully.');
    }
}

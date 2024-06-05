<?php

namespace App\Providers;

use App\Models\Companies\Company;
use App\Models\Healts\Health;
use App\Models\Messege;
use App\Models\Setting;
use App\Models\Users\Admin;
use App\Models\Users\Log;
use App\Models\Users\Member;
use App\Models\Users\Role;
use App\Models\Users\User;
use App\Observers\Companies\CompanyObserver;
use App\Observers\Healths\HealthObserver;
use App\Observers\Observer;
use App\Observers\Users\AdminObserver;
use App\Observers\Users\LogObserver;
use App\Observers\Users\MemberObserver;
use App\Observers\Users\RoleObserver;
use App\Observers\Users\UserObserver;
use Illuminate\Support\ServiceProvider;

class ObserverServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Users
        User::observe(UserObserver::class);
        Admin::observe(AdminObserver::class);
        Member::observe(MemberObserver::class);
        Log::observe(LogObserver::class);
        Role::observe(RoleObserver::class);

        // Users
        Company::observe(CompanyObserver::class);

        // Healt
        Health::observe(HealthObserver::class);

        // Commons
        Setting::observe(Observer::class);
        Messege::observe(Observer::class);
    }
}

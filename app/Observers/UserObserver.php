<?php

namespace App\Observers;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
class UserObserver
{
    /**
     * Handle the Pages "created" event.
     *
     * @param  \App\Models\Pages  $pages
     * @return void
     */
    public function created(User $user)
    {
        Cache::forget('users');
    }

    /**
     * Handle the Pages "updated" event.
     *
     * @param  \App\Models\Pages  $pages
     * @return void
     */
    public function updated(User $user)
    {
        Cache::forget('users');

    }

    /**
     * Handle the Pages "deleted" event.
     *
     * @param  \App\Models\Pages  $pages
     * @return void
     */
    public function deleted(User $user)
    {
        
        Cache::forget('users_delete');
        Cache::forget('users');
    }

    /**
     * Handle the Pages "restored" event.
     *
     * @param  \App\Models\Pages  $pages
     * @return void
     */
    public function restored(User $user)
    {
        Cache::forget('users');
    }

    /**
     * Handle the Pages "force deleted" event.
     *
     * @param  \App\Models\Pages  $pages
     * @return void
     */
    public function forceDeleted(User $user)
    {
        Cache::forget('users');
    }
}

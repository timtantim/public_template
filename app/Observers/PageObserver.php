<?php

namespace App\Observers;

use App\Models\Pages;
use Illuminate\Support\Facades\Cache;

class PageObserver
{
    /**
     * Handle the Pages "created" event.
     *
     * @param  \App\Models\Pages  $pages
     * @return void
     */
    public function created(Pages $pages)
    {
        Cache::forget('pages');
    }

    /**
     * Handle the Pages "updated" event.
     *
     * @param  \App\Models\Pages  $pages
     * @return void
     */
    public function updated(Pages $pages)
    {
        Cache::forget('pages');
    }

    /**
     * Handle the Pages "deleted" event.
     *
     * @param  \App\Models\Pages  $pages
     * @return void
     */
    public function deleted(Pages $pages)
    {
        Cache::forget('pages');
    }

    /**
     * Handle the Pages "restored" event.
     *
     * @param  \App\Models\Pages  $pages
     * @return void
     */
    public function restored(Pages $pages)
    {
        Cache::forget('pages');
    }

    /**
     * Handle the Pages "force deleted" event.
     *
     * @param  \App\Models\Pages  $pages
     * @return void
     */
    public function forceDeleted(Pages $pages)
    {
        Cache::forget('pages');
    }
}

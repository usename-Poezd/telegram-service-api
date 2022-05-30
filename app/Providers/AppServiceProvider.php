<?php

namespace App\Providers;

use danog\MadelineProto\API;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $uid =  Str::uuid()->toString();

        $this->app->singleton(API::class, function ($app) use (&$uid) {
            if (request()->query('session_uid')) {
                $uid = request()->query('session_uid');
            }

            $service = (new API(config('telegram.session') . '.' . $uid, config('telegram.settings')));

            return $service;
        });
    }
}

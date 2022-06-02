<?php

namespace App\Providers;

use danog\MadelineProto\API;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
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

            $sessionFile = config('telegram.session') . '.' . $uid;

            $path = storage_path("app/telegram/$uid");
            if(!File::isDirectory($path)){
                File::makeDirectory($path, 0777, true, true);
            }

            $service = (new API(storage_path("app/telegram/$uid/$sessionFile"), config('telegram.settings')));

            return $service;
        });
    }
}

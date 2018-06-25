<?php

namespace Rzy\Smscode;

use Illuminate\Support\ServiceProvider;

class SmsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([__DIR__.'/database/migrations' => database_path('migrations')]);
        $this->publishes([
        __DIR__.'/config/sms.php' => config_path('sms.php'),
    ]);
    }

    public function register()
    {

    }
}
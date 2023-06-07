<?php

namespace Tech101\Webhook;

use Illuminate\Support\ServiceProvider;

class WebhookServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
    }

    public function register()
    {
        $this->publishes([__DIR__ . '/config/git.php' => config_path('git.php')]);
    }
}

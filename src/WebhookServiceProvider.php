<?php

namespace Tech101\Webhook;

use Illuminate\Support\ServiceProvider;

class WebhookServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');

        $this->publishes([__DIR__ . '/config/gitlog.php' => config_path('gitlog.php')], 'config');
        $this->app->make('config')->set('logging.channels.gitlog', config('gitlog.gitlog'));
    }

    public function register()
    {
        $this->publishes([__DIR__ . '/config/git.php' => config_path('git.php')]);
    }
}

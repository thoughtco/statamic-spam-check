<?php

namespace Thoughtco\StatamicSpamCheck;

use Statamic\Providers\AddonServiceProvider;

class ServiceProvider extends AddonServiceProvider
{
    protected $listen = [
        \Statamic\Events\FormSubmitted::class => [Listeners\FormSubmittedListener::class],
    ];

    public function boot()
    {
        parent::boot();

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/statamic-spam-check.php' => config_path('statamic-spam-check.php'),
            ], 'statamic-spam-check');
        }
    }
}

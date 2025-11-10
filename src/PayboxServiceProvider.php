<?php

namespace PixellWeb\Paybox;

use App\Http\Middleware\TrimStrings;
use Illuminate\Support\ServiceProvider;
use PixellWeb\Paybox\app\Console\Commands\Test;


class PayboxServiceProvider extends ServiceProvider
{

    protected $commands = [
        Test::class,
    ];


    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;


    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->addCustomConfigurationValues();

        TrimStrings::skipWhen(fn ($request) => $request->url() === route(config('paybox.url_repondre_a')));
    }

    public function addCustomConfigurationValues()
    {
        // add filesystems.disks for the log viewer
        config([
            'logging.channels.'.config('paybox.logging_channel') => [
                'driver' => 'single',
                'path' => storage_path('logs/'.config('paybox.logging_channel').'.log'),
                'level' => 'debug',
            ]
        ]);

    }


    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/config/paybox.php', 'paybox'
        );

        // register the artisan commands
        $this->commands($this->commands);
    }
}

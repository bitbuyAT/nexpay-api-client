<?php

namespace bitbuyAT\Nexpay;

use GuzzleHttp\Client as HttpClient;
use Illuminate\Support\ServiceProvider;

class NexpayServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     */
    public function boot()
    {
        $this->registerClient();
    }

    /**
     * Register services.
     */
    public function register()
    {
        $configPath = __DIR__.'/../config/nexpay.php';
        $this->mergeConfigFrom($configPath, 'nexpay');
    }

    protected function registerClient(): void
    {
        $this->app->singleton(Contracts\Client::class, function () {
            $config = $this->app->make('config')->get('nexpay', []);

            return new Client(
                new HttpClient(),
                $config['key'] ?? null,
                $config['message_secret'] ?? null,
                $config['outgoing_secret'] ?? null,
            );
        });
    }
}

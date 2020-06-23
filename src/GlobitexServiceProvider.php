<?php

namespace bitbuyAT\Globitex;

use GuzzleHttp\Client as HttpClient;
use Illuminate\Support\ServiceProvider;

class GlobitexServiceProvider extends ServiceProvider
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
        $configPath = __DIR__.'/../config/globitex.php';
        $this->mergeConfigFrom($configPath, 'globitex');
    }

    protected function registerClient(): void
    {
        $this->app->singleton(Contracts\Client::class, function () {
            $config = $this->app->make('config')->get('globitex', []);

            return new Client(
                new HttpClient(),
                $config['key'] ?? null,
                $config['secret'] ?? null,
                $config['customer_id'] ?? null
            );
        });
    }
}

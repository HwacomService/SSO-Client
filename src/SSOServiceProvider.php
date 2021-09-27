<?php

namespace Hwacom\ClientSso;

use Illuminate\Support\ServiceProvider;

class SSOServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

        $this->loadRoutesFrom(__DIR__ . '/routes/web.php' . '');

        /** @var Router $router */
        $router = $this->app['router'];
        $router->pushMiddlewareToGroup('web', \App\Http\Middleware\SSOAuthenticated::class,);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerPublishables();
    }

    private function registerPublishables()
    {
        $basePath = __DIR__;

        $arrPublishable = [
            'config' => [
                "$basePath/publishable/config/sso.php" => config_path('sso.php'),
            ],
        ];

        foreach ($arrPublishable as $group => $paths) {
            $this->publishes($paths, $group);
        }
    }
}

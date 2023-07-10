<?php
namespace Microoculus\AllUploads\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use File;
class AllUploadsServiceProvider extends ServiceProvider {
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'AllUploads');
        // Route::mixin(new BookCryptRouteMethods);

        // // if ($this->app->runningInConsole()) {
        //     $this->publishes([
        //       __DIR__.'/../config/config.php' => config_path('all-uploads.php'),
        //     ], 'config');
        // // }


        $this->publishes([
            __DIR__.'/../config/config.php' => config_path('all-uploads.php'),
        ], 'config');


       if (empty(glob(database_path('migrations/*_create_uploads_table.php')))) {
        $this->publishes([
            __DIR__.'/../database/migrations/create_uploads_table.php.stub' => database_path('migrations/'.date('Y_m_d_His', time()).'_create_uploads_table.php'),
        ], 'migrations');
        }

        $this->publishes([
            __DIR__.'/../public' => public_path('vendor/all-uploads'),
        ], 'public');

        if (File::exists(__DIR__ . '/../helpers.php')) {
            require __DIR__ . '/../helpers.php';
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'all-uploads.php');
          // Register the main class to use with the facade
        //   $this->app->singleton('alluploads', function () {
        //     return new AllUploads;
        // });
    }
}
?>
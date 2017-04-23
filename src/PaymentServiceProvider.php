<?php namespace Sedehi\Payment;

use Illuminate\Support\ServiceProvider;
use Sedehi\Payment\Commands\ClearLogCommand;
use Sedehi\Payment\Commands\ClearUnsuccessfulTransactionsCommand;

class PaymentServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     * @return void
     */
    public function boot()
    {
        $this->publishes([__DIR__.'/config/payment.php' => config_path('payment.php')], 'config');
        $this->publishes([__DIR__.'/migrations/' => database_path('migrations')], 'migrations');
        if(file_exists(__DIR__.'/helper.php')){
            require_once __DIR__.'/helper.php';
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/config/payment.php', 'payment');
        $this->app->bind('sedehi::command.clear.logs', function($app){
            return new ClearLogCommand();
        });
        $this->app->bind('sedehi::command.clear.unsuccessful.transactions', function($app){
            return new ClearUnsuccessfulTransactionsCommand();
        });
        $this->commands([
                            'sedehi::command.clear.logs',
                            'sedehi::command.clear.unsuccessful.transactions',
                        ]);
        $this->app['payment'] = $this->app->share(function($app){
            return new Payment();
        });
    }

    /**
     * Get the services provided by the provider.
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
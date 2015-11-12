<?php namespace Sedehi\Payment;

use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot() {

        $this->package('sedehi/payment');

    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() {

        $this->app['payment'] = $this->app->share(function ($app) {

            return new Payment();
        });

    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides() {

        return array('payment');
    }

}

<?php

namespace Sedehi\Payment;

use DB;
use Exception;
use Sedehi\Payment\Providers\Mellat\Mellat;
use Sedehi\Payment\Providers\Parsian\Parsian;
use Sedehi\Payment\Providers\Pasargad\Pasargad;
use Sedehi\Payment\Providers\ZarinPal\ZarinPal;

class Payment
{

    protected $provider;
    protected $providerName;
    protected $config;
    protected $providers = [
        'mellat',
        'zarinpal',
    ];
    protected $transaction;

    public function __construct(){

        if(!extension_loaded('soap')) {
            throw new PaymentException('soap در سرور شما فعال نمی باشد', 1504);
        }
        $this->providerName = config('payment.default_provider');
        $this->config       = Paymentconfig::get($this->providerName);
    }

    private function setProvider($provider){

        $this->config = Paymentconfig::get($this->providerName);
        switch($provider) {
            case 'mellat':
                $this->provider = new Mellat($this->config);
                break;
            case 'parsian':
                $this->provider = new Parsian($this->config);
                break;
            case 'pasargad':
                $this->provider = new Pasargad($this->config);
                break;
            case 'zarinpal':
                $this->provider = new ZarinPal($this->config);
                break;
            default:
                throw new PaymentException('provider not found');
                break;
        }
    }

    public function __call($method, $args){

        if(in_array(strtolower($method), $this->providers)) {
            $this->providerName = strtolower($method);
        }else {
            throw new Exception('provider is not supported');
        }
        $this->setProvider($this->providerName);

        return $this;
    }

    public function provider($provider){

        if(in_array(strtolower($provider), $this->providers)) {
            $this->providerName = strtolower($provider);
        }else {
            throw new PaymentException('درگاه مورد نظر شما پشتیبانی نمی شود', 1506);
        }
        $this->setProvider($this->providerName);

        return $this;
    }

    public function callBackUrl($callBackUrl){

        $this->provider->callBackUrl = $callBackUrl;

        return $this;
    }

    public function description($description){

        $this->provider->description = $description;

        return $this;
    }

    public function amount($amount){

        $this->provider->amount = Currency::convert($amount, $this->providerName);

        return $this;
    }

    public function data($data){

        $this->provider->customData = $data;

        return $this;
    }

    public function request(){

        if(!$this->provider->callBackUrl) {
            $this->provider->callBackUrl = config('payment.callback_url');
        }

        return $this->provider->request();
    }

    public function requestResponse(){

        if(!$this->provider->callBackUrl) {
            $this->provider->callBackUrl = config('payment.callback_url');
        }

        return $this->provider->requestResponse();
    }

    public function verify(){

        return $this->provider->verify($this->transaction);
    }

    public function transaction(){

        return $this->provider->transaction($this->transaction);
    }

    public function reversal(){

        return $this->provider->reversal($this->transaction);
    }

}

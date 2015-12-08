<?php
/**
 * Created by PhpStorm.
 * User: Navid Sedehi
 * Date: 6/1/2015
 * Time: 4:53 PM
 */

namespace Sedehi\Payment;

use Carbon\Carbon;
use Config;
use DB;
use Exception;
use Input;
use Sedehi\Payment\Parsian\Parsian;
use Sedehi\Payment\Pasargad\Pasargad;
use SoapClient;
use Sedehi\Payment\Mellat\Mellat;
use Sedehi\Payment\Payline\Payline;
use Sedehi\Payment\PaymentDB;

class Payment
{

    protected $provider;
    protected $providerName;
    protected $config;
    protected $providers = ['jahanpay', 'mellat', 'parsian', 'payline'];
    protected $transaction;

    public function __construct()
    {
        if (!extension_loaded('soap')) {
            throw new Exception('soap is not enabled on your server');
        }
        $this->providerName = Config::get('payment::default_provider');
        $this->config       = PaymentConfig::get($this->providerName);
    }


    private function setProvider($provider)
    {
        switch ($provider) {
            case 'mellat':
                $this->provider = new Mellat($this->config);
                break;
            case 'parsian':
                $this->provider = new Parsian($this->config);
                break;
            case 'pasargad':
                $this->provider = new Pasargad($this->config);
                break;

            case 'payline':
                $this->provider = new Payline($this->config);
                break;

            default:
                throw new Exception('provider not found');
                break;
        }
    }

    public function __call($method, $args)
    {
        if (in_array(strtolower($method), $this->providers)) {
            $this->providerName = strtolower($method);
        } else {
            throw new Exception('provider is not supported');
        }
        $this->setProvider($this->providerName);

        return $this;
    }

    public function provider($provider)
    {
        if (in_array(strtolower($provider), $this->providers)) {
            $this->providerName = strtolower($provider);
        } else {
            throw new Exception('provider is not supported');
        }
        $this->setProvider($this->providerName);

        return $this;
    }

    public function callBackUrl($callBackUrl)
    {

        $this->provider->callBackUrl = $callBackUrl;

        return $this;
    }

    public function description($description)
    {

        $this->provider->description = $description;

        return $this;
    }

    public function amount($amount)
    {
        $this->provider->amount = Currency::convert($amount, $this->providerName);

        return $this;
    }

    public function orderId($orderId)
    {

        $this->provider->orderId = $orderId;

        return $this;
    }

    public function request()
    {
        if (!$this->provider->callBackUrl) {
            $this->provider->callBackUrl = Config::get('payment::callback_url');
        }

        return $this->provider->request();
    }

    public function verify()
    {

        return $this->provider->verify($this->transaction);
    }

    public function reversal()
    {
        return $this->provider->reversal($this->transaction);
    }

    public function clearLog($dateTime = null)
    {
        if (is_null($dateTime)) {
            $dateTime = Carbon::now();
        }

        return DB::table(Config::get('payment::table').'_log')->where('created_at', '<', $dateTime)->delete();
    }

    public function clearUnsuccessful($dateTime = null)
    {
        if (is_null($dateTime)) {
            $dateTime = Carbon::now();
        }

        return DB::table(Config::get('payment::table'))
                 ->where('status', 0)
                 ->where('created_at', '<', $dateTime)
                 ->delete();
    }


}
<?php
/**
 * Created by PhpStorm.
 * User: Navid Sedehi
 * Date: 6/1/2015
 * Time: 7:25 PM
 */

namespace Sedehi\Payment\JahanPay;


use Sedehi\Payment\PaymentAbstract;
use Sedehi\Payment\PaymentInterface;
use Redirect;
use Input;
use SoapFault;
use SoapClient;

class JahanPay extends PaymentAbstract implements PaymentInterface
{

    private $api;
    private $webserviceUrl;
    private $requestUrl;
    private $directWebserviceUrl;

    public $amount;
    public $description = '';
    public $callBackUrl;
    public  $customData  = [];

    public function __construct($config)
    {
        $this->terminalId          = $config['terminalId'];
        $this->webserviceUrl       = $config['webserviceUrl'];
        $this->requestUrl          = $config['requestUrl'];
        $this->directWebserviceUrl = $config['directWebserviceUrl'];
        $this->direct              = $config['direct'];
    }


    public function request()
    {
        $this->newTransaction($this->customData);

        if ($this->direct) {
            $response = $this->jpDirectRequest();
            if ($response['result'] == 1) {

                $this->authority = $response['au'];
                $this->transactionSetAuthority();

                return $response['form'];
            } else {
                $this->newLog($response['result'], JahanPayException::$errors[$response['result']]);
                throw new JahanPayException($response['result']);
            }
        }

        $response = $this->jpRequest();

        if ($response > 0) {
            $this->authority = $response;
            $this->transactionSetAuthority();

            return Redirect::to($this->requestUrl.$response);
        }

        $this->newLog($response, JahanPayException::$errors[$response]);
        throw new JahanPayException($response);
    }

    public function verify()
    {
        $this->getTransaction();

        if ($this->direct) {

            $response = $this->jpDirectVerify();

            if ($response['result'] == 1) {
                $this->reference = $response['bank_au'];
                $this->transactionSucceed();

                return $this->transaction;
            } else {
                $this->newLog($response, JahanPayException::$errors[$response]);
                throw new JahanPayException($response);
            }
        }


        $response = $this->jpVerify();
        if (!empty($response) && $response == 1) {

            $this->transactionSucceed();

            return $this->transaction;
        } else {
            $this->newLog($response, JahanPayException::$errors[$response]);
            throw new JahanPayException($response);
        }
    }

    public function reversal()
    {
        throw new PaymentException('درگاه واسط جهان پی از پشتیبانی وجه پشتیبانی نمی کند', 1501);
    }

    private function jpRequest()
    {
        $this->callBackUrl = $this->buildQuery($this->callBackUrl, ['transaction_id' => $this->transaction->id]);

        try {
            $client   = new SoapClient($this->webserviceUrl);
            $response = $client->requestpayment($this->api, $this->amount, $this->callBackUrl, $this->transaction->id,
                                                urlencode($this->description));
        } catch (SoapFault $e) {
            $this->newLog('SoapFault', $e->getMessage());
            throw $e;
        }

        return $response;
    }

    private function jpVerify()
    {

        try {
            $client   = new SoapClient($this->webserviceUrl);
            $response = $client->verification($this->api, $this->transaction->amount, $this->authority);
        } catch (SoapFault $e) {
            $this->newLog('SoapFault', $e->getMessage());
            throw $e;
        }

        return $response;
    }

    private function jpDirectRequest()
    {
        $this->callBackUrl = $this->buildQuery($this->callBackUrl, ['transaction_id' => $this->transaction->id]);
        try {
            $client   = new SoapClient($this->directWebserviceUrl);
            $response = $client->requestpayment($this->api, $this->amount, $this->callBackUrl, $this->transaction->id);
        } catch (SoapFault $e) {
            $this->newLog('SoapFault', $e->getMessage());
            throw $e;
        }

        return $response;
    }

    private function jpDirectVerify()
    {
        try {
            $client   = new SoapClient($this->directWebserviceUrl);
            $response = $client->verification($this->api, $this->transaction->amount, $this->transaction->authority,
                                              $this->transaction->id, $_POST + $_GET);
        } catch (SoapFault $e) {
            $this->newLog('SoapFault', $e->getMessage());
            throw $e;
        }

        return $response;
    }

    private function getTransaction()
    {
        if ($this->direct) {
            if (Input::has('transaction_id')) {
                $this->transactionFindById(Input::get('transaction_id'));
            } else {
                throw new PaymentException('تراکنش یافت نشد', 1500);
            }
        } else {
            $this->authority  = Input::get('au');
            $this->reference  = Input::get('au');
            $this->cardNumber = null;

            if (Input::has('transaction_id')) {
                $this->transactionFindById(Input::get('transaction_id'));
            } else {
                $this->transactionFindByAuthority($this->authority);
            }
        }
    }

}
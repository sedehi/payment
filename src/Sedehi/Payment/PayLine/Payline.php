<?php
/**
 * Created by PhpStorm.
 * User: Majid Ghaemifar
 * Date: 12/07/2015
 * Time: 7:25 PM
 */

namespace Sedehi\Payment\Payline;

use Input;
use Redirect;
use Sedehi\Payment\Payment;
use Sedehi\Payment\PaymentAbstract;
use Sedehi\Payment\PaymentException;
use Sedehi\Payment\PaymentInterface;

class Payline extends PaymentAbstract implements PaymentInterface
{

    private $api;
    private $requestUrl;
    private $secondRequestUrl;
    private $verifyRequestUrl;

    //private $reference;

    public $amount;
    public $description = '';
    public $callBackUrl;
    public $orderId;

    public function __construct($config)
    {
        $this->api              = $config['api'];
        $this->requestUrl       = $config['request_url'];
        $this->secondRequestUrl = $config['second_request_url'];
        $this->verifyRequestUrl = $config['verify_request_url'];
    }

    public function request()
    {
        $this->newTransaction();

        $callBackUrl = $this->buildQuery($this->callBackUrl, ['transaction_id' => $this->transaction->id]);

        $response = $this->send();

        if($response > 0 && is_numeric($response)){

            $this->authority = $response;
            $this->transactionSetAuthority();
            $go = $this->secondRequestUrl . $response;
            return Redirect::to($go);

        }else{

            $this->newLog($response, PaylineException::$errors['send'][$response]);
            throw new PaylineException('send',$response);

        }

    }

    public function verify()
    {
        $this->getTransaction();

        if($this->reference > 0 && is_numeric($this->reference))
        {
            $verifyResponse = $this->get();

        }else{

            $this->newLog(1502, 'شماره پیگیری دریافتی معتبر نیست');
            throw new PaymentException('شماره پیگیری دریافتی معتبر نیست' , 1502);
        }

        if(is_numeric($verifyResponse) && $verifyResponse == 1){

            $this->transactionSucceed();
            return dd($this->transaction);

        }else{

            $this->newLog($verifyResponse, PaylineException::$errors['get'][$verifyResponse]);
            throw new PaylineException('get',$verifyResponse);

        }

    }


    private function send()
    {
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$this->requestUrl);
        curl_setopt($ch,CURLOPT_POSTFIELDS,"api=$this->api&amount=$this->amount&redirect=" . urlencode($this->callBackUrl));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        $res = curl_exec($ch);
        curl_close($ch);
        return $res;
    }

    private function get()
    {
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$this->verifyRequestUrl);
        curl_setopt($ch,CURLOPT_POSTFIELDS,"api=$this->api&id_get=$this->authority&trans_id=$this->reference");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        $res = curl_exec($ch);
        curl_close($ch);
        return $res;
    }

    private function getTransaction()
    {
        $this->authority  = Input::get('id_get');
        $this->reference  = Input::get('trans_id');
        $this->cardNumber = null;

        if(Input::has('transaction_id'))
        {
            $this->transactionFindById(Input::get('transaction_id'),$this->authority);

        }else{

            $this->transactionFind($this->authority);
        }
    }

    public function reversal()
    {
        throw new PaymentException('این تابع توسط پی لاین پشتیبانی نمی شود',1501);
    }

}
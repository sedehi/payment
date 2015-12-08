<?php
/**
 * Created by PhpStorm.
 * User: Majid Ghaemifar
 * Date: 12/07/2015
 * Time: 7:25 PM
 */

namespace Sedehi\Payment\Payline;

use Redirect;
use Sedehi\Payment\Payment;
use Sedehi\Payment\PaymentAbstract;
use Sedehi\Payment\PaymentInterface;

class Payline extends PaymentAbstract implements PaymentInterface
{

    private $api;
    private $requestUrl;
    private $secondRequestUrl;
    private $verifyRequestUrl;

    public $amount;
    public $description = '';
    public $callBackUrl;
    public $orderId;
    public $authority;

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

        //dd($callBackUrl);

        $responseOne = $this->send($this->requestUrl , $this->api , $this->amount , urlencode($this->callBackUrl));

        if($responseOne > 0 && is_numeric($responseOne)){

            $this->authority = $responseOne;
            $this->transactionSetAuthority();
            $go = $this->secondRequestUrl . $responseOne;
            return Redirect::to($go);

        }else{

            $this->newLog($this->transaction->id, $responseOne, PaylineException::$errors['send'][$responseOne]);
            throw new PaylineException('send',$responseOne);

        }
/*
        $response = explode(',', $response->return);
        if ($response[0] == 0) {
            $this->authority = $response[1];
            $this->transactionSetAuthority();

            return new MellatRedirect($this->authority);
        }
        $this->newLog($this->transaction->id, $response[0], MellatException::$errors[$response[0]]);

        throw new MellatException($response[0]);
*/
    }

    public function verify($transaction)
    {dd($transaction);
        $this->authority  = Input::get('id_get');
        $this->reference  = Input::get('SaleReferenceId');
        $this->cardNumber = Input::get('CardHolderPan');
        if (Input::get('ResCode') == '0') {

            $verifyResponse = $this->bpVerifyRequest($this->transaction, $this->reference);
            if ($verifyResponse->return != '0') {
                $this->newLog($this->transaction->id, $verifyResponse->return,
                              MellatException::$errors[$verifyResponse->return]);
                throw new MellatException($verifyResponse->return);
            } else {
                $settleResponse = $this->bpSettleRequest($this->transaction, $this->reference);
                if ($settleResponse->return == '0' || $settleResponse->return == '45') {
                    $this->transactionSucceed();

                    return $this->transaction;
                } else {
                    $this->newLog($this->transaction->id, $settleResponse->return,
                                  MellatException::$errors[$settleResponse->return]);
                    throw new MellatException($settleResponse->return);
                }
            }
        }
        $this->newLog($this->transaction->id, Input::get('ResCode'), MellatException::$errors[Input::get('ResCode')]);
        throw new MellatException(Input::get('ResCode'));
    }


    private function send($url,$api,$amount,$redirect)
    {
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_POSTFIELDS,"api=$api&amount=$amount&redirect=$redirect");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        $res = curl_exec($ch);
        curl_close($ch);
        return $res;
    }

    private function get($url,$api,$trans_id,$id_get)
    {
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_POSTFIELDS,"api=$api&id_get=$id_get&trans_id=$trans_id");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        $res = curl_exec($ch);
        curl_close($ch);
        return $res;
    }

    public function reversal()
    {

    }

}
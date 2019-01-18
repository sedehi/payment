<?php

namespace Sedehi\Payment\Providers\Mellat;

use Sedehi\Payment\Payment;
use Sedehi\Payment\PaymentAbstract;
use Sedehi\Payment\PaymentInterface;
use SoapClient;
use Request;
use SoapFault;

class Mellat extends PaymentAbstract implements PaymentInterface
{

    private $terminalId;
    private $username;
    private $password;
    public  $customData  = [];
    public  $amount;
    public  $description = '';
    public  $callBackUrl;

    public function __construct($config)
    {
        $this->terminalId    = $config['terminalId'];
        $this->username      = $config['username'];
        $this->password      = $config['password'];
        $this->webserviceUrl = $config['webserviceUrl'];
    }

    public function request()
    {
        $this->newTransaction($this->customData);
        $response = $this->bpPayRequest();
        $response = explode(',', $response->return);
        if($response[0] == '0'){
            $this->authority = $response[1];
            $this->transactionSetAuthority();

            return MellatRedirect::to($this->authority);
        }
        $this->newLog($response[0], MellatException::$errors[$response[0]]);
        throw new MellatException($response[0]);
    }

    public function requestResponse()
    {
        $this->newTransaction($this->customData);
        $response = $this->bpPayRequest();
        $response = explode(',', $response->return);
        if($response[0] == '0'){
            $this->authority = $response[1];
            $this->transactionSetAuthority();

            return $this->transaction;
        }
        $this->newLog($response[0], MellatException::$errors[$response[0]]);
        throw new MellatException($response[0]);
    }

    public function verify()
    {
        $this->getTransaction();
        if(Request::get('ResCode') == '0'){
            $verifyResponse = $this->bpVerifyRequest();
            if($verifyResponse->return != '0'){
                $this->newLog($verifyResponse->return, MellatException::$errors[$verifyResponse->return]);
                throw new MellatException($verifyResponse->return);
            }else{
                $settleResponse = $this->bpSettleRequest();
                if($settleResponse->return == '0' || $settleResponse->return == '45'){
                    $this->transactionSucceed();

                    return $this->transaction;
                }else{
                    $this->newLog($settleResponse->return, MellatException::$errors[$settleResponse->return]);
                    throw new MellatException($settleResponse->return);
                }
            }
        }
        $this->newLog(Request::get('ResCode'), MellatException::$errors[Request::get('ResCode')]);
        throw new MellatException(Request::get('ResCode'));
    }

    public function transaction()
    {
        $this->authority  = Request::get('RefId');
        $this->reference  = Request::get('SaleReferenceId');
        $this->cardNumber = Request::get('CardHolderPan');
        if(Request::has('transaction_id')){
            $this->transactionFindByIdAndAuthority(Request::get('transaction_id'), $this->authority);
        }else{
            $this->transactionFindByAuthority($this->authority);
        }

        return $this->transaction;
    }

    public function reversal()
    {
        $this->getTransaction();
        $Response = $this->bpReversalRequest();
        if($Response->return != '0'){
            $this->newLog($Response->return, MellatException::$errors[$Response->return]);
            throw new MellatException($Response->return);
        }

        return $Response;
    }

    private function bpPayRequest()
    {
        $this->callBackUrl = $this->buildQuery($this->callBackUrl, ['transaction_id' => $this->transaction->id]);
        $fields            = [
            'terminalId'     => $this->terminalId,
            'userName'       => $this->username,
            'userPassword'   => $this->password,
            'orderId'        => $this->transaction->id,
            'amount'         => $this->transaction->amount,
            'localDate'      => date('Ymd'),
            'localTime'      => date('His'),
            'additionalData' => $this->transaction->description,
            'callBackUrl'    => $this->callBackUrl,
            'payerId'        => 0,
        ];
        try{
            $soap     = new SoapClient($this->webserviceUrl);
            $response = $soap->bpPayRequest($fields);
        }catch(SoapFault $e){
            $this->newLog('SoapFault', $e->getMessage());
            throw $e;
        }

        return $response;
    }

    private function bpVerifyRequest()
    {
        $fields = [
            'terminalId'      => $this->terminalId,
            'userName'        => $this->username,
            'userPassword'    => $this->password,
            'orderId'         => $this->transaction->id,
            'saleOrderId'     => $this->transaction->id,
            'saleReferenceId' => $this->reference,
        ];
        try{
            $soap     = new SoapClient($this->webserviceUrl);
            $response = $soap->bpVerifyRequest($fields);
        }catch(SoapFault $e){
            $this->newLog('SoapFault', $e->getMessage());
            throw $e;
        }

        return $response;
    }

    private function bpSettleRequest()
    {
        $fields = [
            'terminalId'      => $this->terminalId,
            'userName'        => $this->username,
            'userPassword'    => $this->password,
            'orderId'         => $this->transaction->id,
            'saleOrderId'     => $this->transaction->id,
            'saleReferenceId' => $this->reference,
        ];
        try{
            $soap     = new SoapClient($this->webserviceUrl);
            $response = $soap->bpSettleRequest($fields);
        }catch(SoapFault $e){
            $this->newLog('SoapFault', $e->getMessage());
            throw $e;
        }

        return $response;
    }

    private function bpInquiryRequest()
    {
        $soap = new SoapClient($this->request_url);
        $fields = [
            'terminalId'      => $this->terminalId,
            'userName'        => $this->username,
            'userPassword'    => $this->password,
            'orderId'         => $this->transaction->id,
            'saleOrderId'     => $this->transaction->id,
            'saleReferenceId' => $this->reference,
        ];
        try{
            $soap     = new SoapClient($this->webserviceUrl);
            $response = $soap->bpInquiryRequest($fields);
        }catch(SoapFault $e){
            $this->newLog('SoapFault', $e->getMessage());
            throw $e;
        }

        return $response;
    }

    private function bpReversalRequest()
    {
        $fields = [
            'terminalId'      => $this->terminalId,
            'userName'        => $this->username,
            'userPassword'    => $this->password,
            'orderId'         => $this->transaction->id,
            'saleOrderId'     => $this->transaction->id,
            'saleReferenceId' => $this->reference,
        ];
        try{
            $soap     = new SoapClient($this->webserviceUrl);
            $response = $soap->bpReversalRequest($fields);
        }catch(SoapFault $e){
            $this->newLog('SoapFault', $e->getMessage());
            throw $e;
        }

        return $response;
    }

    private function getTransaction()
    {
        $this->authority  = Request::get('RefId');
        $this->reference  = Request::get('SaleReferenceId');
        $this->cardNumber = Request::get('CardHolderPan');
        if(Request::has('transaction_id')){
            $this->transactionFindByIdAndAuthority(Request::get('transaction_id'), $this->authority);
        }else{
            $this->transactionFindByAuthority($this->authority);
        }
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: Navid Sedehi
 * Date: 6/1/2015
 * Time: 7:25 PM
 */

namespace Sedehi\Payment\Mellat;

use Sedehi\Payment\Payment;
use Sedehi\Payment\PaymentAbstract;
use Sedehi\Payment\PaymentInterface;
use SoapClient;

class Mellat extends PaymentAbstract implements PaymentInterface
{

    private $terminalId;
    private $username;
    private $password;

    public $amount;
    public $description = '';
    public $callBackUrl;
    public $orderId     = null;

    public function __construct($config)
    {
        $this->terminalId    = $config['terminalId'];
        $this->username      = $config['username'];
        $this->password      = $config['password'];
        $this->webserviceUrl = $config['webserviceUrl'];
    }

    public function request()
    {
        $this->newTransaction();
        $callBackUrl = $this->buildQuery($this->callBackUrl, ['transaction_id' => $this->transaction->id]);
        $fields      = [
            'terminalId'     => $this->terminalId,
            'userName'       => $this->username,
            'userPassword'   => $this->password,
            'orderId'        => $this->transaction->id,
            'amount'         => $this->transaction->amount,
            'localDate'      => date('Ymd'),
            'localTime'      => date('His'),
            'additionalData' => $this->transaction->description,
            'callBackUrl'    => $callBackUrl,
            'payerId'        => 0,
        ];

        try {
            $soap     = new SoapClient($this->webserviceUrl);
            $response = $soap->bpPayRequest($fields);
        } catch (SoapFault $e) {
            $this->newLog('SoapFault', $e->getMessage());
            throw $e;
        }
        $response = $soap->bpPayRequest($fields);

        $response = explode(',', $response->return);
        if ($response[0] == 0) {
            $this->authority = $response[1];
            $this->transactionSetAuthority();

            return new MellatRedirect($this->authority);
        }
        $this->newLog($response[0], MellatException::$errors[$response[0]]);

        throw new MellatException($response[0]);
    }

    public function verify()
    {
        $this->getTransaction();

        if (Input::get('ResCode') == '0') {

            $verifyResponse = $this->bpVerifyRequest();

            if ($verifyResponse->return != '0') {
                $this->newLog($verifyResponse->return, MellatException::$errors[$verifyResponse->return]);
                throw new MellatException($verifyResponse->return);
            } else {
                $settleResponse = $this->bpSettleRequest();
                if ($settleResponse->return == '0' || $settleResponse->return == '45') {
                    $this->transactionSucceed();

                    return $this->transaction;
                } else {
                    $this->newLog($settleResponse->return, MellatException::$errors[$settleResponse->return]);
                    throw new MellatException($settleResponse->return);
                }
            }
        }
        $this->newLog(Input::get('ResCode'), MellatException::$errors[Input::get('ResCode')]);
        throw new MellatException(Input::get('ResCode'));
    }

    public function reversal()
    {
        $this->getTransaction();

        $reversalResponse = $this->bpReversalRequest();

        if ($reversalResponse->return != '0') {
            $this->newLog($reversalResponse->return, MellatException::$errors[$reversalResponse->return]);
            throw new MellatException($reversalResponse->return);
        }

        return true;
    }

    private function bpVerifyRequest()
    {


        $fields = array(
            'terminalId'      => $this->terminalId,
            'userName'        => $this->username,
            'userPassword'    => $this->password,
            'orderId'         => $this->transaction->id,
            'saleOrderId'     => $this->transaction->id,
            'saleReferenceId' => $this->reference
        );

        try {
            $soap     = new SoapClient($this->webserviceUrl);
            $response = $soap->bpVerifyRequest($fields);
        } catch (SoapFault $e) {
            $this->newLog('SoapFault', $e->getMessage());
            throw $e;
        }

        return $response->return;
    }

    private function bpSettleRequest()
    {


        $fields = array(
            'terminalId'      => $this->terminalId,
            'userName'        => $this->username,
            'userPassword'    => $this->password,
            'orderId'         => $this->transaction->id,
            'saleOrderId'     => $this->transaction->id,
            'saleReferenceId' => $this->reference
        );

        try {
            $soap     = new SoapClient($this->webserviceUrl);
            $response = $soap->bpSettleRequest($fields);
        } catch (SoapFault $e) {
            $this->newLog('SoapFault', $e->getMessage());
            throw $e;
        }

        return $response->return;
    }

    private function bpInquiryRequest()
    {

        $soap = new SoapClient($this->request_url);

        $fields = array(
            'terminalId'      => $this->terminalId,
            'userName'        => $this->username,
            'userPassword'    => $this->password,
            'orderId'         => $this->transaction->id,
            'saleOrderId'     => $this->transaction->id,
            'saleReferenceId' => $this->reference
        );
        try {
            $soap     = new SoapClient($this->webserviceUrl);
            $response = $soap->bpInquiryRequest($fields);
        } catch (SoapFault $e) {
            $this->newLog('SoapFault', $e->getMessage());
            throw $e;
        }


        return $response;
    }

    private function bpReversalRequest()
    {

        $fields = array(
            'terminalId'      => $this->terminalId,
            'userName'        => $this->username,
            'userPassword'    => $this->password,
            'orderId'         => $this->transaction->id,
            'saleOrderId'     => $this->transaction->id,
            'saleReferenceId' => $this->reference
        );

        try {
            $soap     = new SoapClient($this->webserviceUrl);
            $response = $soap->bpReversalRequest($fields);
        } catch (SoapFault $e) {
            $this->newLog('SoapFault', $e->getMessage());
            throw $e;
        }

        return;
    }

    private function getTransaction()
    {
        $this->authority  = Input::get('RefId');
        $this->reference  = Input::get('SaleReferenceId');
        $this->cardNumber = Input::get('CardHolderPan');

        if (Input::has('transaction_id')) {
            $this->transactionFindById(Input::get('transaction_id'), $this->authority);
        } else {

            $this->transactionFind($this->authority);
        }
    }
}
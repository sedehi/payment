<?php
/**
 * Created by PhpStorm.
 * User: Majid Ghaemifar
 * Date: 12/07/2015
 * Time: 7:25 PM
 */

namespace Sedehi\Payment\Payline;

use Sedehi\Payment\Payment;
use Sedehi\Payment\PaymentAbstract;
use Sedehi\Payment\PaymentInterface;

class Payline extends PaymentAbstract implements PaymentInterface
{

    private $idGet;
    private $transId;

    private $terminalId = null;
    private $username = null;
    private $password = null;

    public $amount;
    public $description = '';
    public $callBackUrl;
    public $orderId     = null;
    public $authority = null;

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
        $callBackUrl = $this->buildQuery($this->callBackUrl, ['trans_id' => $this->transaction->id]);
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
            $this->newLog($this->transaction->id, 'SoapFault', $e->getMessage());
            throw $e;
        }
        $response = $soap->bpPayRequest($fields);

        $response = explode(',', $response->return);
        if ($response[0] == 0) {
            $this->authority = $response[1];
            $this->transactionSetAuthority();

            return new MellatRedirect($this->authority);
        }
        $this->newLog($this->transaction->id, $response[0], MellatException::$errors[$response[0]]);

        throw new MellatException($response[0]);
    }

    public function verify($transaction)
    {
        $this->authority  = Input::get('RefId');
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

    public function reversal()
    {
        $reversalResponse = $this->bpReversalRequest($this->transaction, $this->reference);

        if ($reversalResponse->return != '48') {
            $this->newLog($this->transaction->id, $reversalResponse->return,
                          MellatException::$errors[$reversalResponse->return]);
            throw new MellatException($reversalResponse->return);
        }

        return true;
    }

    private function bpVerifyRequest($transaction, $saleReferenceId)
    {


        $fields = array(
            'terminalId'      => $this->terminalId,
            'userName'        => $this->username,
            'userPassword'    => $this->password,
            'orderId'         => $transaction->id,
            'saleOrderId'     => $transaction->id,
            'saleReferenceId' => $saleReferenceId
        );

        try {
            $soap     = new SoapClient($this->webserviceUrl);
            $response = $soap->bpVerifyRequest($fields);
        } catch (SoapFault $e) {
            $this->newLog($this->transaction->id, 'SoapFault', $e->getMessage());
            throw $e;
        }

        return $response->return;
    }

    private function bpSettleRequest($transaction, $saleReferenceId)
    {


        $fields = array(
            'terminalId'      => $this->terminalId,
            'userName'        => $this->username,
            'userPassword'    => $this->password,
            'orderId'         => $transaction->id,
            'saleOrderId'     => $transaction->id,
            'saleReferenceId' => $saleReferenceId
        );

        try {
            $soap     = new SoapClient($this->webserviceUrl);
            $response = $soap->bpSettleRequest($fields);
        } catch (SoapFault $e) {
            $this->newLog($this->transaction->id, 'SoapFault', $e->getMessage());
            throw $e;
        }

        return $response->return;
    }

    private function bpInquiryRequest($transaction, $saleReferenceId)
    {

        $soap = new SoapClient($this->request_url);

        $fields   = array(
            'terminalId'      => $this->terminalId,
            'userName'        => $this->username,
            'userPassword'    => $this->password,
            'orderId'         => $transaction->id,
            'saleOrderId'     => $transaction->id,
            'saleReferenceId' => $saleReferenceId
        );
        $response = $soap->bpInquiryRequest($fields);

        return $response;
    }

    private function bpReversalRequest($transaction, $saleReferenceId)
    {


        $fields = array(
            'terminalId'      => $this->terminalId,
            'userName'        => $this->username,
            'userPassword'    => $this->password,
            'orderId'         => $transaction->id,
            'saleOrderId'     => $transaction->id,
            'saleReferenceId' => $saleReferenceId
        );

        try {
            $soap     = new SoapClient($this->webserviceUrl);
            $response = $soap->bpReversalRequest($fields);
        } catch (SoapFault $e) {
            $this->newLog($this->transaction->id, 'SoapFault', $e->getMessage());
            throw $e;
        }

        return;
    }
}
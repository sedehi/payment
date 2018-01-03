<?php
/**
 * Created by PhpStorm.
 * User: Navid Sedehi
 * Date: 6/1/2015
 * Time: 7:25 PM
 */

namespace Sedehi\Payment\Providers\ZarinPal;

use Request;
use Sedehi\Payment\PaymentAbstract;
use Sedehi\Payment\PaymentException;
use Sedehi\Payment\PaymentInterface;
use SoapClient;

class ZarinPal extends PaymentAbstract implements PaymentInterface
{

    private $merchantId;
    private $request_url;
    private $payment_url;

    public $amount;
    public $description = '';
    public $callBackUrl;
    public $zarin_gate  = null;
    public $customData  = [];

    public function __construct($config){
        $this->merchantId  = $config['merchantId'];
        $this->request_url = $config['request_url'];
        $this->payment_url = $config['payment_url'];
        $this->zarin_gate  = (isset($config['zarin_gate']) && $config['zarin_gate'] == true) ? '/ZarinGate' : null;
    }

    public function request(){

        $this->newTransaction($this->customData);
        $this->callBackUrl = $this->buildQuery($this->callBackUrl, ['transaction_id' => $this->transaction->id]);
        $response          = $this->paymentRequest();
        if($response->Status == 100 && strlen($response->Authority) == 36) {
            $this->authority = $response->Authority;
            $this->transactionSetAuthority();
            $go = $this->payment_url.$this->authority.$this->zarin_gate;

            return redirect()->to($go);
        }else {
            $this->newLog($response->Status, ZarinPalException::$errors[$response->Status]);
            throw new ZarinPalException($response->Status);
        }
    }

    public function requestResponse(){

        $this->newTransaction($this->customData);
        $this->callBackUrl = $this->buildQuery($this->callBackUrl, ['transaction_id' => $this->transaction->id]);
        $response          = $this->paymentRequest();
        if($response->Status == 100 && strlen($response->Authority) == 36) {
            $this->authority = $response->Authority;
            $this->transactionSetAuthority();

            return $this->transaction;
        }else {
            $this->newLog($response->Status, ZarinPalException::$errors[$response->Status]);
            throw new ZarinPalException($response->Status);
        }
    }

    public function verify(){

        $this->getTransaction();
        if(Request::get('Status') == 'OK') {
            $response = $this->paymentVerification();
            if($response->Status == 100) {
                $this->reference = $response->RefID;
                $this->transactionSucceed();

                return $this->transaction;
            }else {
                $this->newLog($response->Status, ZarinPalException::$errors[$response->Status]);
                throw new ZarinPalException($response->Status);
            }
        }else {
            $this->newLog(1508, 'تراکنش توسط کاربر لغو شده است');
            throw new PaymentException('تراکنش توسط کاربر لغو شده است', 1508);
        }
    }

    public function transaction(){

        $this->authority = Request::get('Authority');
        if(Request::has('transaction_id')) {
            $this->transactionFindByIdAndAuthority(Request::get('transaction_id'), $this->authority);
        }else {
            $this->transactionFindByAuthority($this->authority);
        }

        return $this->transaction;
    }

    private function paymentRequest(){

        $client = new SoapClient($this->request_url, ['encoding' => 'UTF-8']);
        $data   = [
            'MerchantID'  => $this->merchantId,
            'Amount'      => $this->amount,
            'CallbackURL' => $this->callBackUrl,
            'Description' => $this->description,
        ];
        if(!empty($this->customData)) {
            $data = array_merge($data, $this->customData);
        }
        $result = $client->PaymentRequest($data);

        return $result;
    }

    public function paymentVerification(){

        $client = new SoapClient($this->request_url, ['encoding' => 'UTF-8']);
        $result = $client->PaymentVerification([
                                                   'MerchantID' => $this->merchantId,
                                                   'Authority'  => $this->authority,
                                                   'Amount'     => $this->amount,
                                               ]);

        return $result;
    }

    private function getTransaction(){

        $this->authority  = Request::get('Authority');
        $this->cardNumber = null;
        if(Request::has('transaction_id')) {
            $this->transactionFindByIdAndAuthority(Request::get('transaction_id'), $this->authority);
        }else {
            $this->transactionFindByAuthority($this->authority);
        }
        $this->amount = $this->transaction->amount;
    }

    public function reversal(){

        throw new PaymentException('این تابع توسط زرین پال پشتیبانی نمی شود', 1507);
    }
}

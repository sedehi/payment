<?php

namespace Sedehi\Payment;

use Sedehi\Payment\Models\Log;
use Sedehi\Payment\Models\Transaction;

abstract class PaymentAbstract
{

    protected $transaction;
    protected $reference;
    protected $authority;
    protected $cardNumber;

    public function newLog($code, $message){

        $log                 = new Log();
        $log->transaction_id = $this->transaction->id;
        $log->code           = $code;
        $log->message        = $message;
        $log->save();
    }

    public function newTransaction(array $customData){

        $this->transaction              = new Transaction();
        $this->transaction->amount      = $this->amount;
        $this->transaction->gateway     = static::name;
        $this->transaction->status      = 0;
        $this->transaction->description = $this->description;
        $this->transaction->ip          = request()->getClientIp();
        foreach($customData as $field => $data) {
            $this->transaction->$field = $data;
        }
        $this->transaction->save();
    }

    public function buildQuery($url, array $query){
        $query        = http_build_query($query);
        $questionMark = strpos($url, '?');
        if(!$questionMark) {
            return "$url?$query";
        }else {
            return substr($url, 0, $questionMark + 1).$query."&".substr($url, $questionMark + 1);
        }
    }

    public function transactionSetAuthority(){

        $this->transaction->authority = $this->authority;
        $this->transaction->save();
    }

    public function transactionSucceed(){

        $this->transaction->reference   = $this->reference;
        $this->transaction->status      = 1;
        $this->transaction->card_number = $this->cardNumber;
        $this->transaction->save();

        return $this->transaction;
    }

    public function transactionFindById($id){

        $this->transaction = Transaction::find($id);
        if(is_null($this->transaction)) {
            throw new PaymentException('تراکنش یافت نشد', 1500);
        }
    }

    public function transactionFindByAuthority($authority){

        $this->transaction = Transaction::where('authority', $authority)->where('status', 0)->first();
        if(is_null($this->transaction)) {
            throw new PaymentException('تراکنش یافت نشد', 1500);
        }
    }

    public function transactionFindByIdAndAuthority($id, $authority){

        $this->transaction = Transaction::where('authority', $authority)->where('status', 0)->find($id);
        if(is_null($this->transaction)) {
            throw new PaymentException('تراکنش یافت نشد', 1500);
        }
    }
}

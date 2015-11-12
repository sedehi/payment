<?php
/**
 * Created by PhpStorm.
 * User: Navid
 * Date: 11/12/2015
 * Time: 5:15 PM
 */

namespace Sedehi\Payment;

use Carbon\Carbon;
use Config;
use DB;
use Request;

abstract class PaymentAbstract
{

    protected $transaction;

    private function getCallName()
    {
        $name = explode('\\', get_called_class());

        return strtolower(end($name));
    }

    public function newLog($transactionId, $code, $message)
    {
        DB::table(Config::get('payment::table').'_log')->insert(array(
                                                                    'transaction_id' => $transactionId,
                                                                    'code'           => $code,
                                                                    'message'        => $message,
                                                                    'updated_at'     => Carbon::now(),
                                                                    'created_at'     => Carbon::now()
                                                                ));
    }

    public function newTransaction()
    {
        $insertId = DB::table(Config::get('payment::table'))->insertGetId(array(
                                                                              'amount'      => $this->amount,
                                                                              'order_id'    => $this->orderId,
                                                                              'provider'    => $this->getCallName(),
                                                                              'currency'    => Currency::type($this->getCallName()),
                                                                              'status'      => 0,
                                                                              'description' => $this->description,
                                                                              'ip'          => Request::getClientIp(),
                                                                              'updated_at'  => Carbon::now(),
                                                                              'created_at'  => Carbon::now()
                                                                          ));

        $this->transaction = DB::table(Config::get('payment::table'))->find($insertId);
    }

    public function buildQuery($url, array $query)
    {
        $query        = http_build_query($query);
        $questionMark = strpos($url, '?');
        if (!$questionMark) {
            return "$url?$query";
        } else {
            return substr($url, 0, $questionMark + 1).$query."&".substr($url, $questionMark + 1);
        }
    }

    public function transactionSetAuthority()
    {
        return DB::table(Config::get('payment::table'))
                 ->where('id', $this->transaction->id)
                 ->update(['authority' => $this->authority]);
    }

    public function transactionSucceed()
    {
        return DB::table(Config::get('payment::table'))
                 ->where('id', $this->transaction->id)
                 ->update(['reference' => $this->reference, 'status' => 1, 'card_number' => $this->cardNumber]);
    }

}
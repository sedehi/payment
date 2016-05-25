<?php
/**
 * Created by PhpStorm.
 * User: Navid
 * Date: 11/12/2015
 * Time: 5:15 PM
 */

namespace Sedehi\Payment;

use Carbon\Carbon;
use DB;
use Request;
use Schema;

abstract class PaymentAbstract
{

    protected $transaction;
    protected $reference;
    protected $authority;
    protected $cardNumber;

    private function getCallName()
    {
        $name = explode('\\', get_called_class());

        return strtolower(end($name));
    }

    public function newLog($code, $message)
    {
        DB::table(config('payment.table').'_log')->insert([
                                                                    'transaction_id' => $this->transaction->id,
                                                                    'code'           => $code,
                                                                    'message'        => $message,
                                                                    'updated_at'     => Carbon::now(),
                                                                    'created_at'     => Carbon::now()
                                                                ]);
    }

    /**
     * @param array $customData
     */
    public function newTransaction(array $customData)
    {
        $data = [
            'amount'      => $this->amount,
            'provider'    => $this->getCallName(),
            'currency'    => Currency::type($this->getCallName()),
            'status'      => 0,
            'description' => $this->description,
            'ip'          => Request::getClientIp(),
            'updated_at'  => Carbon::now(),
            'created_at'  => Carbon::now()
        ];

        foreach ($customData as $key => $value)
        {
            if(Schema::hasColumn(config('payment.table'),$key))
            {
                $data = array_add($data, $key, $value);
            }
        }

        $insertId = DB::table(config('payment.table'))->insertGetId($data);

        $this->transactionFindById($insertId);
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
        DB::table(config('payment.table'))
                 ->where('id', $this->transaction->id)
                 ->update(['authority' => $this->authority]);
        $this->transactionFindById($this->transaction->id);
    }

    public function transactionSucceed()
    {
        $data = DB::table(config('payment.table'))
                  ->where('id', $this->transaction->id)
                  ->update(['reference' => $this->reference, 'status' => 1, 'card_number' => $this->cardNumber]);

        $this->transactionFindById($this->transaction->id);

        return $data;
    }

    public function transactionFindById($id)
    {
        $this->transaction = DB::table(config('payment.table'))->where('id', $id)->first();
    }

    public function transactionFindByAuthority($authority)
    {
        $this->transaction = DB::table(config('payment.table'))
                               ->where('authority', $authority)
                               ->where('status', 0)
                               ->first();

        if (is_null($this->transaction)) {
            throw new PaymentException('تراکنش یافت نشد', 1500);
        }
    }

    public function transactionFindByIdAndAuthority($id, $authority)
    {
        $this->transaction = DB::table(config('payment.table'))
                               ->where('id', $id)
                               ->where('status', 0)
                               ->where('authority', $authority)
                               ->first();

        if (is_null($this->transaction)) {
            throw new PaymentException('تراکنش یافت نشد', 1500);
        }
    }
}

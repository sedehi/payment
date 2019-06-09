<?php

namespace Sedehi\Payment\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{

    public $table = 'transactions';

    public function log(){

        return $this->hasOne(Log::class);
    }

}

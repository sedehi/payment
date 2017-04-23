<?php
/**
 * Created by PhpStorm.
 * User: Navid Sedehi
 * Date: 6/1/2015
 * Time: 4:55 PM
 */

namespace Sedehi\Payment\Facades;

use Illuminate\Support\Facades\Facade;

class Payment extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'payment';
    }
}
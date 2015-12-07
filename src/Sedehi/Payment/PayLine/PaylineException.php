<?php
/**
 * Created by PhpStorm.
 * User: Navid Sedehi
 * Date: 6/1/2015
 * Time: 11:14 PM
 */

namespace Sedehi\Payment\Payline;

use Sedehi\Payment\PaymentException;

class PaylineException extends PaymentException
{

    public static $errors = [
        'send'   => [
            -1  => 'api ارسالی با نوع api تعریف شده در payline سازگار نیست',
            -2  => 'amount مقدار داده عددی نمی باشد و یا کمتر از 1000 ریال است',
            -3  => 'مقدار redirect رشته null است',
            -4  => 'درگاهی با اطلاعات ارسالی شما یافت نشد و یا در حال انتظار می باشد'
        ],
        'get'   => [
            -1  => 'api ارسالی با نوع api تعریف شده در payline سازگار نیست',
            -2  => 'trans_id ارسال شده معتبر نمی باشد',
            -3  => 'id_get ارسالی معتبر نمی باشد',
            -4  => 'چنین تراکنشی در سیستم وجود ندارد و یا موفقیت آمیز نبوده است',
             1  => 'تراکنش موفقیت آمیز بوده است'
        ]
    ];



    public function __construct($request , $errorId)
    {
        parent::__construct(@self::$errors[$request][$errorId], $errorId);
    }

}
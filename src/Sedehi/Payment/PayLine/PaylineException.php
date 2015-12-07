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
            -1 => 'api ارسالی با نوع api تعریف شده در payline سازگار نیست',
            -2  => 'amount مقدار داده عددی نمی باشد و یا کمتر از 1000 ریال است',
            -3  => 'مقدار redirect رشته null است',
            -4  => 'درگاهی با اطلاعات ارسالی شما یافت نشد و یا در حال انتظار می باشد'
        ],
        'get'   => [
            421 => 'IP نامعتبر است',
            51  => 'تراکنش تکراری است',
            54  => 'تراکنش مرجع موجود نیست',
            55  => 'تراکنش نامعتبر است',
            61  => 'خطا در واریز'
        ]
    ];



    public function __construct($errorId)
    {
        parent::__construct(@self::$errors[$errorId], $errorId);
    }

}
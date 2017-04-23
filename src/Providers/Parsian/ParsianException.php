<?php

namespace Sedehi\Payment\Providers\Parsian;

use Sedehi\Payment\PaymentException;

/**
 * Created by PhpStorm.
 * User: Navid
 * Date: 12/7/2015
 * Time: 10:17 PM
 */
class ParsianException extends PaymentException
{

    public static $errors = [
        0  => 'تراکنش با موفقیت انجام شد.',
        1  => 'خطا در انجام تراکنش',
        2  => 'بین عملیات وقفه افتاده است.',
        10 => 'شماره کارت نامعتبر است.',
        11 => 'کارت شما منقضی شده است',
        12 => 'رمز کارت وارد شده اشتباه است',
        13 => 'موجودی کارت شما کافی نیست',
        14 => 'مبلغ تراکنش بیش از سقف مجاز پذیرنده است.',
        15 => 'سقف مجاز روزانه شما کامل شده است.',
        18 => 'این تراکنش قبلا تایید شده است',
        20 => 'اطلاعات پذیرنده صحیح نیست.',
        21 => 'invalid authority',
        22 => 'اطلاعات پذیرنده صحیح نیست.',
        30 => 'عملیات قبلا با موفقیت انجام شده است.',
        34 => 'شماره تراکنش فروشنده درست نمی باشد.',
    ];

    public function __construct($errorId)
    {
        parent::__construct(@self::$errors[$errorId], $errorId);
    }
}
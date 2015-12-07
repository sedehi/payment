<?php
namespace Sedehi\Payment\Mellat;

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
        20 => 'شماره پین کد فروشنده نادرست است',
        22 => 'شماره پین یا آی پی فروشنده نادرست است',
        30 => 'عملیات مورد نظر قبلا با موفقیت انجام شده است',
        34 => 'شماره تراکنش اعلام شده توسط فروشنده صحیح نمی باشد'
    ];

    public function __construct($errorId)
    {
        parent::__construct(@self::$errors[$errorId], $errorId);
    }
}
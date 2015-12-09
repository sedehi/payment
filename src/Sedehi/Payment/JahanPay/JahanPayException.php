<?php
/**
 * Created by PhpStorm.
 * User: Navid Sedehi
 * Date: 6/1/2015
 * Time: 11:14 PM
 */

namespace Sedehi\Payment\JahanPay;

use Exception;
use Sedehi\Payment\PaymentException;

class JahanPayException extends PaymentException
{

    public static $errors = array(
        -20 => 'api نامعتبر است',
        -21 => 'آی پی نامعتبر است',
        -22 => 'مبلغ از کف تعریف شده کمتر است',
        -23 => 'مبلغ از سقف تعریف شده بیشتر است',
        -24 => 'مبلغ نامعتبر است',
        -6  => 'ارتباط با بانک برقرار نشد',
        -26 => 'درگاه غیرفعال است',
        -27 => 'آی پی شما مسدود است',
        -9  => 'خطای ناشناخته',
        -29 => 'آدرس کال بک خالی است ',
        -30 => 'چنین تراکنشی یافت نشد',
        -31 => 'تراکنش انجام نشده ',
        -32 => 'تراکنش انجام شده اما مبلغ نادرست است ',
    );

    public function __construct($errorId)
    {

        parent::__construct(@$this->errors[$errorId], $errorId);
    }

}
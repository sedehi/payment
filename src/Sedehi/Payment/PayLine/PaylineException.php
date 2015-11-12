<?php
/**
 * Created by PhpStorm.
 * User: Navid Sedehi
 * Date: 6/1/2015
 * Time: 11:14 PM
 */

namespace Sedehi\Payment\PayLine;

use Exception;

class PaylineException extends Exception
{

    protected $requestErrors = array(
        -1 => 'api ارسالی با نوع api تعریف شده در payline سازگار نیست',
        -2 => 'مقدار amount داده عددی نمی باشد',
        -3 => 'مقدار redirect رشته null است.',
        -4 => 'درگاهی با اطلاعات ارسالی شما یافت نشده و یا در حالت انتظار می باشد.'
    );
    protected $verifyErrors  = array(
        -1 => 'api ارسالی با نوع api تعریف شده در payline سازگار نیست',
        -2 => 'ارسال شده معتبر نمی باشد trans_id',
        -3 => 'ارسالی معتبر نمی باشد id_get : 3-',
        -4 => 'چنین تراکنشی در سیستم وجود ندارد و یا موفقیت آمیز نبوده است'
    );

    public function __construct($errorId, $type)
    {

        if ($type == 'request') {
            $errors = $this->requestErrors;
        }
        if ($type == 'verify') {
            $errors = $this->requestErrors;
        }
        parent::__construct(@$errors[$errorId], $errorId);
    }

}
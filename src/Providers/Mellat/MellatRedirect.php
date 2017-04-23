<?php
/**
 * Created by PhpStorm.
 * User: Navid Sedehi
 * Date: 6/28/2015
 * Time: 8:59 PM
 */

namespace Sedehi\Payment\Providers\Mellat;

class MellatRedirect
{

    public static function to($authority)
    {
        return '<html>
    <body>
        <script>
        	var form = document.createElement("form");
        	form.setAttribute("method", "POST");
        	form.setAttribute("action", "https://bpm.shaparak.ir/pgwchannel/startpay.mellat");
        	form.setAttribute("target", "_self");
            var hiddenField = document.createElement("input");
        	hiddenField.setAttribute("name", "RefId");
        	hiddenField.setAttribute("value", "'.$authority.'");

            form.appendChild(hiddenField);

        	document.body.appendChild(form);
        	form.submit();
        	document.body.removeChild(form);
        </script>
    </body>
</html>';
    }

}
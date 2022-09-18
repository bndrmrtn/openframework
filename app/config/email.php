<?php

/**
 *  to use the mails properly create the xmail function
 *  the function should look like as this:
 *  function xmail(string $to, string $subject, string $message, array|string $additional_headers, string $additional_params){
 *      // return mail(...) or return your mail client
 *      // return true if the mail successfully sent, otherwise false
 *      return DemoMail::temp(...func_get_args());
 *  }
 */

return array(
    'function' => 'xmail',
);
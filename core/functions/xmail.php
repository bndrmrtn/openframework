<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *\
 *                                                                                             *
 *  This mail function is a demo/fake mail store function                                      *
 *   You could see the "sent" emails with the "php dev cache mails" command                 *
 *   This FakeMails stored in the cache storage only for development                           *
 *   Create a new mail function for your mail client then configure it in that function        *
 *   The Mail class returns these data for the mail:                                           *
 *        string "from"                                                                        *
 *        string "to"                                                                          *
 *        string "subject"                                                                     *
 *        string "body"                                                                        *
 *        bool "is_html"                                                                       *
 *        string|array "headers"                                                               *
 *        ... other params that specified in the Mail->addparam() function                     *
 *   Then you need to return a bool value that the mail is sent or not                         *
 *                                                                                             *
\* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

function xmail(){
     // demo mail function
     // not sendig the email in real
     $path = cache('/xmail-demo/');
     createPath($path);
     $maildata = "<?php\n\nreturn ";
     $maildata .= var_export(array_merge(func_get_args(), ['sent_at' => date('Y-m-d H:i:s')]), true);
     $maildata .= ';';
     file_put_contents($path . date('Y-m-d-H-i-s') . '.xmail-cached-file.php', $maildata);
     return true;
}
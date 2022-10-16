<?php

namespace Framework\Models;

use Framework\Base\ModelBase;

class EmailVerifications extends ModelBase {

    // database table name
    protected static string $_table = 'email_verifications';

    // all database fields
    // this will auto filled
    protected array $all_field = [];

    // model config
    protected static array $_config = [
        // the fields in the db
        'fields' => array(
            // don't add 'id' if you don't have a writable config
            'user_id',
            'token',
            'date',
        ),
        'readable' => array(
            // fields that you want to allow to read
            'id',
            'user_id',
            'token',
            'date',
            // 'password'   password is a sensitive data so it's ignored
        ),
        /**
         * if writable property not exists, all 'fields' are writable
         */
        //'writable' => array(),
    ];

    // is exists in database
    public bool $exists = false;

}
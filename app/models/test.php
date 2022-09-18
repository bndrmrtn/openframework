<?php

use Framework\Base\ModelBase;

class TestModel extends ModelBase {

    // all database fields
    // this will auto filled
    protected array $all_field = [];

    // model config
    protected static array $_config = [
        // the fields in the db
        'fields' => array(
            // don't add 'id' if you don't have a writable config
            'name',
            'password',
        ),
        'readable' => array(
            // fields that you want to allow to read
            'id',
            'name',
            // 'password' password is a sensitive data so it ignored
        ),
        /**
         * if writable property not exists, all 'fields' are writable
         */
        //'writable' => array(),
    ];

    // Database table name
    protected static string $_table = 'test';

    // is exists in database
    public bool $exists = false;

}
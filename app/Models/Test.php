<?php

/**
 * OpenFramework Database Model
 * Easy to use
 * Example:
 * $model = new TestModel(search: 1, findBy: 'id', fail: false)
 * $model->fields // returns all the readable fields from the model
 * $model->id|field|... // returns a specific field
 * Note: don't use a database field with '-' in it
 * Good: $model->userId,
 * Bad: $model->user-id
 */

namespace Framework\Models;

use Framework\Base\ModelBase;

class Test/*Model*/ extends ModelBase {

    // database table name
    protected static string $_table = 'Please Fill Me :)';

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
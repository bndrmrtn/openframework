<?php

// create users table
$table = SQL::table('users');

// userid
$table->col('id','bigint',255,false,true);

// username used for login
$table->col('username','varchar',255);

// registered email
$table->col('email','varchar',255);

// a random field (just to show how to add custom fields)
$table->col('uniqid','varchar',255);

// email verified at (if the verification needed in the config('auth') file)
$table->col('email_verified_at','datetime',NULL,true);

// a hashed password
$table->col('password','text');

// profile creation date
$table->createdAt();

// set primary key to id
$table->setPrimaryKey('id');

// save the table to the database
$table->save();

// run 'php dev db setup:tables' to set it up
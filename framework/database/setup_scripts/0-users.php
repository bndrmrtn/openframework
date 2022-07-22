<?php

// create users table
$table = SQL::table('users');

$table->col('id','bigint',255,false,true);
$table->col('username','varchar',255);
$table->col('email','varchar',255);
$table->col('uniqid','varchar',255);
$table->col('password','text');
$table->col('date','datetime');

$table->setPrimaryKey('id');
$table->save();
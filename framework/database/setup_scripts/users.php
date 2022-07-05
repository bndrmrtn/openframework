<?php

// create users table
$table = SQL::table('users');

$table->tableColumn('id','bigint',255,false,true);
$table->tableColumn('username','varchar',255);
$table->tableColumn('email','varchar',255);
$table->tableColumn('uniqid','varchar',255);
$table->tableColumn('password','text');
$table->tableColumn('date','datetime');

$table->tableSetPrimaryKey('id');
$table->saveTable();
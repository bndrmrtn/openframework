<?php

$table = SQL::table('__logins');

$table->col('id','bigint',255,false,true);
$table->col('user','varchar',255);
$table->col('token','text');
$table->col('useragent','text');
$table->createdAt();

$table->setPrimaryKey('id');
$table->save();
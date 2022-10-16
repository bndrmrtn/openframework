<?php

$table = SQL::table('email_verifications');

$table->col('id','bigint',255,false,true);
$table->foreignCol('user_id','bigint',255, 'users');
$table->col('token','text');
$table->createdAt();

$table->setPrimaryKey('id');
$table->save();
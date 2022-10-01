<?php

$table = SQL::table('email_verifications');

$table->col('id','bigint',255,false,true);
$table->foreignCol('userid','bigint',255, 'users');
$table->col('token','text');
$table->col('date','datetime');

$table->setPrimaryKey('id');
$table->save();
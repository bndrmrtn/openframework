<?php

class Login extends Auth {

    private static $table_name = '__logins';

    public static function save(){
        self::checkTable();
        $uniqueId = self::uniqueId();
        $date = Dates::toString(Dates::addTo(['day' => 5],Dates::now()));
        $i = DB::insert(self::$table_name,[
            'user' => self::user('user'),
            'token' => $uniqueId,
            'date' => $date,
        ]);
        if(isset($i['error'])) MErrors::ServerError();
        return $uniqueId;
    }

    private static function uniqueId(){
        $table = self::$table_name;
        do {
            $token = randomString(100);
        } while(DB::exists($table,['token' => $token]));
        return $token;
    }

    public static function checkTable(){
        if(!_env('APP_DEV',true)) return;
        $sql = SQL::table(self::$table_name)
        ->tableColumn('id','bigint',255,false,true)
        ->tableColumn('user','varchar',255)
        ->tableColumn('token','text')
        ->tableColumn('date','datetime')
        ->tableSetPrimaryKey('id')
        ->saveTable();
        return $sql == true;
    }

    public static function token($token){
        self::checkTable();
        $table = self::$table_name;
        $select = DB::_select("SELECT * FROM $table WHERE token = ? AND date > ? LIMIT 1",[$token,date('Y-m-d H:i:s')],[0]);
        if(isset($select['error'])) return false;
        return $select;
    }

    protected static function destroy($token){
        DB::delete(self::$table_name,'token',$token);
        return true;
    }

}
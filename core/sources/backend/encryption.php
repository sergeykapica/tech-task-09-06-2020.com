<?php

class Encryption {
    
    static $salt = 'mishaulyanov';
    static $iteration_count = 100;
    static $iteration_count_authorization = 200;
    
    public static function pass( $pass )
    {
        for( $i = 0; $i < self::$iteration_count; $i++ )
        {
            $pass = md5( $pass . self::$salt );
        }
        
        return $pass;
    }
    
    public static function authorization( $data )
    {
        for( $i = 0; $i < self::$iteration_count_authorization; $i++ )
        {
            $data = md5( $data . self::$salt );
        }
        
        return $data;
    }
}
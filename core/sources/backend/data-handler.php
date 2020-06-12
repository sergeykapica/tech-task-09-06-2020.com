<?php

class Data_Handler {
    
    public static function handler_string( $string ) {
        return trim( strip_tags( $string ) );
    }
}
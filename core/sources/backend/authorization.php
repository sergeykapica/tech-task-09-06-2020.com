<?php
session_start();

require_once( $_SERVER[ 'DOCUMENT_ROOT' ] . PATH_TO_MODEL_RELATIVE . 'DB.php' );
require_once( $_SERVER[ 'DOCUMENT_ROOT' ] . PATH_TO_BACKEND_RELATIVE . 'encryption.php' );

class Authorization {
    
    public static function check( $authorized_data = false, $redirect = true )
    {
        if( isset( $_SESSION[ 'authorized' ] ) )
        {
            $authorized_data = Data_Handler::handler_string( $_SESSION[ 'authorized' ] );
        }
        else if( $authorized_data == false )
        {
            if( $redirect )
            {
                header( 'Location: /authorization' );
                die();
            }
            else
            {
                return false;
            }
        }
        
        $DB = new DB();
        $authorization_list = $DB->get_authorization_list();

        if( $authorization_list )
        {
            foreach( $authorization_list as $authorization_item )
            {
                if( $authorized_data == Encryption::authorization( $authorization_item[ 'login' ] . $authorization_item[ 'password' ] ) )
                {
                    if( ! isset( $_SESSION[ 'authorized' ] ) )
                    {
                        $_SESSION[ 'authorized' ] = $authorized_data;
                    }
                    
                    return true;
                }
            }
        }

        if( isset( $_SESSION[ 'authorized' ] ) )
        {
            if( $redirect )
            {
                header( 'Location: /authorization' );
                die();
            }
        }
        
        return false;
    }
    
    public static function destroy()
    {
        session_destroy();
        session_unset();
    }
}
<?php

class View {
    
    public static function get( $file_name, $only_content = false ) {
        
        global $styles_items, $script_items, $admin_status;
        
        if( ! $only_content )
        {
            require_once( $_SERVER[ 'DOCUMENT_ROOT' ] . PATH_TO_VIEW_STATIC_RELATIVE . 'header.php' );
            require_once( $_SERVER[ 'DOCUMENT_ROOT' ] . PATH_TO_VIEW_DYNAMIC_RELATIVE . $file_name . '.php' );
            require_once( $_SERVER[ 'DOCUMENT_ROOT' ] . PATH_TO_VIEW_STATIC_RELATIVE . 'footer.php' );
        }
        else
        {
            require_once( $_SERVER[ 'DOCUMENT_ROOT' ] . PATH_TO_VIEW_DYNAMIC_RELATIVE . $file_name . '.php' );
        }
    }
}
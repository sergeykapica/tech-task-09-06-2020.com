<?php

require_once( $_SERVER[ 'DOCUMENT_ROOT' ] . '/core/sources/backend/base-settings.php' );
require_once( $_SERVER[ 'DOCUMENT_ROOT' ] . PATH_TO_BACKEND_RELATIVE . 'view.php' );
require_once( $_SERVER[ 'DOCUMENT_ROOT' ] . PATH_TO_BACKEND_RELATIVE . 'data-handler.php' );
require_once( $_SERVER[ 'DOCUMENT_ROOT' ] . PATH_TO_BACKEND_RELATIVE . 'pagination.php' );
require_once( $_SERVER[ 'DOCUMENT_ROOT' ] . PATH_TO_BACKEND_RELATIVE . 'authorization.php' );

global $styles_items, $script_items, $admin_status;

class Index
{
    public $current_page;
    
    public function __construct( $current_page )
    {
        global $styles_items, $script_items, $admin_status;
        $styles_items = array( 'bootstrap.min', 'font-awesome.min', 'all.min', 'index', 'adaptive' );
        $script_items = array( 'jquery-3.5.1.slim.min', 'popper.min', 'bootstrap.min', 'xml-http-request', 'interface' );
        $admin_status = Authorization::check( false, false );
        
        $this->current_page = $current_page;
        $this->$current_page();
    }
    
    public function __call( $name, $arguments ) 
    {
        echo 'Страницы не существует';
    }
    
    public function index() 
    {
        global $styles_items, $script_items;

        $this->current_page = 'tasks_list';
        $this->tasks_list( false );
    }
    
    public function tasks_list( $ajax = true )
    {
        if( isset( $_GET[ 'sort_by_field' ] ) && isset( $_GET[ 'sort_dir' ] ) && isset( $_GET[ 'page_id' ] ) )
        {
            $params = array(
                'db_method_name' => 'get_tasks_list',
                'db_method_arguments' => array(
                    'sort_by_field' => Data_Handler::handler_string( $_GET[ 'sort_by_field' ] ),
                    'sort_dir' => Data_Handler::handler_string( $_GET[ 'sort_dir' ] )
                ),
                'page_id' => Data_Handler::handler_string( $_GET[ 'page_id' ] ),
                'limit' => 3,
                'resonance' => 3,
                'base_link' => '/tasks_list'
            );
        }
        else
        {
            $params = array(
                'db_method_name' => 'get_tasks_list',
                'db_method_arguments' => array(
                    'sort_by_field' => 'name',
                    'sort_dir' => 'ascending'
                ),
                'page_id' => 1,
                'limit' => 3,
                'resonance' => 3,
                'base_link' => '/tasks_list'
            );
        }
        
        global $pagination_data;
        
        $pagination = new Pagination( $params );
        $pagination_data = $pagination->get();
        
        if( $ajax )
        {
            View::get( $this->current_page, true );
        }
        else
        {
            View::get( $this->current_page );
        }
    }
    
    public function add_task()
    {
        if( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' )
        {
            require_once( $_SERVER[ 'DOCUMENT_ROOT' ] . PATH_TO_MODEL_RELATIVE . 'DB.php' );
            
            $params = array(
                'action' => 'insert',
                'fields' => array(
                    'name' => Data_Handler::handler_string( $_POST[ 'name' ] ),
                    'email' => Data_Handler::handler_string( $_POST[ 'email' ] ),
                    'description' => Data_Handler::handler_string( $_POST[ 'description' ] ),
                    'date' => time()
                )
            );
            
            $DB = new DB();
            echo $DB->set_tasks_list( $params );
        }
    }
    
    public function authorization()
    {
        View::get( $this->current_page );
    }
    
    public function authorization_handler()
    {
        if( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' )
        {
            require_once( $_SERVER[ 'DOCUMENT_ROOT' ] . PATH_TO_BACKEND_RELATIVE . 'encryption.php' );
            
            $authorization_data = Encryption::authorization( Data_Handler::handler_string( $_POST[ 'login' ] ) . Encryption::pass( Data_Handler::handler_string( $_POST[ 'password' ] ) ) );
            
            echo Authorization::check( $authorization_data );
        }
    }
    
    public function authorization_exit()
    {
        Authorization::destroy();
        
        header( 'Location: /' );
        die();
    }
    
    public function update_handler()
    {
        if( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' )
        {
            require_once( $_SERVER[ 'DOCUMENT_ROOT' ] . PATH_TO_MODEL_RELATIVE . 'DB.php' );
            
            array_walk( $_POST[ 'task_data' ], function( &$data )
            {
                $data = json_decode( Data_Handler::handler_string( $data ) );
                
                if( isset( $data->description ) )
                {
                    $data->updated = 1;
                }
            } );
            
            $params = array(
                'action' => 'update',
                'fields' => $_POST[ 'task_data' ]
            );
            
            $DB = new DB();
            echo $DB->set_tasks_list( $params );
        }
    }
}

if( isset( $_GET[ 'request_page' ] ) ) 
{
    $current_page = Data_Handler::handler_string( $_GET[ 'request_page' ] );
}
else
{
    $current_page = 'index';
}

new Index( $current_page );
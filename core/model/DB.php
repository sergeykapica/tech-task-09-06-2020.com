<?php

class DB
{
    protected $PDO;
    protected $DB_data = array(
        'host' => 'localhost',
        'port' => 3306,
        'db_name' => 'tech_task_09_06_2020',
        'charset' => 'utf8',
        'user' => 'root',
        'password' => ''
    );
    protected $tables = array(
        'tasks' => 'tasks',
        'authorization' => 'authorization'
    );
    
    public function __construct()
    {
        $this->PDO = new PDO( 'mysql:host=' . $this->DB_data[ 'host' ] . ';port=' . $this->DB_data[ 'port' ] . ';dbname=' . $this->DB_data[ 'db_name' ] . ';charset=' . $this->DB_data[ 'charset' ], $this->DB_data[ 'user' ], $this->DB_data[ 'password' ] );
    }
    
    public function get_tasks_list( $params )
    {
        if( isset( $params[ 'offset' ] ) && isset( $params[ 'limit' ] ) ) {
            
            $params[ 'sort_dir' ] = $params[ 'sort_dir' ] == 'ascending' ? 'ASC' : 'DESC';
            
            $query = sprintf( 'SELECT id, name, email, description, status, updated FROM ' . $this->tables[ 'tasks' ] . ' ORDER BY %s %s LIMIT :offset, :limit', $params[ 'sort_by_field' ], $params[ 'sort_dir' ] );
            $query = $this->PDO->prepare( $query );
            $query->bindParam( ':offset', $params[ 'offset' ], PDO::PARAM_INT );
            $query->bindParam( ':limit', $params[ 'limit' ], PDO::PARAM_INT );
            $query->execute();
        }
        else if( isset( $params[ 'count' ] ) ) {
            $query = 'SELECT COUNT( * ) as total FROM ' . $this->tables[ 'tasks' ];
            $query = $this->PDO->query( $query );
        }
        
        if( $query == false || $this->PDO->errorCode() > 0 )
        {
            return false;
        }
        else
        {
            return $query->fetchAll();
        }
    }
    
    public function set_tasks_list( $params )
    {
        $query_fields = '';
        $query_placeholders = '';
        $query_placeholders_values = array();
        
        if( $params[ 'action' ] == 'insert' )
        {
            $fields_keys = array_keys( $params[ 'fields' ] );
            
            foreach( $params[ 'fields' ] as $field_title => $field_value )
            {
                $query_fields .= $field_title;
                $query_placeholders .= ':' . $field_title;
                
                if( $field_title != end( $fields_keys ) )
                {
                    $query_fields .= ', ';
                    $query_placeholders .= ', ';
                }
                
                $query_placeholders_values[ ':' . $field_title ] = $field_value;
            }
            
            $query = 'INSERT INTO ' . $this->tables[ 'tasks' ] . ' ( ' . $query_fields . ' ) VALUES ( ' . $query_placeholders . ' )';
            $query = $this->PDO->prepare( $query );
            $query = $query->execute( $query_placeholders_values );
        }
        else
        {
            foreach( $params[ 'fields' ] as $data_key => $data )
            {
                $query_fields = '';
                $query_placeholders_values = array();
                $fields_keys = array_keys( ( array ) $data );
                
                foreach( $data as $field_title => $field_value )
                {
                    if( $field_title !== 'id' )
                    {
                        $query_fields .= $field_title . ' = :' . $field_title;

                        if( $field_title != end( $fields_keys ) )
                        {
                            $query_fields .= ', ';
                        }
                    }
                    
                    $query_placeholders_values[ ':' . $field_title ] = $field_value;
                }
                
                $query = 'UPDATE ' . $this->tables[ 'tasks' ] . ' SET ' . $query_fields . ' WHERE id = :id';
                $query = $this->PDO->prepare( $query );
                $query = $query->execute( $query_placeholders_values );
            }
        }
        
        if( $query === false || $this->PDO->errorCode() > 0 )
        {
            return false;
        }
        else
        {
            return true;
        }
    }
    
    public function get_authorization_list()
    {
        $query = 'SELECT id, login, password FROM ' . $this->tables[ 'authorization' ];
        $query = $this->PDO->query( $query );
        
        if( $query == false || $this->PDO->errorCode() > 0 )
        {
            return false;
        }
        else
        {
            return $query->fetchAll();
        }
    }
}
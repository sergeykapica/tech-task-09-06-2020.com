<?php

class Pagination
{
    protected $params;
    protected $DB;
    
    public function __construct( $params )
    {
        require_once( $_SERVER[ 'DOCUMENT_ROOT' ] . PATH_TO_MODEL_RELATIVE . 'DB.php' );
        
        $this->params = $params;
        $this->DB = new DB();
    }
    
    public function get()
    {
        $db_method_name = $this->params[ 'db_method_name' ];
        $db_method_arguments = $this->params[ 'db_method_arguments' ];
        $db_method_arguments[ 'offset' ] = ( $this->params[ 'page_id' ] - 1 ) * $this->params[ 'limit' ];
        $db_method_arguments[ 'limit' ] = $this->params[ 'limit' ];
        
        $rows_list = $this->DB->$db_method_name( $db_method_arguments );
        $rows_count = $this->DB->$db_method_name( array( 'count' => true ) );
        $rows_count = $rows_count !== false ? $rows_count[ 0 ][ 'total' ] : false;
        
        if( $rows_list && $rows_count )
        {
            $pagesCount = ceil( $rows_count / $this->params[ 'limit' ] );
            $startPage = $this->params[ 'page_id' ] <= $this->params[ 'resonance' ] ? 1 : ( $this->params[ 'page_id' ] + 1 ) - $this->params[ 'resonance' ];
            $endPage = $this->params[ 'page_id' ] + $this->params[ 'resonance' ];
            $endPage = $endPage > $pagesCount ? $pagesCount : $endPage;
            
            if( $pagesCount > 1 )
            {
                ob_start();
            ?>

                <ul class="pagination">
                    
                    <?php
            
                    if( $this->params[ 'page_id' ] > 1 )
                    {
                        
                    ?>
                        <li class="page-item">
                          <a class="page-link" href="<?php echo $this->params[ 'base_link' ] . '?sort_by_field=' . $db_method_arguments[ 'sort_by_field' ] . '&sort_dir=' . $db_method_arguments[ 'sort_dir' ] . '&page_id=' . ( $this->params[ 'page_id' ] - 1 ); ?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                          </a>
                        </li>
                    <?php
                        
                    }
            
                    for( $i = $startPage; $i <= $endPage; $i++ )
                    {
                        
                    ?>
                    
                        <li class="page-item <?php echo $this->params[ 'page_id' ] == $i ? 'active' : ''; ?>"><a class="page-link" href="<?php echo $this->params[ 'base_link' ] . '?sort_by_field=' . $db_method_arguments[ 'sort_by_field' ] . '&sort_dir=' . $db_method_arguments[ 'sort_dir' ] . '&page_id=' . $i; ?>"><?php echo $i; ?></a></li>
                    
                    <?php
            
                    }
                        
                    if( $this->params[ 'page_id' ] < $pagesCount )
                    {
                        
                    ?>
                    
                        <li class="page-item">
                          <a class="page-link" href="<?php echo $this->params[ 'base_link' ] . '?sort_by_field=' . $db_method_arguments[ 'sort_by_field' ] . '&sort_dir=' . $db_method_arguments[ 'sort_dir' ] . '&page_id=' . ( $this->params[ 'page_id' ] + 1 ); ?>" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                          </a>
                        </li>
                    <?php
                        
                    }
            
                    ?>
                </ul>

            <?php
                $pagination_navigation = ob_get_clean();
                
                return array(
                    'pagination_content' => $rows_list,
                    'pagination_navigation' => $pagination_navigation
                );
            }
            else
            {
                return array(
                    'pagination_content' => $rows_list
                );
            }
        }
    }
}
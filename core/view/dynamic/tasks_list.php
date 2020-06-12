<?php

global $pagination_data;

if( ! $only_content )
{

?>
    <table class="table table-responsive-sm table-responsive-md">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th role="button" class="sort-button" data-sort-field="name" aria-sort="ascending">
                    <span class="mr-1">Name</span>
                    <i class="fa fa-caret-down sort-icon" aria-hidden="true"></i>
                </th>
                <th role="button" class="sort-button" data-sort-field="email" aria-sort="ascending">
                    <span class="mr-1">Email</span>
                    <i class="fa fa-caret-down sort-icon" aria-hidden="true"></i>
                </th>
                <th>Description</th>
                <th role="button" class="sort-button" data-sort-field="status" aria-sort="ascending">
                    <span class="mr-1">Status</span>
                    <i class="fa fa-caret-down sort-icon" aria-hidden="true"></i>
                </th>
            </tr>
        </thead>
        <tbody class="content-area">
            <?php
            
            if( ! empty( $pagination_data[ 'pagination_content' ] ) )
            {
                foreach( $pagination_data[ 'pagination_content' ] as $content_number => $content )
                {
                    if( ! $admin_status )
                    {
                ?>

                    <tr class="table-tr">
                        <th><?php echo $content_number + 1; ?></th>
                        <td data-field="name"><?php echo $content[ 'name' ]; ?></td>
                        <td data-field="email"><?php echo $content[ 'email' ]; ?></td>
                        <td data-field="description"><?php echo $content[ 'description' ]; ?></td>
                        <td data-field="status">
                            <?php

                                if( $content[ 'status' ] == 0 )
                                {
                            ?>
                                    <i class="fa fa-exclamation-circle status unconfirm" aria-hidden="true"></i>

                            <?php

                                }
                                else
                                {

                            ?>

                                <i class="fa fa-chevron-circle-down status confirm" aria-hidden="true"></i>

                            <?php

                                }

                            ?>

                            <?php

                                if( $content[ 'updated' ] == 1 )
                                {
                            ?>
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>

                            <?php

                                }

                            ?>
                        </td>
                    </tr>

                <?php

                    }
                    else
                    {

                ?>

                    <tr class="table-tr">
                        <th data-field="id" data-id="<?php echo $content[ 'id' ]; ?>" class="edited"><?php echo $content_number + 1; ?></th>
                        <td data-field="name"><?php echo $content[ 'name' ]; ?></td>
                        <td data-field="email"><?php echo $content[ 'email' ]; ?></td>
                        <td data-field="description" class="editable" tabindex="1"><?php echo $content[ 'description' ]; ?></td>
                        <td data-field="status" class="editable" tabindex="1">
                            <?php

                                if( $content[ 'status' ] == 0 )
                                {
                            ?>
                                    <i class="fa fa-exclamation-circle status unconfirm" aria-hidden="true"></i>

                            <?php

                                }
                                else
                                {

                            ?>

                                <i class="fa fa-chevron-circle-down status confirm" aria-hidden="true"></i>

                            <?php

                                }

                            ?>

                            <?php

                                if( $content[ 'updated' ] == 1 )
                                {
                            ?>
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>

                            <?php

                                }

                            ?>
                        </td>
                    </tr>

                <?php

                    }
                }
            }
            else
            {
                
            ?>
                <tr class="table-tr">
                    <td>Data is empty</td>
                </tr>
            <?php
                
            }

            ?>
        </tbody>
    </table>

    <nav class="pagination-lining pt-4 pb-4">
        <?php

            if( isset( $pagination_data[ 'pagination_navigation' ] ) )
            {
                echo $pagination_data[ 'pagination_navigation' ];
            }

        ?>
    </nav>

    <form method="post" action="/add_task" enctype="application/x-www-form-urlencoded" id="add-task-form" novalidate>
        <div class="form-row mb-3">
            <div class="input-group col-sm-6 mb-xs-3 pl-sm-0 pr-md-3">
                <input type="text" name="name" class="form-control" placeholder="Name" autocomplete="off">
                <div class="d-block invisible feedback"></div>
            </div>
            <div class="col-sm-6 pr-sm-0 pl-md-3">
                <div class="input-group">
                    <label for="input-email" class="input-group-prepend">
                        <div class="input-group-text">@</div>
                    </label>
                    <input type="email" name="email" id="input-email" class="form-control" placeholder="Email" autocomplete="off">
                </div>
                <div class="d-block invisible input-email feedback"></div>
            </div>
        </div>
        <div class="form-row mb-3 textarea-lining">
            <textarea name="description" class="form-control textarea" placeholder="Description"></textarea>
            <div class="d-block invisible feedback"></div>
        </div>
        <div class="form-row">
            <button class="btn btn-primary px-5" type="submit">Submit</button>
        </div>                                               
    </form>

    <script type="text/javascript" src="<?php echo PATH_TO_JS; ?>validate-fields.js"></script>

    <script type="text/javascript">
        $( window ).ready( function()
        {
            let sortButton = $( '.sort-button' );
            var paginationLining = $( '.pagination-lining' );
            var contentArea = $( '.content-area' );
            
            sortButton.on( 'click', function()
            {
                let thisButton = $( this );
                let sortField = thisButton.attr( 'data-sort-field' );
                let sortDir = thisButton.attr( 'aria-sort' );
                sortDir = sortDir === 'ascending' ? 'descending' : 'ascending';  
                thisButton.attr( 'aria-sort', sortDir );
                let activePageID = $( '.page-item.active' ).text();

                ajaxRequest(
                    'POST',
                    '/tasks_list?sort_by_field=' + sortField + '&sort_dir=' + sortDir + '&page_id=' + activePageID,
                    'json',
                    $.proxy( function( xhr )
                    {
                        let response = xhr.response;

                        this.container.html( response.pagination_content );
                        this.navigationContainer.html( response.pagination_navigation );
                    },
                    {
                        container: contentArea,
                        navigationContainer: paginationLining
                    } )
                );
            } );
            
            paginationLining.on( 'click', function( e )
            {
                e = e || window.event;
                e.preventDefault();
                
                var target = $( e.target );
                
                while( ! target.hasClass( 'page-link' ) && ! target.hasClass( 'pagination-lining' ) )
                {
                    target = target.parent();
                }
                
                if( target.hasClass( 'page-link' ) )
                {
                    ajaxRequest(
                        'POST',
                        target.attr( 'href' ),
                        'json',
                        $.proxy( function( xhr )
                        {
                            let response = xhr.response;

                            this.container.html( response.pagination_content );
                            this.navigationContainer.html( response.pagination_navigation );
                        },
                        {
                            container: contentArea,
                            navigationContainer: paginationLining
                        } )
                    );
                }
            } );
            
            var validator = new Validator( {
                fieldsList: {
                    'name': {
                        minSymbols: 3,
                        maxSymbols: 100
                    },
                    
                    'email': {
                        minSymbols: 3,
                        maxSymbols: 100,
                        email: true
                    },
                    
                    'description': {
                        minSymbols: 3,
                        maxSymbols: 2000
                    }
                },
                feedbackClass: 'feedback',
                fields: $( '.form-control' )
            } );
            
            let addTaskForm = $( '#add-task-form' );
            
            addTaskForm.on( 'submit', function( e )
            {
                e = e || window.event;
                e.preventDefault();
                
                let form = $( this );
                
                validator.check();
                
                if( form.find( '.is-invalid' )[ 0 ] )
                {
                    return false;
                }
                
                let data = new FormData( form[ 0 ] );
                
                ajaxRequest(
                    'POST',
                    form.attr( 'action' ),
                    'text',
                    function( xhr )
                    {
                        let isValid = form.find( '.is-valid' );
                        isValid.val( '' );
                        isValid.removeClass( 'is-valid' );
                        
                        ajaxRequest(
                            'POST',
                            $( '.page-link' ).eq( 0 )[ 0 ] !== undefined ? $( '.page-link' ).eq( 0 ).attr( 'href' ) : '/tasks_list',
                            'json',
                            $.proxy( function( xhr )
                            {
                                let response = xhr.response;

                                this.container.html( response.pagination_content );
                                this.navigationContainer.html( response.pagination_navigation );
                            },
                            {
                                container: contentArea,
                                navigationContainer: paginationLining
                            } )
                        );
                        
                        if( xhr.response )
                        {
                            message = 'Task added'; 
                            interface.actionAlert( 'left', 'success', message );
                        }
                        else
                        {
                            message = 'Task not added'; 
                            interface.actionAlert( 'left', 'failed', message );
                        }
                    },
                    data
                );
            } );
            
            if( '<?php echo $admin_status; ?>' )
            {
                var saveButton = $( '#save-button' );
                
                saveButton.on( 'click', function()
                {
                    var tableTr = $( '.table-tr' );
                    var data = new FormData();
                    
                    tableTr.each( function( tn )
                    {
                        let tr = tableTr.eq( tn );
                        let edited = tr.find( '.edited' );
                        let fields = {};
                        
                        edited.each( function( en )
                        {
                            let field = edited.eq( en );
                            
                            if( field.attr( 'data-field' ) === 'id' )
                            {
                                fields[ field.attr( 'data-field' ) ] = field.attr( 'data-id' );
                            }
                            else if( field.attr( 'data-field' ) !== 'status' )
                            {
                                fields[ field.attr( 'data-field' ) ] = field.text();
                            }
                            else
                            {
                                fields[ field.attr( 'data-field' ) ] = field.find( '.confirm' )[ 0 ] !== undefined ? 1 : 0;
                            }
                        } );
                        
                        if( Object.keys( fields ).length > 1 )
                        {
                            data.append( 'task_data[]', JSON.stringify( fields ) );
                        }
                    } );
                    
                    ajaxRequest(
                        'POST',
                        '/update_handler',
                        'text',
                        function( xhr )
                        {
                            if( xhr.response )
                            {
                                message = 'Task updated'; 
                                interface.actionAlert( 'left', 'success', message );
                            }
                            else
                            {
                                message = 'Task not updted'; 
                                interface.actionAlert( 'left', 'failed', message );
                            }
                        },
                        data
                    );
                } );
                
                contentArea.on( 'click', function( e )
                {
                    e = e || window.event;

                    var target = $( e.target );
                    target = target.hasClass( 'editable' ) ? target : target.parent();

                    if( target.hasClass( 'editable' ) )
                    {
                        if( target.attr( 'data-field' ) !== 'status' )
                        {
                            target.attr( 'contenteditable', true );

                            target.on( 'input', function()
                            {
                                if( ! saveButton.hasClass( 'fade-in' ) )
                                {
                                    saveButton.removeClass( 'fade-out' );
                                    saveButton.addClass( 'fade-in' );
                                }
                                
                                target.addClass( 'edited' );
                            } );
                            
                            target.on( 'blur', function()
                            {
                                $( this ).attr( 'contenteditable', false );
                                $( this ).off( 'input' );
                                $( this ).off( 'blur' );
                            } );
                        }
                        else
                        {
                            let status = target.find( '.status' );
                            
                            if( status.hasClass( 'unconfirm' ) )
                            {
                                status.removeClass( 'unconfirm' );
                                status.addClass( 'confirm' );
                                status.removeClass( 'fa-exclamation-circle' );
                                status.addClass( 'fa-chevron-circle-down' );
                            }
                            else
                            {
                                status.removeClass( 'confirm' );
                                status.addClass( 'unconfirm' );
                                status.removeClass( 'fa-chevron-circle-down' );
                                status.addClass( 'fa-exclamation-circle' );
                            }
                            
                            if( ! saveButton.hasClass( 'fade-in' ) )
                            {
                                saveButton.removeClass( 'fade-out' );
                                saveButton.addClass( 'fade-in' );
                            }
                            
                            target.addClass( 'edited' );
                        }
                    }
                } ); 
            }
        } );
    </script>

<?php

}
else
{
    ob_start();
    
    foreach( $pagination_data[ 'pagination_content' ] as $content_number => $content )
    {
        if( ! $admin_status )
        {
    ?>

        <tr class="table-tr">
            <th><?php echo $content_number + 1; ?></th>
            <td data-field="name"><?php echo $content[ 'name' ]; ?></td>
            <td data-field="email"><?php echo $content[ 'email' ]; ?></td>
            <td data-field="description"><?php echo $content[ 'description' ]; ?></td>
            <td data-field="status">
                <?php

                    if( $content[ 'status' ] == 0 )
                    {
                ?>
                        <i class="fa fa-exclamation-circle status unconfirm" aria-hidden="true"></i>

                <?php

                    }
                    else
                    {

                ?>

                    <i class="fa fa-chevron-circle-down status confirm" aria-hidden="true"></i>

                <?php

                    }

                ?>
                
                <?php
                            
                    if( $content[ 'updated' ] == 1 )
                    {
                ?>
                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>

                <?php

                    }

                ?>
            </td>
        </tr>

    <?php

        }
        else
        {

    ?>

        <tr class="table-tr">
            <th data-field="id" data-id="<?php echo $content[ 'id' ]; ?>" class="edited"><?php echo $content_number + 1; ?></th>
            <td data-field="name"><?php echo $content[ 'name' ]; ?></td>
            <td data-field="email"><?php echo $content[ 'email' ]; ?></td>
            <td data-field="description" class="editable" tabindex="1"><?php echo $content[ 'description' ]; ?></td>
            <td data-field="status" class="editable" tabindex="1">
                <?php

                    if( $content[ 'status' ] == 0 )
                    {
                ?>
                        <i class="fa fa-exclamation-circle status unconfirm" aria-hidden="true"></i>

                <?php

                    }
                    else
                    {

                ?>

                    <i class="fa fa-chevron-circle-down confirm" aria-hidden="true"></i>

                <?php

                    }

                ?>
                
                <?php
                            
                    if( $content[ 'updated' ] == 1 )
                    {
                ?>
                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>

                <?php

                    }

                ?>
            </td>
        </tr>

    <?php

        }
    
    }
    
    $pagination_content = ob_get_clean();
    
    if( isset( $pagination_data[ 'pagination_navigation' ] ) )
    {
        $pagination_navigation = $pagination_data[ 'pagination_navigation' ];
        
        echo json_encode( array(
            'pagination_content' => $pagination_content,
            'pagination_navigation' => $pagination_navigation,
        ) );
    }
    else
    {
        echo json_encode( array(
            'pagination_content' => $pagination_content
        ) );
    }
}

?>
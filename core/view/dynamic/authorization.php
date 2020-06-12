<div class="form-wrapper py-5">
    <form method="post" action="/authorization_handler" enctype="application/x-www-form-urlencoded" class="w-25 w-xs-75 w-sm-50 mx-auto" id="authorization-form">
        <div class="form-group">
            <input type="text" name="login" placeholder="Login" class="form-control mb-3"/>
            <div class="d-block invisible feedback"></div>
        </div>
        <div class="form-group">
            <input type="password" name="password" placeholder="Password" class="form-control mb-3"/>
            <div class="d-block invisible feedback"></div>
        </div>
        <button type="submit" class="btn btn-primary w-100">Submit</button>
    </form>
</div>

<script type="text/javascript" src="<?php echo PATH_TO_JS; ?>validate-fields.js"></script>

<script type="text/javascript">
    $( window ).ready( function()
    {
        var validator = new Validator( {
            fieldsList: {
                'login': {
                    empty: true
                },
                
                'password': {
                    empty: true
                }
            },
            feedbackClass: 'feedback',
            fields: $( '.form-control' )
        } );
        
        let authorizationForm = $( '#authorization-form' );
        
        authorizationForm.on( 'submit', function( e )
        {
            e = e || window.event;
            e.preventDefault();
            
            var form = $( this );
            
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
                    if( xhr.response )
                    {
                        location.assign( '/' );
                    }
                    else
                    {
                        validator.generateFeedback( $( '[name="password"]' ), 'invalid', 'Login or password are incorrect' );
                    }
                },
                data
            );
        } );
    } );
</script>
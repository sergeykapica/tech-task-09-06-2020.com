( function()
{
    window.interface = {
        actionAlert: function( dir, type, text )
        {
            if( dir === 'left' )
            {
                var rootWrapper = $( '#root-wrapper' );
                var message;

                if( type === 'success' )
                {
                    message = '<div class="alert alert-success alert-move-right" role="alert">' + text + '</div>'; 
                }
                else
                {
                    message = '<div class="alert alert-danger alert-move-right" role="alert">' + text + '</div>'; 
                }

                rootWrapper.append( message );

                setTimeout( function()
                {
                    let alert = $( '.alert' );
                    alert.removeClass( 'alert-move-right' );
                    alert.addClass( 'alert-move-left' );

                    alert.on( 'animationend', function()
                    {
                        $( this ).remove();
                    } );
                }, 2000 );
            }
        }
    };
} )();
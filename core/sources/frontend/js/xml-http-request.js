( function()
{
    window.ajaxRequest = function( method, url, type = 'text', callback, data = '' )
    {
        var xhr = window.XMLHttpRequest !== undefined ? new XMLHttpRequest() : new ActiveXObject( "Microsoft.XMLHTTP" );  
        
        function onload()
        {
            if( xhr.readyState === 4 )
            {
                if( xhr.status === 200 )
                {
                    callback( xhr );
                }
            }
        }
        
        xhr.onload = onload;
        //xhr.onreadystatechange = onload;
        
        xhr.open( method, url, true );
        xhr.responseType = type;
        xhr.send( data );
    };
} )();
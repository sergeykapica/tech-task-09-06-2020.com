( function()
{
    function Validator( params )
    {
        var thisObject = this;
        thisObject.params = params;
        
        thisObject.params.fields.on( 'keydown', function()
        {
            let field = $( this );
            
            if( field.hasClass( 'is-invalid' ) && ! field.hasClass( 'is-valid' ) )
            {
                thisObject.removeChecked( field, 'invalid' );
            }
        } );
    }
    
    Validator.prototype.check = function()
    {
        var thisObject = this;
        thisObject.removeChecked( thisObject.params.fields, 'invalid' );
        
        for( let fn in thisObject.params.fieldsList )
        {
            var field = $( '[name="' + fn + '"]' );
            var fieldCurrentParams = thisObject.params.fieldsList[ fn ];
            
            for( let fp in fieldCurrentParams )
            {
                var fieldParamValue = fieldCurrentParams[ fp ];
                
                if( fp === 'empty' && field.val() === '' )
                {
                    thisObject.generateFeedback( field, 'invalid', 'Field must be filled out' );
                    
                    break;
                }
                else if( fp === 'minSymbols' && field.val().length < fieldParamValue )
                {
                    thisObject.generateFeedback( field, 'invalid', 'Minimum number of characters is ' + fieldParamValue + ' symbols' );
                    
                    break;
                }
                else if( fp === 'maxSymbols' && field.val().length > fieldParamValue  )
                {
                    thisObject.generateFeedback( field, 'invalid', 'Maximum number of characters is ' + fieldParamValue + ' symbols' );
                    
                    break;
                }
                else if( fp === 'email' && ! /.+?@.+?\..+/.test( field.val() ) )
                {
                    thisObject.generateFeedback( field, 'invalid', 'Value must be email address' );
                    
                    break;
                }
            }
        }
    };
    
    Validator.prototype.generateFeedback = function( field, type, text = '', feedback = true )
    {
        var thisObject = this;
        
        if( feedback )
        {
            var feedback = field.next( '.' + thisObject.params.feedbackClass )[ 0 ] || field.parent().parent().find( '.' + thisObject.params.feedbackClass )[ 0 ];
            feedback = $( feedback );
            feedback.text( text );
        }
            
        if( type === 'valid' )
        {   
            thisObject.removeChecked( field, 'invalid' );
        }
        else
        {
            thisObject.removeChecked( field, 'valid' );
        }
    };
    
    Validator.prototype.removeChecked = function( field, type )
    {
        var thisObject = this;
        
        if( type === 'invalid' )
        {
            field.removeClass( 'is-invalid' );
            field.addClass( 'is-valid' );

            var feedback = field.next( '.' + thisObject.params.feedbackClass )[ 0 ] || field.parent().parent().find( '.' + thisObject.params.feedbackClass )[ 0 ];
            feedback = $( feedback );
            
            if( feedback[ 0 ] !== undefined )
            {
                feedback.addClass( 'invisible' );
            }
        }
        else
        {
            field.removeClass( 'is-valid' );
            field.addClass( 'is-invalid' );
            
            var feedback = field.next( '.' + thisObject.params.feedbackClass )[ 0 ] || field.parent().parent().find( '.' + thisObject.params.feedbackClass )[ 0 ];
            feedback = $( feedback );
            
            if( feedback[ 0 ] !== undefined )
            {
                feedback.addClass( 'invalid-feedback' );
                feedback.removeClass( 'invisible' );
            }
        }
    };
    
    window.Validator = Validator;
} )();
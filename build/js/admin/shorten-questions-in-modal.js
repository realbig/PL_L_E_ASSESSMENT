( function( $ ) {

	function getParameterByName( name, url ) {
		
		if ( ! url ) {
			url = window.location.href;
		}
		
		name = name.replace( /[\[\]]/g, "\\$&" );
		
		var regex = new RegExp( "[?&]" + name + "(=([^&#]*)|&|#|$)" ),
			results = regex.exec( url );
		
		if ( ! results ) return null;
		if ( ! results[2]) return '';
		
		return decodeURIComponent( results[2].replace( /\+/g, " " ) );
	}

	$( document ).ready( function() {

		if ( adminpage !== 'admin_page_ldAdvQuiz' ) return false;
		
		if ( getParameterByName( 'id' ) !== '1' ) return false;
		
		$( document ).ajaxComplete( function( event, xhr, options ) {
			
			if ( getParameterByName( 'func', options.data ) !== 'statisticLoadUser' ) return false;
			
			$( '#wpProQuiz_user_content tbody a' ).each( function( index, questionLink ) {
				
				var text = $( questionLink ).html();
				
				if ( text.length > 55 ) {
					text = text.substr( 0, 52 ) + "...";
				}
				
				$( questionLink ).html( text );
				
			} );
			
		} );

	} );

} )( jQuery );
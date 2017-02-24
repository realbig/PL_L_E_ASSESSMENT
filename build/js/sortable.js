( function( $ ) {
	
	$( document ).ready( function() {
		
		function reindexSortable( $sortable ) {
			
			$sortable.each( function( sortableIndex, sortable ) {

				$( sortable ).find( '.wpProQuiz_sortable' ).each( function( index, element ) {

					if ( $( element ).find( '.index' ).length > 0 ) {
						$( element ).find( '.index' ).html( ( index + 1 ) + '. ' );
					}
					else {
						$( element ).prepend( '<span class="index">' + ( index + 1 ) + '. </span>' );
					}
					
					$( element ).attr( 'data-currentindex', index + 1 );

				} );

			} );
			
		}
		
		if ( $( '#wpProQuiz_1' ).length > 0 ) {
			
			var $sortable = $( '#wpProQuiz_1 .wpProQuiz_sortable' ).parents( 'ul' );
			
			$sortable.on( 'sortcreate', function( event, ui ) {
				setTimeout( function() {
					reindexSortable( $sortable );
				}, 100 );
			} );
			
			$sortable.on( 'sortstop', function( event, ui ) {
				reindexSortable( $sortable );
			} );
			
		}
		
	} );
	
} )( jQuery );
jQuery( document ).ready( function( $ ) {

	$( '.location-autocomplete' ).each( function (index, element) {

		var hiddenElID = element.id.replace(/-/g, '_');

		var parentID = $(element).attr('data-parent');

		var parent = 0

		$(element).autocomplete({
			minLength: 0,
			//delay: 0,
			create: function(event, ui) {
				
				var input = $(event.target);

				var wasOpen = false;

				var inputGroup = input.get(0).parentElement;

				var button = $(inputGroup).find('button')
					.attr( "tabIndex", -1 )
					.attr( "title", "Mostrar Todos os Itens" )
					.tooltip()
					.on( "mousedown", function() {
						wasOpen = input.autocomplete( "widget" ).is( ":visible" );
					})
					.on( "click", function() {
						input.trigger( "focus" );

						// Close if already visible
						if ( wasOpen ) {
							return;
						}

						// Pass empty string as value to search for, displaying all results
						input.autocomplete( "search", "" );
					});
			
			},
			source: function(request, response) {

				if ( parentID.length > 0 ) {
					parent = $('#' + parentID).val();
				}

				$.ajax({
					url: ajaxurl,
					data: {
						action: 'investim_locations',
						parent: parent,
						q: request.term
					},
					dataType: 'json',
					success: function(data) {
						response(data);
					}
				});

			},
			change: function( event, ui ) {

				if(ui.item) {
					$('#' + hiddenElID).val('id|' + ui.item.id);
				} else {
					$('#' + hiddenElID).val('name|' + this.value);
				}
				
			}
		});

	});

});
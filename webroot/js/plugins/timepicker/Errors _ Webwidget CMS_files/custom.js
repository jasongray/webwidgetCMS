/*
 * custom.js
 *
 * Place your code here that you need on all your pages.
 */

"use strict";

$(document).ready(function(){

	$('table .actions a.trash').click(function(e){
		e.preventDefault();
		var link = $(this);
		var n = noty({
			type: 'confirm',
			text: link.data('alert-msg'),
			buttons: [
				{addClass: 'btn btn-success', text: 'Yes', onClick: function($noty) {
						$noty.close();
						window.location = link.attr('href');
					}
				},
				{addClass: 'btn btn-danger', text: 'No', onClick: function($noty) {
						$noty.close();
					}
				}
			]
		});
	});
	
	if ($.fn.spinner) {

		$( "#spinner-decimal" ).spinner({
			step: 0.01,
			numberFormat: "n"
		});
		
	}
		
});
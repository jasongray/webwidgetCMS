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

	if ($('.ajax-featurepost').length > 0) {
		$('.ajax-featurepost span').click(function(e){
			e.preventDefault();
			var elm = $(this);
			if (elm.hasClass('label-inverse')) {
				var postID = elm.parent().parent('tr').attr('id');
				postID = postID.replace('post-', '');
				$.ajax({
					url: _baseurl+'admin/news/update',
					type: 'POST',
					data: { 'News[id]': postID, 'News[featured]' : 1 },
				}).done(function(d){
					if (d) {
						elm.removeClass('label-inverse').addClass('label-warning');
					}
				});
			} 
			if (elm.hasClass('label-warning')) {
				var postID = elm.parent().parent('tr').attr('id');
				postID = postID.replace('post-', '');
				$.ajax({
					url: _baseurl+'admin/news/update',
					type: 'POST',
					data: { 'News[id]': postID, 'News[featured]' : 0 },
				}).done(function(d){
					if (d) {
						elm.removeClass('label-warning').addClass('label-inverse');
					}
				});
			} 
		});
	}

	if ($('.ajax-publishpost').length > 0) {
		$('.ajax-publishpost span').click(function(e){
			e.preventDefault();
			var elm = $(this);
			if (elm.hasClass('label-warning')) {
				var postID = elm.parent().parent('tr').attr('id');
				postID = postID.replace('post-', '');
				$.ajax({
					url: _baseurl+'admin/news/update',
					type: 'POST',
					data: { 'News[id]': postID, 'News[published]' : 1 },
				}).done(function(d){
					if (d) {
						elm.removeClass('label-warning').addClass('label-success').html('Published');
					}
				});
			} 
			if (elm.hasClass('label-success')) {
				var postID = elm.parent().parent('tr').attr('id');
				postID = postID.replace('post-', '');
				$.ajax({
					url: _baseurl+'admin/news/update',
					type: 'POST',
					data: { 'News[id]': postID, 'News[published]' : 0 },
				}).done(function(d){
					if (d) {
						elm.removeClass('label-success').addClass('label-warning').html('Unpublished');
					}
				});
			} 
		});
	}	

	if ($('.ajax-publishalert').length > 0) {
		$('.ajax-publishalert span').click(function(e){
			e.preventDefault();
			var elm = $(this);
			if (elm.hasClass('label-warning')) {
				var postID = elm.parent().parent('tr').attr('id');
				postID = postID.replace('alert-', '');
				$.ajax({
					url: _baseurl+'admin/alerts/update',
					type: 'POST',
					data: { 'Alert[id]': postID, 'Alert[publish]' : 1 },
				}).done(function(d){
					if (d) {
						elm.removeClass('label-warning').addClass('label-success').html('Published');
					}
				});
			} 
			if (elm.hasClass('label-success')) {
				var postID = elm.parent().parent('tr').attr('id');
				postID = postID.replace('alert-', '');
				$.ajax({
					url: _baseurl+'admin/alerts/update',
					type: 'POST',
					data: { 'Alert[id]': postID, 'Alert[published]' : 0 },
				}).done(function(d){
					if (d) {
						elm.removeClass('label-success').addClass('label-warning').html('Unpublished');
					}
				});
			} 
		});
	}	
	
});
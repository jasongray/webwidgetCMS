/**
 * Core script to handle functions on system pages
 */

var System = function () {
	
	"use strict";
	
	var dofunctions = function() {
		$('.databasetables a').click(function(e){
			e.preventDefault();
			$.post(this.href, '', 'json').done(function(d){
		    	var r = $.parseJSON(d); 
		    	if (r.response == 1) { bootbox.alert(r.msg); 
		    		$.get(_baseurl+'admin/system', function(data){
		    			var newtable = $(data).find('.databasetables');
		    			$('.databasetables').replaceWith(newtable);
		    			dofunctions();
		    		});
		    	} 
		    	if (r.response == 0) { bootbox.alert(r.msg); } 
			});
		});
	}
	
	var doUpdate = function() {
		$('#updateModal #btn-upgrade').click(function(e){
			e.preventDefault();
			$(this).attr('disabled','disabled');
			$('.spinner-icon').show();
			$('.modal-response').empty();
			var msg = $('.progress-bar').data('start');
			$('<p>'+msg+'</p>').appendTo('.modal-response'); 
			$('#updateModal .progress').show();
			$('#updateModal .progress-bar').width('2%');
			$.get(_baseurl+'admin/system/update', function(d){
				var r = $.parseJSON(d);
				if (r.duration == 0) {
					updateProgress(r);
					return;
				} else {
					updateProgress(r);
				$.get(_baseurl+'admin/system/update/task:backup', function(d){
					var r = $.parseJSON(d);
					if (r.duration == 0) {
						updateProgress(r);
						return;
					} else {
					$.get(_baseurl+'admin/system/update/task:download', function(d){
						var r = $.parseJSON(d);
						if (r.duration == 0) {
							updateProgress(r);
							return;
						} else {
							updateProgress(r);
							$.get(_baseurl+'admin/system/update/task:unzip', function(d){
								var r = $.parseJSON(d);
								if (r.duration == 0) {
									updateProgress(r);
									return;
								} else {
									updateProgress(r);
									$.get(_baseurl+'admin/system/update/task:install', function(d){
										var r = $.parseJSON(d);
										if (r.duration == 0) {
											updateProgress(r);
											return;
										} else {
											updateProgress(r);
											$.get(_baseurl+'admin/system/update/task:upsql', function(d){
												var r = $.parseJSON(d);
												if (r.duration == 0) {
													updateProgress(r);
													return;
												} else {
													$.get(_baseurl+'admin/system/update/task:finish', function(d){
														var r = $.parseJSON(d);
														if (d.duration != 0) {
															updateProgress(r);
															$('<i class="icon-fixed-width icon-4x icon-ok text-success"> </i>').appendTo('.modal-response'); 
															setTimeout(function() {
																location.reload();
															}, 3000);
														} else {
															updateProgress(r);
														}
													});
												}
											});
										}
									});
								}
							});
						}
					});
					}
				});
					
				}
			});
		});
	}

	var doProductUpdate = function() {
		$('#updateProductModal #btn-upgrade').click(function(e){
			e.preventDefault();
			$(this).attr('disabled','disabled');
			$('#updateProductModal .spinner-icon').show();
			$('#updateProductModal .modal-response').empty();
			var msg = $('#updateProductModal .progress-bar').data('start');
			$('<p>'+msg+'</p>').appendTo('#updateProductModal .modal-response'); 
			$('#updateProductModal .progress').show();
			$('#updateProductModal .progress-bar').width('2%');
			$.get(_baseurl+'admin/run/updates', function(d){
				var r = $.parseJSON(d);
				if (r.duration == 0) {
					updateProductProgress(r);
					return;
				} else {
					updateProductProgress(r);
					$.get(_baseurl+'admin/run/updates/task:savefile', function(d){
						var r = $.parseJSON(d);
						if (r.duration == 0) {
							updateProductProgress(r);
							return;
						} else {
							updateProductProgress(r);
							$.get(_baseurl+'admin/run/updates/task:create', function(d){
								var r = $.parseJSON(d);
								if (r.duration == 0) {
									updateProductProgress(r);
									return;
								} else {
									updateProductProgress(r);
									$.get(_baseurl+'admin/run/updates/task:install', function(d){
										var r = $.parseJSON(d);
										if (r.duration == 0) {
											updateProductProgress(r);
											return;
										} else {
											updateProductProgress(r);
												$.get(_baseurl+'admin/run/updates/task:rename', function(d){
												var r = $.parseJSON(d);
												if (r.duration == 0) {
													updateProductProgress(r);
													return;
												} else {
													updateProductProgress(r);
													$.get(_baseurl+'admin/run/updates/task:upsql', function(d){
														var r = $.parseJSON(d);
														if (r.duration == 0) {
															updateProductProgress(r);
															return;
														} else {
															updateProductProgress(r);
															$.get(_baseurl+'admin/run/updates/task:categories', function(d){
																var r = $.parseJSON(d);
																if (r.duration == 0) {
																	updateProductProgress(r);
																	return;
																} else {
																	updateProductProgress(r);
																	$.get(_baseurl+'admin/run/updates/task:groups', function(d){
																		var r = $.parseJSON(d);
																		if (r.duration == 0) {
																			updateProductProgress(r);
																			return;
																		} else {
																			updateProductProgress(r);
																			$.get(_baseurl+'admin/run/updates/task:finish', function(d){
																				var r = $.parseJSON(d);
																				if (d.duration != 0) {
																					updateProductProgress(r);
																					$('<i class="icon-fixed-width icon-4x icon-ok text-success"> </i>').appendTo('.modal-response'); 
																					setTimeout(function() {
																						location.reload();
																					}, 1500);
																				} else {
																					updateProductProgress(r);
																				}
																			});
																		}
																	});
																}
															});
														}
													});
												}
											});
										}
									});
								}
							});
						}
					});	
				}
			});
		});
	}	

	var updateProductProgress = function(r) {
		var msg = '<p>'+r.message+'</p>';
		$(msg).appendTo('#updateProductModal .modal-response'); 
		$('#updateProductModal .progress-bar').width(r.duration+'%');
		$('#updateProductModal .progress-bar').data('width', r.duration);
		if (r.duration == 0) {
			$('<i class="icon-fixed-width icon-4x icon-remove text-danger"> </i>').appendTo('#updateProductModal .modal-response');
			$('#updateProductModal .spinner-icon').hide(); 
			setTimeout(function() {
				$('#updateProductModal').modal('hide');
				$('#updateProductModal .modal-response').empty();
				$('#updateProductModal #btn-upgrade').removeAttr('disabled');
			}, 5000);
		}
	}

	var updateProgress = function(r) {
		var msg = '<p>'+r.message+'</p>';
		$(msg).appendTo('.modal-response'); 
		$('#updateModal .progress-bar').width(r.duration+'%');
		$('#updateModal .progress-bar').data('width', r.duration);
		if (r.duration == 0) {
			$('<i class="icon-fixed-width icon-4x icon-remove text-danger"> </i>').appendTo('.modal-response');
			$('.spinner-icon').hide(); 
			setTimeout(function() {
				$('#updateModal').modal('hide');
				$('.modal-response').empty();
				$('#updateModal #btn-upgrade').removeAttr('disabled');
			}, 5000);
		}
	}
	
	var runTask = function(t) {
		if (t == 'backup') {
			if ($('#skipbackup').is(':checked')){
				// skip backup
				return true;
			}
		}
		$.get(_baseurl+'admin/system/update/task:'+t, function(d){
			var r = $.parseJSON(d);
			if (r.duration == 0) {
				updateProgress(r);
				return false;
			} else {
				updateProgress(r);
				return true;
			}
		});
	}
	
	return {
	
		init: function () {
			dofunctions();
			doUpdate();
			doProductUpdate();
		},

	};
	
}();
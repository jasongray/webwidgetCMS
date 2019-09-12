<?php echo $this->Form->input('params.slidertransition', array('div' => 'form-group', 'class' => 'select2-select-00 col-md-12 full-width-fix', 'label' => array('text' => __('Slider Transition'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>', 'empty' => '', 'options' => array('fade' => 'Fade', 'backSlide' => 'Back Slide', 'goDown' => 'Go Down', 'fadeUp' => 'Fade Up')));?>
<?php echo $this->Form->input('params.sliderspeed', array('div' => 'form-group', 'class' => 'form-control input-width-mini', 'label' => array('text' => __('Slider Speed (ms)'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>'));?>
<div class="form-group">
	<div class="widget-content">
				<div id="upload-wrapper"></div>
				<?php 
				$max_upload = (int)(ini_get('upload_max_filesize'));
				$max_post = (int)(ini_get('post_max_size'));
				$memory_limit = (int)(ini_get('memory_limit'));
				$upload_mb = min($max_upload, $max_post, $memory_limit);?>
				<small>(Max file size: <?php echo $upload_mb.'MB';?>)</small>
				<div id="uploaded"></div>
				<div class="clearfix"></div>
	</div>
	<div class="col-md-12 file-browser">
		<?php if(!empty($this->data['params']['images'])) { ?>
		<ul class="data images">
		<?php foreach($this->data['params']['images'] as $k => $v) { ?>	
			<li id="file_<?php echo $v;?>" class="files" data-index="<?php echo $k;?>">
				<?php echo $this->Html->link('<span class="badge" data-badge="file_'.$k.'" data-file="'.$v.'">X</span>', array('controller' => 'modules', 'action' => 'functions', 'module' => 'mod_slideshow', 'function' => 'remove', 'admin' => 'admin', 'plugin' => false, 'id' => $this->data['Module']['id']), array('escape' => false, 'class' => 'badge-wrapper'));?>
				<?php echo $this->Resize->image('slideshows' . DS . $v, 200, 200, true);?>
			</li>
		<?php } ?>
		</ul>
		<?php } ?>
		<div class="clearfix"></div>
	</div>

</div>

<?php echo $this->Html->script(array('uploadr'), array('inline' => false));?>
<?php echo $this->Html->scriptBlock("
$(window).load(function() {
	var folder = 'slideshows';
	var removefiles = function(){
		$('.badge').click(function(e){
			e.preventDefault();
			var _id = $(this).data('badge').replace('file_', '');
			$.ajax({
				type: 'POST', 
				url: $(this).parent().attr('href'), 
				data: {'index': _id, 'folder': folder}, 
				success: function(d,t,x){ 
					if (d == 1) {
						$('[data-index=\"'+_id+'\"]').fadeOut('slow', function(){ $(this).remove; });
						var n = noty({type: 'success', text: '".__('File has been successfully removed')."'});
					} else {
						//alert('Error removing file!');
						var n = noty({type: 'error', text: '".__('Error removing file!')."'});
					}
				}, 
				error: function(x,t,e){ 
					var n = noty({type: 'error', text: '".__('Unexplained error')."'});
				}
			});
		});
	}
	var sortablefiles = function(){
		$('.images').sortable({ 
			update: function(e, u){ 
				$.post('".$this->Html->url(array('controller' => 'modules', 'action' => 'functions', 'admin' => 'admin', 'module' => 'mod_slideshow', 'function' => 'reorder', 'id' => $this->data['Module']['id']))."', 
					{ order: $('.images').sortable('toArray').join(',') }
				); 
			}
		});
	}
	$('#upload-wrapper').mfupload({
		type		: 'jpg,png,gif,jpeg',
		maxsize		: 3000,
		post_upload	: '" . $this->Html->url(array('controller' => 'modules', 'action' => 'functions', 'admin' => 'admin', 'module' => 'mod_slideshow', 'function' => 'upload', 'id' => $this->data['Module']['id'])) . "',
		folder		: folder,
		ini_text	: '".__('Drag your files to here  or  click to select files')."',
		over_text	: '".__('Drop your file here')."',
		over_col	: 'white',
		over_bkcol	: 'grey',
		file_element: 'mf_file_upload',
		
		init		: function(){		
			$('#uploaded').empty();
		},
		
		start		: function(result){		
			$('#uploaded').append('<div id=\"FILE'+result.fileno+'\" class=\"files\" ><div class=\"fname\">'+result.filename+'</div><div class=\"prog\"><div id=\"PRO'+result.fileno+'\" class=\"prog_inn\"></div></div></div>');	
		},

		loaded		: function(result){
			$('#PRO'+result.fileno).parent().remove();
			$('#FILE'+result.fileno).html('Uploaded: '+result.filename+' ('+result.size+')');	
			if (result.error) {
				alert(result.error);
			}		
		},

		progress	: function(result){
			$('#PRO'+result.fileno).css('width', result.perc+'%');
			
		},

		error		: function(error){
			alert(error);
		},

		completed	: function(error){
			$('.loading').remove();
			$('.file-browser').append('<div class=\"loading\"></div>');
			$('.file-browser').load('" . $this->Html->url(array('controller' => 'modules', 'action' => 'edit', 'admin' => 'admin', $this->data['Module']['id'])) . " .file-browser ul', function(){ removefiles(); sortablefiles(); $('.loading').fadeOut();});
		}
        
	});   
	removefiles();
	sortablefiles();
});", array('inline' => false));?>
<div class="form-group">
	<label class="col-md-2 control-label"><?php echo __('Gallery Images');?></label>
	<div class="col-md-8 file-browser">
		<?php if(!empty($files[1])) { ?>
		<ul class="data">
		<?php $i = 0;?>
		<?php foreach($files[1] as $f) { ?>	
			<?php $file = new File(APP . WEBROOT_DIR . DS . 'files' . DS . 'projects' . DS . $this->data['Project']['id'] . DS . $f)?>
			<?php $fileinfo = $file->info();?>
			<li id="file_<?php echo $i;?>" class="files">
				<?php echo $this->Html->link('<span data-badge="badge_'.$i.'" class="badge">X</span>', array('controller' => 'projects', 'action' => 'removefile', 'id' => $this->data['Project']['id'], 'file' => $f, 'admin' => 'admin', 'plugin' => false), array('escape' => false, 'class' => 'badge-wrapper'));?>
				<a class="files" href="<?php echo $this->Html->url(array('controller' => 'projects', 'action' => 'download', 'id' => $this->data['Project']['id'], 'file' => $f, 'admin' => false));?>">
					<span class="icon file f-<?php echo $fileinfo['extension'];?>">.<?php echo $fileinfo['extension'];?></span>
					<span class="name"><?php echo $fileinfo['basename'];?></span>
					<span class="details"><?php echo $this->Number->toReadableSize($fileinfo['filesize']);?></span>
				</a>
			</li>
			<?php $i++;?>
		<?php } ?>
		</ul>
		<?php } ?>
	</div>
	<div class="col-md-2">
		<div id="upload-wrapper"></div>
		<?php 
		$max_upload = (int)(ini_get('upload_max_filesize'));
		$max_post = (int)(ini_get('post_max_size'));
		$memory_limit = (int)(ini_get('memory_limit'));
		$upload_mb = min($max_upload, $max_post, $memory_limit);?>
		<small>(Max file size: <?php echo $upload_mb.'MB';?>)</small>
		<div id="uploaded"></div>
	</div>
</div>

<?php echo $this->Html->script(array('uploadr'), array('inline' => false));?>
<?php echo $this->Html->scriptBlock("
function removefiles() {
	$('.badge').click(function(e){
		e.preventDefault();
		var _id = $(this).data('badge').replace('badge_', '');
		$.ajax({
			type: 'POST', 
			url: $(this).parent().attr('href'),  
			success: function(d,t,x){ 
				if (d == 1) {
					$('#file_'+_id).fadeOut('slow', function(){ $(this).remove; });
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
$(window).load(function() {
	$('#upload-wrapper').mfupload({
		type		: 'jpg,png,gif,jpeg,pdf,xls,xlsx,doc,docx,ppt,pptx,txt,mpg,mov,mp3,wav,avi',
		maxsize		: 3000,
		post_upload	: '" . $this->Html->url(array('controller' => 'galleries', 'action' => 'upload', 'admin' => 'admin', $this->data['Gallery']['id'])) . "',
		folder		: '',
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
			$('.file-browser').load('" . $this->Html->url(array('controller' => 'galleries', 'action' => 'edit', 'admin' => 'admin', $this->data['Gallery']['id'])) . " .file-browser ul', function(){ removefiles(); $('.loading').fadeOut();});
		}
        
	});   
	removefiles();
});", array('inline' => false));?>
<?php if (isset($this->data['Project']['id'])) { ?>
<?php echo $this->Html->script('plugins/uploadr/uploader.js', array('inline' => false));?>
<?php echo $this->Html->scriptBlock("
$(window).load(function() {
	$('#upload').mfupload({
		type		: 'jpg,png,tif,jpeg,pdf,doc,docx,xls,xlsx,ppt,pptx,mp3,avi,mp4,mov,flv',
		maxsize		: 300,
		post_upload	: _baseurl+'projects/upload/'+$('#ProjectId').val(),
		folder		: 'files/',
		ini_text	: 'Drag your files to here or click to select files',
		over_text	: 'Drop Here',
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
			$('.portlet-content.right').load(_baseurl+'admin/projects/edit/'+$('#ProjectId').val()+' .portlet-content.right', function(){ $('#foldername').val(''); removefiles();});
		}
        
	});   
	removefiles();
});
function removefiles() {
	$('.remove').click(function(e){
		e.preventDefault();
		$.ajax({
			type: 'POST', 
			url: _baseurl+'projects/remfile', 
			data: { 'folder': $('#folderpath').val(), 'file': $(this).attr('title') }, 
			success: function(d,t,x){ 
				if (d == 1) {
					alert('File removed'); 
					$('#foldertable').load($('#url').val()+' #foldertable .innertable', function(){ removefiles();});
				} else {
					alert('Error removing file!');
				}
			}, 
			error: function(x,t,e){ 
				alert('Error removing file!');
			}
		});
	});
}", array('inline' => false));?>
<?php } ?>
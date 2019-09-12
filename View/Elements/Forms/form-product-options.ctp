<?php echo $this->Html->link('<i class="icon-plus"></i> ' . __('Add Option'), array('controller' => 'productOptions', 'action' => 'add'), array('escape' => false, 'class' => 'btn btn-warning', 'id' => 'addoptions'));?>
<table id="productOptions" class="table table-hover table-striped">
</table>
<div id="productOptionModal" class="modal fade" style="display: none;" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button aria-hidden="true" data-dismiss="modal" class="close" type="button">X</button>
				<h4 class="modal-title"><?php echo __('Add Product Option');?></h4>
			</div>
			<div class="modal-body">
			</div>
			<div class="modal-footer">
				<button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
				<button data-dismiss="modal" class="btn btn-primary" type="button" id="saveoption">Save changes</button>
			</div>
		</div>
	</div>
</div>
<?php echo $this->Html->scriptBlock("
$(document).ready(function() {
	$('#addoptions').click(function(e){
		e.preventDefault();
		$('#productOptionModal .modal-body').load('".$this->Html->url(array('controller' => 'product_options', 'action' => 'add', 'product_id' => $this->request->data['Product']['id'], 'layout' => 'ajax', 'admin' => 'admin', 'plugin' => false))." #ProductOptionAdminAddForm', function(){
				$('#ProductOptionAdminAddForm .form-actions').remove();
				$('#canceloption').click(function(e){
					e.preventDefault();
					$('#productOptionModal').modal('hide');
				});
				$('#saveoption').click(function(e){
					e.preventDefault();
					$('#productOptionModal .modal-body').prepend('<div class=\"ajax-product-option-loader\"></div>');
					$.ajax({ 
						url: '".$this->Html->url(array('controller' => 'product_options', 'action' => 'add', 'product_id' => $this->request->data['Product']['id'], 'layout' => 'ajax', 'admin' => 'admin', 'plugin' => false))."',
						type: 'POST',
						data: $('#ProductOptionAdminAddForm').serialize(),
						error: function(){ alert(); },
						success: function(data){ 
							getProductOptions();
							$('#productOptionModal').modal('hide');
						}
					});
				});
				
			}
		);
		$('#productOptionModal').modal('show');
	});
	
	var getProductOptions = function(){
		$('.big-loader').remove();
		$('#productOptions').prepend('<div class=\"big-loader\"></div>');
		$('#productOptions').load('".$this->Html->url(array('controller' => 'product_options', 'action' => 'index', 'product_id' => $this->request->data['Product']['id'], 'admin' => 'admin', 'plugin' => false))." #productOptions > *', 
		function(){ 
			$('.big-loader').remove(); 
			remProductOptions();
			$('.bs-colorpicker').colorpicker();
		});
	};
	
	var remProductOptions = function() {
		$('.trash').click(function(e){
			e.preventDefault();
			var _tr = 'popt-'+$(this).data('myid');
			$.ajax({
				url: $(this).attr('href'),
				type: 'POST',
				success: function(data){
					if (data == 1){
						$('tr[data-row=\"'+_tr+'\"]').fadeOut('slow', function(){
							$(this).remove();
						});
					}
				}
			});
		});
	}
	
	getProductOptions();
	remProductOptions();
});
", array('inline' => false));?>
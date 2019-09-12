<div class="row">
	<div class="col-md-12">
		<div class="alert alert-warning fade in">
			<i data-dismiss="alert" class="icon-remove close"></i>
			<strong><?php echo __(sprintf('New update avaliable, version %s', $update));?></strong>
			<?php echo $this->Html->link(__('Click here to update'), '#updateModal', array('class' => 'btn btn-xs btn-inverse right', 'data-toggle' => 'modal'));?>
		</div>
	</div>
</div>
<div id="updateModal" class="modal fade" style="display: none;" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"><?php echo __('WebwidgetCMS Upgrader');?></h4>
				<div class="spinner-icon" style="display: none;"></div>
			</div>
			<div class="modal-body">
				<div class="progress progress-striped active" style="display: none;">
					<div style="width: 0%" class="progress-bar progress-bar-info" data-width="-1" data-start="<?php echo __('Commencing update...');?>"></div>
				</div>
				<div class="modal-response">
					
				</div>
			</div>
			<div class="modal-footer">
				<button id="btn-upgrade" class="btn btn-primary" type="button"><?php echo __('Upgrade');?></button>
				<button data-dismiss="modal" class="btn btn-default" type="button"><?php echo __('Exit');?></button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>
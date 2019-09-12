<div class="row">
	<div class="col-md-12">
		<div class="alert alert-warning fade in">
			<i data-dismiss="alert" class="icon-remove close"></i>
			<strong><?php echo __(sprintf('New update avaliable, version %s', $update));?></strong>
			<?php echo $this->Html->link(__('Click here to update'), array('controller' => 'system', 'action' => 'index', 'admin' => 'admin', 'plugin' => false), array('class' => 'btn btn-xs btn-inverse right', 'data-toggle' => 'modal'));?>
		</div>
	</div>
</div>
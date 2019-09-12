<?php echo $this->Form->create('Alert', array('class' => 'form-horizontal row-border', 'type' => 'file'));?>
<?php echo $this->Form->input('title', array('div' => 'form-group', 'class' => 'form-control', 'label' => array('text' => __('Title'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>')); ?>
<div class="form-group">
	<label class="col-md-2 control-label"><?php echo __('Publish');?></label>
	<div class="col-md-10">
		<?php echo $this->Form->input('publish', array('div' => false, 'label' => false, 'class' => 'uniform', 'type' => 'checkbox')); ?>
	</div>
</div>
<div class="form-group">
	<?php echo $this->Form->input('content', array('div' => false, 'label' => array('text' => __('Content'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>', 'class' => 'form-control'));?>
</div>
<?php echo $this->Form->input('hexcode', array('div' => 'form-group', 'class' => 'form-control bs-colorpicker', 'label' => array('text' => __('Colour'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-4">', 'after' => '</div>')); ?>
<?php $_date = empty($this->data['Alert']['start_date'])? date('d-m-Y H:i:s'): date('d-m-Y H:i:s', strtotime($this->data['Alert']['start_date']));?>
<?php echo $this->Form->input('start_date', array('div' => 'form-group', 'class' => 'form-control', 'label' => array('text' => __('Start publish date'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-6">', 'after' => '</div>', 'type' => 'text', 'value' => $_date));?>
<?php $_date2 = !empty($this->data['Alert']['end_date'])? date('d-m-Y H:i:s', strtotime($this->data['Alert']['end_date'])): '';?>
<?php echo $this->Form->input('end_date', array('div' => 'form-group', 'class' => 'form-control', 'label' => array('text' => __('End publish date'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-6">', 'after' => '</div>', 'type' => 'text', 'value' => $_date2));?>
<div class="form-actions">
<?php
	echo $this->Form->hidden('id');
	echo $this->Form->submit('Save', array('class'=>'btn btn-primary', 'div' => false)); 
	echo $this->Html->link('Cancel', array('controller' => 'alerts', 'action' => 'cancel', 'admin' => 'admin', 'plugin' => false), array('class' => 'btn btn-orange'));
	if(!empty($this->data['Alerts']['id'])){
		echo $this->Html->link('Delete', array('controller' => 'alerts', 'action' => 'delete', 'admin' => 'admin', 'plugin' => false, $this->data['Alerts']['id']), array('class' => 'btn btn-grey'));
	}
?>	
</div>
<?php echo $this->Form->end();?>
<?php echo $this->Html->css(array('plugins/jquery-ui', 'plugins/timepicker'), array('block' => 'css'));?>
<?php echo $this->Html->script(array('plugins/bootstrap-colorpicker/bootstrap-colorpicker.min.js', 'plugins/timepicker/timepicker.js'), array('inline' => false));?>
<?php echo $this->Html->scriptBlock("
$(document).ready(function(){
	if ($('#AlertStartDate').length > 0) {
		$('#AlertStartDate').datetimepicker({
			formatDate: 'Y-m-d', 
			formatTime: 'H:i',
			defaultDate: '".date('Y-m-d', strtotime($this->data['Alert']['start_date']))."',
			defaultTime: '".date('H:i', strtotime($this->data['Alert']['start_date']))."',
		});
	}
	if ($('#AlertEndDate').length > 0) {
		$('#AlertEndDate').datetimepicker({
			formatDate: 'Y-m-d', 
			formatTime: 'H:i',
			defaultDate: '".date('Y-m-d', strtotime($this->data['Alert']['end_date']))."',
			defaultTime: '".date('H:i', strtotime($this->data['Alert']['end_date']))."',
		});
	}
});
" , array('inline' => false));?>
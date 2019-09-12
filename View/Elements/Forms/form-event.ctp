<?php echo $this->Html->css(array('plugins/jquery-ui', 'plugins/timepicker'), array('block' => 'css'));?>
<?php echo $this->Form->input('title', array('div' => 'form-group', 'class' => 'form-control', 'label' => array('text' => __('Title'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>')); ?>
<?php echo $this->Form->input('category_id', array('div' => 'form-group', 'class' => 'form-control', 'label' => array('text' => __('Category'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>', 'empty' => '')); ?>
<div class="form-group">
	<label class="col-md-2 control-label"><?php echo __('Publish');?></label>
	<div class="col-md-10">
		<?php echo $this->Form->input('published', array('div' => false, 'label' => false, 'class' => 'uniform', 'type' => 'checkbox')); ?>
	</div>
</div>
<div class="form-group">
	<?php echo $this->Form->input('intro', array('div' => false, 'label' => array('text' => __('Intro Text'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>', 'type' => 'textarea', 'class' => 'form-control wysiwyg', 'rows' => 10));?>
</div>
<div class="form-group">
	<?php echo $this->Form->input('text', array('div' => false, 'label' => array('text' => __('Content'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>', 'type' => 'textarea', 'class' => 'form-control wysiwyg', 'rows' => 27));?>
</div>
<div class="form-actions">
<?php
	echo $this->Form->hidden('id');
	echo $this->Form->submit('Save', array('class'=>'btn btn-primary', 'div' => false)); 
	echo $this->Html->link('Cancel', array('controller' => 'events', 'action' => 'cancel', 'admin' => 'admin', 'plugin' => false), array('class' => 'btn btn-orange'));
	if(!empty($this->data['Event']['id'])){
		echo $this->Html->link('Delete', array('controller' => 'events', 'action' => 'delete', 'admin' => 'admin', 'plugin' => false, $this->data['Event']['id']), array('class' => 'btn btn-grey'));
	}
?>	
</div>
<?php echo $this->Html->script(array('plugins/timepicker/timepicker.js'), array('inline' => false));?>
<?php echo $this->Html->scriptBlock("
$(document).ready(function(){
	if ($('.datetimepicker').length > 0) {
		$('.datetimepicker').datetimepicker({
			formatDate: 'Y-m-d', 
			formatTime: 'H:i',
			defaultDate: '".date('Y-m-d', $this->data['Event']['datetime'])."',
			defaultTime: '".date('H:i', $this->data['Event']['datetime'])."',
		});
	}
});
" , array('inline' => false));?>
<?php echo $this->element('forms-js');?>
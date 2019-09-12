<?php echo $this->Html->css(array('plugins/jquery-ui'), array('block' => 'css'));?>
<?php echo $this->Form->input('name', array('div' => 'form-group', 'class' => 'form-control', 'label' => array('text' => __('Title'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>')); ?>
<div class="form-group">
	<label class="col-md-2 control-label"><?php echo __('Publish');?></label>
	<div class="col-md-10">
		<?php echo $this->Form->input('published', array('div' => false, 'label' => false, 'class' => 'uniform', 'type' => 'checkbox')); ?>
	</div>
</div>
<div class="form-group">
	<?php echo $this->Form->input('full_text', array('div' => false, 'label' => array('text' => __('Content'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>', 'type' => 'textarea', 'class' => 'form-control wysiwyg', 'rows' => 27));?>
</div>
<div class="form-actions">
<?php
	echo $this->Form->hidden('id');
	echo $this->Form->submit('Save', array('class'=>'btn btn-primary', 'div' => false)); 
	echo $this->Html->link('Cancel', array('controller' => 'sponsors', 'action' => 'cancel', 'admin' => 'admin', 'plugin' => false), array('class' => 'btn btn-orange'));
	if(!empty($this->data['Sponsor']['id'])){
		echo $this->Html->link('Delete', array('controller' => 'sponsors', 'action' => 'delete', 'admin' => 'admin', 'plugin' => false, $this->data['Sponsor']['id']), array('class' => 'btn btn-grey'));
	}
?>	
</div>
<?php echo $this->element('forms-js');?>
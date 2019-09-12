<?php echo $this->Form->create('Menu', array('class' => 'form-horizontal row-border'));?>
<?php echo $this->Form->input('title', array('div' => 'form-group', 'class' => 'form-control', 'label' => array('text' => __('Menu Title'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>')); ?>
<?php echo $this->Form->input('unique', array('div' => 'form-group', 'class' => 'form-control', 'label' => array('text' => __('Unique Name'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>')); ?>
<div class="form-group">
	<label class="col-md-2 control-label"><?php echo __('Publish');?></label>
	<div class="col-md-10">
		<?php echo $this->Form->input('published', array('div' => false, 'label' => false, 'class' => 'uniform', 'type' => 'checkbox')); ?>
	</div>
</div>
<div class="form-actions">
<?php
	echo $this->Form->hidden('id');
	echo $this->Form->submit('Save', array('class'=>'btn btn-primary', 'div' => false)); 
	echo $this->Html->link('Cancel', array('controller' => 'menus', 'action' => 'cancel', 'admin' => 'admin', 'plugin' => false), array('class' => 'btn btn-orange'));
	if(!empty($this->data['Role']['id'])){
		echo $this->Html->link('Delete', array('controller' => 'menus', 'action' => 'delete', 'admin' => 'admin', 'plugin' => false, $this->data['Menu']['id']), array('class' => 'btn btn-grey'));
	}
?>	
</div>
<?php echo $this->Form->end();?>
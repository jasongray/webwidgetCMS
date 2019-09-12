<?php echo $this->Html->css(array('plugins/jquery-ui'), array('block' => 'css'));?>
<?php echo $this->Form->input('subheading', array('div' => 'form-group', 'class' => 'form-control', 'label' => array('text' => __('Short Title'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>')); ?>
<div class="form-group">
	<?php echo $this->Form->input('intro_text', array('div' => false, 'label' => array('text' => __('Intro'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>', 'type' => 'textarea', 'class' => 'form-control wysiwyg', 'rows' => 10));?>
</div>
<?php echo $this->Form->input('title', array('div' => 'form-group', 'class' => 'form-control', 'label' => array('text' => __('Main Title'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>')); ?>
<div class="form-group">
	<?php echo $this->Form->input('full_text', array('div' => false, 'label' => array('text' => __('Content'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>', 'type' => 'textarea', 'class' => 'form-control wysiwyg', 'rows' => 27));?>
</div>
<div class="form-actions">
<?php
	echo $this->Form->hidden('id');
	echo $this->Form->submit('Save', array('class'=>'btn btn-primary', 'div' => false)); 
	echo $this->Html->link('Cancel', array('controller' => 'news', 'action' => 'cancel', 'admin' => 'admin', 'plugin' => false), array('class' => 'btn btn-orange'));
	if(!empty($this->data['News']['id'])){
		echo $this->Html->link('Delete', array('controller' => 'news', 'action' => 'delete', 'admin' => 'admin', 'plugin' => false, $this->data['News']['id']), array('class' => 'btn btn-grey'));
	}
?>	
</div>
<?php echo $this->element('forms-js');?>
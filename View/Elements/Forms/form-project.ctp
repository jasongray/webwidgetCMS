<?php echo $this->Form->input('title', array('div' => 'form-group', 'class' => 'form-control', 'label' => array('text' => __('Project Title'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>')); ?>
<div class="form-group">
	<label class="col-md-2 control-label"><?php echo __('Publish');?></label>
	<div class="col-md-10">
		<?php echo $this->Form->input('published', array('div' => false, 'label' => false, 'class' => 'uniform', 'type' => 'checkbox')); ?>
	</div>
</div>
<?php echo $this->Form->input('member_only', array('div' => 'form-group', 'class' => 'select2-select-00 col-md-12 full-width-fix', 'label' => array('text' => __('Private Project'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>', 'empty' => '', 'options' => array(0 => 'No', 1 => 'Yes'))); ?>
<?php echo $this->Form->input('category_id', array('div' => 'form-group', 'class' => 'select2-select-00 col-md-12 full-width-fix', 'label' => array('text' => __('Category'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>', 'empty' => '')); ?>
<?php echo $this->Form->input('intro_text', array('div' => 'form-group', 'label' => array('text' => __('Intro Text'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>', 'type' => 'textarea', 'class' => 'form-control wysiwyg', 'rows' => 10));?>
<?php echo $this->Form->input('show_intro', array('div' => 'form-group', 'class' => 'select2-select-00 col-md-12 full-width-fix', 'label' => array('text' => __('Show Intro Text'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>', 'empty' => '', 'options' => array(0 => 'No', 1 => 'Yes'))); ?>
<?php echo $this->Form->input('text', array('div' => 'form-group', 'label' => array('text' => __('Text'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>', 'type' => 'textarea', 'class' => 'form-control wysiwyg', 'rows' => 15));?>
<div class="form-actions">
<?php
	echo $this->Form->hidden('id');
	echo $this->Form->submit('Save', array('class'=>'btn btn-primary', 'div' => false)); 
	echo $this->Html->link('Cancel', array('controller' => 'projects', 'action' => 'cancel', 'admin' => 'admin', 'plugin' => false), array('class' => 'btn btn-orange'));
	if(!empty($this->data['Project']['id'])){
		echo $this->Html->link('Delete', array('controller' => 'projects', 'action' => 'delete', 'admin' => 'admin', 'plugin' => false, $this->data['Project']['id']), array('class' => 'btn btn-grey'));
	}
?>	
</div>
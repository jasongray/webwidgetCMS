<?php echo $this->Form->create('Category', array('class' => 'form-horizontal row-border', 'type' => 'file'));?>
<?php echo $this->Form->input('title', array('div' => 'form-group', 'class' => 'form-control', 'label' => array('text' => __('Title'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>')); ?>	
<div class="form-group">
	<label class="col-md-2 control-label"><?php echo __('Publish');?></label>
	<div class="col-md-10">
		<?php echo $this->Form->input('published', array('div' => false, 'label' => false, 'class' => 'uniform', 'type' => 'checkbox')); ?>
	</div>
</div>
<div class="form-group">
	<label class="col-md-2 control-label"><?php echo __('Category Image');?></label>
	<div class="col-md-4">
		<?php echo $this->Form->input('Image.file', array('type' => 'file', 'data-style' => 'fileinput', 'div' => false, 'label' => false));?>
		<div class="avatar">
		<?php if (!empty($this->data['Category']['image'])) {
			echo $this->Resize->image('categories/'.$this->data['Category']['image'], 250, 250, true, array('alt' => ''));
			echo $this->Html->link('<span class="badge">X</span>', array('controller' => 'categories', 'action' => 'removeImage', $this->data['Category']['id'], 'admin' => 'admin', 'plugin' => false), array('escape' => false));
		}?>
		</div>
	</div>
	<label class="col-md-2 control-label"><?php echo __('Category Type');?></label>
	<div class="col-md-4">
		<?php echo $this->Form->input('type', array('div' => false, 'class' => 'select2-select-00 col-md-12 full-width-fix', 'label' =>false, 'between' => '', 'after' => '', 'empty' => '', 'options' => array(1 => __('Product'), 2 => __('Blog'), 3 => __('Content'), 4 => __('Events')))); ?>
	</div>
</div>
<?php echo $this->Form->input('parent_id', array('div' => 'form-group', 'class' => 'select2-select-00 col-md-12 full-width-fix', 'label' => array('text' => __('Parent Item'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>', 'options' => $parents, 'empty' => '')); ?>
<?php echo $this->Form->input('show_image', array('div' => 'form-group', 'class' => 'select2-select-00 col-md-12 full-width-fix', 'label' => array('text' => __('Show Image'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>', 'empty' => '', 'options' => array(0 => 'No', 1 => 'Yes'))); ?>
<?php echo $this->Form->input('image_position', array('div' => 'form-group', 'class' => 'select2-select-00 col-md-12 full-width-fix', 'label' => array('text' => __('Image Position'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>', 'empty' => '', 'options' => array('left' => 'Left', 'right' => 'Right'))); ?>
<?php echo $this->Form->input('description', array('div' => 'form-group', 'label' => array('text' => __('Description'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>', 'type' => 'textarea', 'class' => 'form-control wysiwyg', 'rows' => 15));?>
<?php echo $this->Form->input('show_text', array('div' => 'form-group', 'class' => 'select2-select-00 col-md-12 full-width-fix', 'label' => array('text' => __('Show Description'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>', 'empty' => '', 'options' => array(0 => 'No', 1 => 'Yes'))); ?>
<div class="form-actions">
<?php
	echo $this->Form->hidden('id');
	echo $this->Form->submit('Save', array('class'=>'btn btn-primary', 'div' => false)); 
	echo $this->Html->link('Cancel', array('controller' => 'categories', 'action' => 'cancel', 'admin' => 'admin', 'plugin' => false), array('class' => 'btn btn-orange'));
	if(!empty($this->data['Category']['id'])){
		echo $this->Html->link('Delete', array('controller' => 'categories', 'action' => 'delete', 'admin' => 'admin', 'plugin' => false, $this->data['Category']['id']), array('class' => 'btn btn-grey'));
	}
?>	
</div>
<?php echo $this->Form->end();?>
<?php echo $this->Form->create('Brand', array('class' => 'form-horizontal row-border', 'type' => 'file'));?>
<?php echo $this->Form->input('title', array('div' => 'form-group', 'class' => 'form-control', 'label' => array('text' => __('Title'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>')); ?>	
<div class="form-group">
	<label class="col-md-2 control-label"><?php echo __('Publish');?></label>
	<div class="col-md-10">
		<?php echo $this->Form->input('published', array('div' => false, 'label' => false, 'class' => 'uniform', 'type' => 'checkbox')); ?>
	</div>
</div>
<div class="form-group">
	<label class="col-md-2 control-label"><?php echo __('Brand Image');?></label>
	<div class="col-md-4">
		<?php echo $this->Form->input('Image.file', array('type' => 'file', 'data-style' => 'fileinput', 'div' => false, 'label' => false));?>
		<div class="avatar">
		<?php if (!empty($this->data['Brand']['image'])) {
			echo $this->Html->image('brands/' . $this->data['Brand']['image'], array('alt' => ''));
			echo $this->Html->link('<span class="badge">X</span>', array('controller' => 'brands', 'action' => 'removeImage', $this->data['Brand']['id'], 'admin' => 'admin', 'plugin' => false), array('escape' => false));
		}?>
		</div>
	</div>
</div>
<?php echo $this->Form->input('description', array('div' => 'form-group', 'label' => array('text' => __('Description'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>', 'type' => 'textarea', 'class' => 'form-control wysiwyg', 'rows' => 15));?>
<?php echo $this->Form->input('show_desc', array('div' => 'form-group', 'class' => 'select2-select-00 col-md-12 full-width-fix', 'label' => array('text' => __('Show Description'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-2">', 'after' => '</div>', 'empty' => '', 'options' => array(0 => 'No', 1 => 'Yes'))); ?>
<div class="form-actions">
<?php
	echo $this->Form->hidden('id');
	echo $this->Form->submit('Save', array('class'=>'btn btn-primary', 'div' => false)); 
	echo $this->Html->link('Cancel', array('controller' => 'brands', 'action' => 'cancel', 'admin' => 'admin', 'plugin' => false), array('class' => 'btn btn-orange'));
	if(!empty($this->data['Brand']['id'])){
		echo $this->Html->link('Delete', array('controller' => 'brands', 'action' => 'delete', 'admin' => 'admin', 'plugin' => false, $this->data['Brand']['id']), array('class' => 'btn btn-grey'));
	}
?>	
</div>
<?php echo $this->Form->end();?>
<div class="form-group">
	<label class="col-md-2 control-label"><?php echo __('Leading Image');?></label>
	<div class="col-md-10">
		<?php echo $this->Form->input('Image.file', array('type' => 'file', 'data-style' => 'fileinput', 'div' => false, 'label' => false));?>
		<div class="avatar">
		<?php if(!empty($this->data['Project']['image']) ) { 
			echo $this->Resize->image('projects/'.$this->data['Project']['image'], 300, 200, true, array('alt' => ''));
			echo $this->Html->link('<span class="badge">X</span>', array('controller' => 'projects', 'action' => 'removeImage', $this->data['Project']['id'], 'admin' => 'admin', 'plugin' => false), array('escape' => false));
		}?>
		</div>
	</div>
</div>
<?php echo $this->Form->input('show_image', array('div' => 'form-group', 'class' => 'select2-select-00 col-md-12 full-width-fix', 'label' => array('text' => __('Show Image'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>', 'empty' => '', 'options' => array(0 => 'No', 1 => 'Yes'))); ?>
<div class="form-group">
	<label class="col-md-2 control-label"><?php echo __('Video');?></label>
	<div class="col-md-10">
		<?php echo $this->Form->input('video', array('div' => false, 'class' => 'form-control', 'label' => false)); ?>
		<div class="avatar">
		<?php if(!empty($this->data['Project']['video']) ) { 
			echo $this->Video->embed($this->data['Project']['video'], array('width' => '100%', 'autoplay' => 0));
			echo $this->Html->link('<span class="badge">X</span>', array('controller' => 'projects', 'action' => 'removeVideo', $this->data['Project']['id'], 'admin' => 'admin', 'plugin' => false), array('escape' => false));
		}?>
		</div>
	</div>
</div>
<?php echo $this->Form->input('show_video', array('div' => 'form-group', 'class' => 'select2-select-00 col-md-12 full-width-fix', 'label' => array('text' => __('Show Video'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>', 'empty' => '', 'options' => array(0 => 'No', 1 => 'Yes'))); ?>
<?php $_author = empty($this->data['Project']['author'])? $this->Session->read('Auth.User.firstname') . ' ' . $this->Session->read('Auth.User.surname'): $this->data['Project']['author'];?>
<?php echo $this->Form->input('author', array('div' => 'form-group', 'class' => 'form-control', 'label' => array('text' => __('Author'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>', 'value' => $_author)); ?>
<?php echo $this->Form->input('show_author', array('div' => 'form-group', 'class' => 'select2-select-00 col-md-12 full-width-fix', 'label' => array('text' => __('Show Author'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>', 'empty' => '', 'options' => array(0 => 'No', 1 => 'Yes'))); ?>
<?php echo $this->Form->input('notes', array('div' => 'form-group', 'label' => array('text' => __('Notes'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>', 'type' => 'textarea', 'class' => 'form-control', 'rows' => 5));?>
<?php echo $this->Form->input('show_notes', array('div' => 'form-group', 'class' => 'select2-select-00 col-md-12 full-width-fix', 'label' => array('text' => __('Show Notes'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>', 'empty' => '', 'options' => array(0 => 'No', 1 => 'Yes'))); ?>
<?php echo $this->Form->input('project_date', array('div' => 'form-group', 'class' => 'form-control datepicker', 'label' => array('text' => __('Project date'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>', 'type' => 'text'));?>
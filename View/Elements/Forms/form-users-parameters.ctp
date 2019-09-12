<div class="form-group">
	<label class="col-md-2 control-label"><?php echo __('User Image');?></label>
	<div class="col-md-10">
		<?php echo $this->Form->input('Image.file', array('type' => 'file', 'data-style' => 'fileinput', 'div' => false, 'label' => false));?>
		<div class="avatar">
		<?php if( empty($this->data['User']['avatar']) ) { 
			echo $this->Html->image('avatar-1.jpg', array('alt' => ''));
		} else {
			echo $this->Resize->image($this->data['User']['avatar'], 80, 80, false, array('alt' => ''));
			echo $this->Html->link('<span class="badge">X</span>', array('controller' => 'users', 'action' => 'removeAvatar', $this->data['User']['id'], 'admin' => 'admin', 'plugin' => false), array('escape' => false));
		}?>
		</div>
	</div>
</div>
<?php echo $this->Form->input('role_id', array('div' => 'form-group', 'class' => 'select2-select-00 col-md-12 full-width-fix', 'label' => array('text' => __('Role'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>', 'empty' => '')); ?>
<?php echo $this->Form->input('active', array('div' => 'form-group', 'class' => 'select2-select-00 col-md-12 full-width-fix', 'label' => array('text' => __('Active'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>', 'empty' => '', 'options' => array(0 => 'No', 1 => 'Yes'))); ?>
<?php echo $this->Form->input('display', array('div' => 'form-group', 'class' => 'select2-select-00 col-md-12 full-width-fix', 'label' => array('text' => __('Display on User page'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>', 'empty' => '', 'options' => array(0 => 'No', 1 => 'Yes'))); ?>
<div class="form-group">
	<label class="col-md-2 control-label"><?php echo __('Last Login');?></label>
	<div class="col-md-10">
		<?php if (!empty($this->data['User']['last_login'])){ echo $this->Time->timeAgoInWords($this->data['User']['last_login'], array('end' => '+1 year')); } else { echo __('Never'); } ?>
	</div>
</div>
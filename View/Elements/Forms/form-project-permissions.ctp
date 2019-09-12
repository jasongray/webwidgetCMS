<?php echo $this->Form->input('User.User', array('div' => 'form-group', 'label' => array('text' => __('Who can access'), 'class' => 'col-md-2 control-label'), 'class' => 'select2-select-00 col-md-12 full-width-fix', 'multiple' => true, 'between' => '<div class="col-md-10">', 'after' => '<span class="help-block">'.__('Select users who can access this project. Leave blank for all users on the front page!').'</span></div>', 'options' => $users, 'empty' => ''));?>
<?php echo $this->Form->input('Role.Role', array('div' => 'form-group', 'label' => array('text' => __('Role can access'), 'class' => 'col-md-2 control-label'), 'class' => 'select2-select-00 col-md-12 full-width-fix', 'multiple' => true, 'between' => '<div class="col-md-10">', 'after' => '<span class="help-block">'.__('Select roles that can access this project. Leave blank for all roles!').'</span></div>', 'options' => $roles, 'empty' => ''));?>
<div class="form-group">
	<label class="col-md-2 control-label"><?php echo __('Limit Permissions');?></label>
	<div class="col-md-4">
		<?php echo $this->Form->input('ProjectLimit.date_limit', array('div' => 'form-group-inline', 'label' => array('text' => __('Date Limit'), 'class' => 'col-md-4 control-label'), 'class' => 'form-control col-md-12 full-width-fix datepicker', 'between' => '<div class="col-md-8">', 'after' => '<span class="help-block">'.__('Limit this project to be only viewable before a certain date.').'</span></div>', 'type' => 'text'));?>
	</div>
	<div class="col-md-4">
		<?php echo $this->Form->input('ProjectLimit.count_limit', array('div' => 'form-group-inline', 'label' => array('text' => __('View Limit'), 'class' => 'col-md-4 control-label'), 'class' => 'form-control col-md-12 full-width-fix', 'between' => '<div class="col-md-8">', 'after' => '<span class="help-block">'.__('Limit this project to be only viewable by the number of times set here.').'</span></div>'));?>
	</div>
	<?php if(!empty($this->data['ProjectLimit']['id'])){?>
		<?php echo $this->Form->hidden('ProjectLimit.id');?>
	<?php } ?>
</div>
<?php echo $this->Form->input('username', array('div' => 'form-group', 'class' => 'form-control', 'label' => array('text' => __('Username'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>')); ?>
<?php echo $this->Form->input('password', array('div' => 'form-group', 'class' => 'form-control', 'label' => array('text' => __('Password'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>', 'value' => ''));?>
<?php echo $this->Form->input('email', array('div' => 'form-group', 'class' => 'form-control', 'label' => array('text' => __('Email'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>')); ?>	

<?php echo $this->Form->input('firstname', array('div' => 'form-group', 'class' => 'form-control', 'label' => array('text' => __('Firstname'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>')); ?>
<?php echo $this->Form->input('surname', array('div' => 'form-group', 'class' => 'form-control', 'label' => array('text' => __('Surname'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>')); ?>
<?php echo $this->Form->input('facebook', array('div' => 'form-group', 'class' => 'form-control', 'label' => array('text' => __('Facebook Page'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>')); ?>
<?php echo $this->Form->input('website', array('div' => 'form-group', 'class' => 'form-control', 'label' => array('text' => __('Website Link'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>')); ?>
<?php echo $this->Form->input('profile', array('div' => 'form-group', 'label' => array('text' => __('Profile'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>', 'type' => 'textarea', 'class' => 'form-control wysiwyg', 'rows' => 10));?>
<?php echo $this->Form->input('notes', array('div' => 'form-group', 'label' => array('text' => __('User Notes'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>', 'type' => 'textarea', 'class' => 'form-control wysiwyg', 'rows' => 15));?>
<div class="form-actions">
<?php
	echo $this->Form->hidden('id');
	echo $this->Form->submit('Save', array('class'=>'btn btn-primary', 'div' => false)); 
	echo $this->Html->link('Cancel', array('controller' => 'users', 'action' => 'cancel', 'admin' => 'admin', 'plugin' => false), array('class' => 'btn btn-orange'));
	if(!empty($this->data['User']['id'])){
		echo $this->Html->link('Delete', array('controller' => 'users', 'action' => 'delete', 'admin' => 'admin', 'plugin' => false, $this->data['User']['id']), array('class' => 'btn btn-grey'));
	}
?>	
</div>
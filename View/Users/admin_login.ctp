<?php echo $this->Form->create('User', array('class'=>'form-vertical login-form'));?>
	<h3 class="form-title"><?php echo __('Admin');?></h3>
	<?php echo $this->Session->flash('auth', array('element' => 'Flash/admin/login-error'));?>
	<?php echo $this->Session->flash('flash', array('element' => 'Flash/admin/login-error'));?>
		
	<?php echo $this->Form->input('username', array('div' => 'form-group', 'label' => false, 'class' => 'form-control', 'before' => '<div class="input-icon"><i class="icon-user"></i>', 'after' => '</div>', 'placeholder' => __('Username'), 'autofocus' => 'autofocus',  'data-rule-required' => 'true', 'data-msg-required' => __('Please enter your username.')));?>
	<?php echo $this->Form->input('password', array('div' => 'form-group', 'label' => false, 'class' => 'form-control', 'before' => '<div class="input-icon"><i class="icon-lock"></i>', 'after' => '</div>', 'placeholder' => __('Password'), 'autofocus' => 'autofocus',  'data-rule-required' => 'true', 'data-msg-required' => __('Please enter your password.')));?>
		
	<div class="form-actions">
		<label class="checkbox pull-left"><?php echo $this->Form->input('remember', array('label' => false, 'div' => false, 'type' => 'checkbox'));?> <?php echo __('Remember Me');?></label>
		<?php echo $this->Form->button(__('Sign In') . ' <i class="icon-angle-right"></i>', array('class' => 'submit btn btn-primary pull-right', 'escape' => false));?>
	</div>
	
<?php echo $this->Form->end();?>
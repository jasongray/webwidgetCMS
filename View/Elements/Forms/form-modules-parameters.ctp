<div class="form-group">
	<label class="col-md-2 control-label"><?php echo __('Select which menu to show this module on');?></label>
	<div class="col-md-10">	
		<?php echo $this->Form->input('html_file', array('div' => false, 'legend' => false, 'class' => 'uniform htmlfile', 'options' => array('html' => 'HTML', 'file' => 'File', 'menu' => 'Menu'), 'type' => 'radio', 'before' => '<div class="radio-inline">', 'after' => '</div>', 'separator' => '</div><div class="radio-inline">'));?>						
	</div>
</div>
<?php $_class1 = (isset($this->data['Module']['html_file']) && $this->data['Module']['html_file'] == 'file')? 'display:block;': 'display:none'; ?>
<div class="xtra usefile" style="<?php echo $_class1;?>'">
	<?php echo $this->Form->input('modfile', array('div' => 'form-group', 'class' => 'select2-select-00 col-md-12 full-width-fix', 'label' => array('text' => '', 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>', 'empty' => '', 'options' => $modfiles)); ?>
</div>
<?php $_class2 = (isset($this->data['Module']['html_file']) && $this->data['Module']['html_file'] == 'html')? 'display:block;': 'display:none';?>
<div class="xtra usehtml" style="<?php echo $_class2;?>">
	<?php echo $this->Form->input('content', array('div' => 'form-group', 'label' => array('text' => '', 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>', 'type' => 'textarea', 'class' => 'form-control wysiwyg', 'rows' => 10));?>
</div>
<?php $_class3 = (isset($this->data['Module']['html_file']) && $this->data['Module']['html_file'] == 'menu')? 'display:block;': 'display:none';?>
<div class="xtra usemenu" style="<?php echo $_class3;?>">
	<?php echo $this->Form->input('menu_id', array('div' => 'form-group', 'class' => 'select2-select-00 col-md-12 full-width-fix', 'label' => array('text' => __('Menu'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>', 'empty' => '', 'options' => $menus));?>
	<?php echo $this->Form->input('modmenufiles', array('div' => 'form-group', 'class' => 'select2-select-00 col-md-12 full-width-fix', 'label' => array('text' => __('Menu File'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>', 'empty' => '', 'options' => $modmenufiles));?>
</div>
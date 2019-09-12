<div class="form-group">
	<label class="col-md-2 control-label"><?php echo __('Logo');?></label>
	<div class="col-md-10">
		<?php echo $this->Form->input('Image.file', array('type' => 'file', 'data-style' => 'fileinput', 'div' => false, 'label' => false));?>
		<div class="avatar col-md-5">
			<?php if(!empty($this->data['params']['logo']) ) { 
				echo $this->Resize->image('logo'.DS.$this->data['params']['logo'], 200, 200, true, array('alt' => ''));
			}?>
		</div>
	</div>
</div>
<?php echo $this->Form->input('params.logo_height', array('div' => 'form-group', 'label' => array('text' => __('Logo Max Height'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>', 'type' => 'text', 'class' => 'form-control'));?>
<?php echo $this->Form->input('params.logo_layout', array('div' => 'form-group', 'class' => 'select2-select-00 col-md-12 full-width-fix', 'label' => array('text' => __('Logo Layout'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>', 'options' => array('1' => 'Logo Only', '2' => 'Logo and Site Name', '3' => 'Site Name Only'), 'empty' => ''));?>
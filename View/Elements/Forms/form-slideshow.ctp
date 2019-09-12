<?php echo $this->Form->create('Slideshow', array('class' => 'form-horizontal row-border', 'type' => 'file'));?>
<?php echo $this->Form->input('name', array('div' => 'form-group', 'class' => 'form-control', 'label' => array('text' => __('Name'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>')); ?>
<div class="form-group">
	<label class="col-md-2 control-label"><?php echo __('Publish');?></label>
	<div class="col-md-10">
		<?php echo $this->Form->input('published', array('div' => false, 'label' => false, 'class' => 'uniform', 'type' => 'checkbox')); ?>
	</div>
</div>	
<div class="form-group">
	<label class="col-md-2 control-label"><?php echo __('Info box?');?></label>
	<div class="col-md-10">
		<?php echo $this->Form->input('Slideshow.params.showinfo', array('div' => false, 'label' => false, 'class' => 'uniform', 'type' => 'checkbox')); ?>
	</div>
</div>
<?php echo $this->Form->input('Slideshow.params.infoposition', array('div' => 'form-group', 'class' => 'form-control', 'label' => array('text' => __('Info box position'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>', 'options' => array('left' => 'Left', 'right' => 'Right'), 'empty' => ''));?>
<div class="form-group">
	<label class="col-md-2 control-label"><?php echo __('Heading');?></label>
	<div class="col-md-6 col-xs-10">
	<?php echo $this->Form->input('Slideshow.params.heading', array('div' => false, 'class' => 'form-control', 'label' => false, 'between' => '', 'after' => ''));?>
	</div>
	<div class="col-md-4 col-xs-10">
		<?php echo $this->Form->input('Slideshow.params.headingcolour', array('div' => 'form-group', 'class' => 'form-control bs-colorpicker', 'label' => array('text' => __('Colour'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>')); ?>
	</div>
</div>
<div class="form-group">
	<label class="col-md-2 control-label"><?php echo __('Text');?></label>
	<div class="col-md-6 col-xs-10">
	<?php echo $this->Form->input('Slideshow.params.text', array('div' => false, 'class' => 'form-control', 'label' => false, 'between' => '', 'after' => '', 'rows' => 2));?>
	</div>
	<div class="col-md-4 col-xs-10">
		<?php echo $this->Form->input('Slideshow.params.textcolour', array('div' => 'form-group', 'class' => 'form-control bs-colorpicker', 'label' => array('text' => __('Colour'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>')); ?>
	</div>
</div>
<div class="form-group">
	<label class="col-md-2 control-label"><?php echo __('Button Link');?></label>
	<div class="col-md-5 col-xs-10">
		<?php echo $this->Form->input('Slideshow.params.link', array('div' => 'form-group', 'class' => 'form-control', 'label' => array('text' => __('URL'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>')); ?>
	</div>
	<div class="col-md-5 col-xs-10">
	<?php echo $this->Form->input('Slideshow.params.linktext', array('div' => 'form-group', 'class' => 'form-control', 'label' => array('text' => __('Text'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>')); ?>
	</div>
</div>		
<?php echo $this->Form->input('alt', array('div' => 'form-group', 'class' => 'form-control', 'label' => array('text' => __('Alternate Text'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>')); ?>
<div class="form-group">
	<label class="col-md-2 control-label"><?php echo __('Slide Image');?></label>
	<div class="col-md-10">
		<?php echo $this->Form->input('Image.file', array('type' => 'file', 'data-style' => 'fileinput', 'div' => false, 'label' => false));?>
		<div class="avatar col-md-5">
		<?php if(!empty($this->data['Slideshow']['image']) ) { 
			echo $this->Resize->image('slideshows'.DS.$this->data['Slideshow']['image'], 600, 600, true, array('alt' => ''));
			echo $this->Html->link('<span class="badge">X</span>', array('controller' => 'slideshows', 'action' => 'removeImage', $this->data['Slideshow']['id'], 'admin' => 'admin', 'plugin' => false), array('escape' => false));
		}?>
		</div>
	</div>
</div>
<div class="form-actions">
<?php
	echo $this->Form->hidden('id');
	echo $this->Form->submit('Save', array('class'=>'btn btn-primary', 'div' => false)); 
	echo $this->Html->link('Cancel', array('controller' => 'slideshows', 'action' => 'cancel', 'admin' => 'admin', 'plugin' => false), array('class' => 'btn btn-orange'));
	if(!empty($this->data['Slideshow']['id'])){
		echo $this->Html->link('Delete', array('controller' => 'slideshows', 'action' => 'delete', 'admin' => 'admin', 'plugin' => false, $this->data['Slideshow']['id']), array('class' => 'btn btn-grey'));
	}
?>	
</div>
<?php echo $this->Form->end();?>
<?php echo $this->Html->script(array('plugins/bootstrap-colorpicker/bootstrap-colorpicker.min.js'), array('inline' => false));?>
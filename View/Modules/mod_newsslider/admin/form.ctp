<div class="form-group">
	<label class="col-md-2 control-label"><?php echo __('Featured Items');?></label>
	<div class="col-md-10">
		<?php echo $this->Form->input('params.featured', array('div' => false, 'label' => false, 'class' => 'uniform', 'type' => 'checkbox')); ?>
	</div>
</div>
<?php echo $this->Form->input('params.category_id', array('div' => 'form-group', 'class' => 'select2-select-00 col-md-12 full-width-fix', 'label' => array('text' => __('Category'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>', 'multiple' => 'multiple', 'empty' => '', 'options' => $helper->getCategories()));?>
<?php echo $this->Form->input('params.bloglimit', array('div' => 'form-group', 'class' => 'form-control input-width-mini', 'label' => array('text' => __('Number of items to show'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>'));?>
<hr/>
<?php echo $this->Form->input('params.slidertransition', array('div' => 'form-group', 'class' => 'select2-select-00 col-md-12 full-width-fix', 'label' => array('text' => __('Slider Transition'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>', 'empty' => '', 'options' => array('fade' => 'Fade', 'backSlide' => 'Back Slide', 'goDown' => 'Go Down', 'fadeUp' => 'Fade Up')));?>
<?php echo $this->Form->input('params.sliderspeed', array('div' => 'form-group', 'class' => 'form-control input-width-mini', 'label' => array('text' => __('Slider Speed (ms)'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>'));?>
<div class="form-group">
	<div class="col-md-4">
	<?php echo $this->Form->input('published', array('div' => false, 'class' => 'uniform', 'label' => array('text' => __('Publish'), 'class' => 'control-label'), 'between' => '<div class="col-md-8">', 'after' => '</div>', 'type' => 'checkbox')); ?>
	</div>
	<div class="col-md-4">
	<?php echo $this->Form->input('allow_cart', array('div' => false, 'class' => 'uniform', 'label' => array('text' => __('Add to Cart'), 'class' => 'control-label'), 'between' => '<div class="col-md-8">', 'after' => '</div>', 'type' => 'checkbox')); ?>
	</div>
	<div class="col-md-4">
	<?php echo $this->Form->input('allow_details', array('div' => false, 'class' => 'uniform', 'label' => array('text' => __('Show Detail'), 'class' => 'control-label'), 'between' => '<div class="col-md-8">', 'after' => '</div>', 'type' => 'checkbox')); ?>
	</div>
</div>
<div class="form-group">
	<label class="col-md-2 control-label"><?php echo __('Dimensions');?></label>
	<div class="col-md-3">
		<?php echo $this->Form->input('length', array('div' => false, 'label' => array('text' => __('Length')), 'class' => 'form-control')); ?>
	</div>
	<div class="col-md-3">
		<?php echo $this->Form->input('width', array('div' => false, 'label' => array('text' => __('Width')), 'class' => 'form-control')); ?>
	</div>
	<div class="col-md-3">
		<?php echo $this->Form->input('depth', array('div' => false, 'label' => array('text' => __('Depth')), 'class' => 'form-control')); ?>
	</div>
</div>
<?php echo $this->Form->input('weight', array('div' => 'form-group', 'class' => 'form-control', 'label' => array('text' => __('Product Weight'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-3">', 'after' => '<span class="help-block">'.__('Enter product weight in grams.').'</span></div>')); ?>
<?php echo $this->Form->input('Category.Category', array('div' => 'form-group', 'label' => array('text' => __('Categories'), 'class' => 'col-md-2 control-label'), 'class' => 'select2-select-00 col-md-12 full-width-fix', 'multiple' => true, 'between' => '<div class="col-md-10">', 'after' => '<span class="help-block">'.__('Select categories for this product. You can select multiple categories also.').'</span></div>', 'options' => $categories, 'empty' => ''));?>
<div class="form-group">
	<label class="col-md-2 control-label"><?php echo __('Tax');?></label>
	<div class="col-md-3">
		<?php echo $this->Form->input('taxed', array('div' => false, 'label' => false, 'class' => 'uniform', 'type' => 'checkbox')); ?>
	</div>
	<div class="col-md-7">
		<?php echo $this->Form->input('tax_id', array('div' => false, 'label' => false, 'class' => 'select2-select-00 col-md-12 full-width-fix', 'empty' => '')); ?>
	</div>
</div>

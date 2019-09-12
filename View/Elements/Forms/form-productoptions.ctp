<?php echo $this->Form->input('code', array('div' => 'form-group', 'class' => 'form-control', 'label' => array('text' => __('Code'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>')); ?>
<?php echo $this->Form->input('size', array('div' => 'form-group', 'class' => 'form-control', 'label' => array('text' => __('Size'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>')); ?>
<?php echo $this->Form->input('colour', array('div' => 'form-group', 'class' => 'form-control', 'label' => array('text' => __('Colour'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>')); ?>
<?php echo $this->Form->input('htmlcolour', array('div' => 'form-group', 'class' => 'form-control bs-colorpicker', 'label' => array('text' => __('Colour'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>')); ?>
<?php echo $this->Form->input('costprice', array('div' => 'form-group', 'class' => 'form-control', 'label' => array('text' => __('Cost'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>')); ?>
<?php echo $this->Form->input('retailprice', array('div' => 'form-group', 'class' => 'form-control', 'label' => array('text' => __('RRP'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>')); ?>
<?php echo $this->Form->input('sellprice', array('div' => 'form-group', 'class' => 'form-control', 'label' => array('text' => __('Sell'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>')); ?>
<?php echo $this->Form->input('status', array('div' => 'form-group', 'class' => 'form-control', 'label' => array('text' => __('Status'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>', 'empty' => '', 'options' => array(0 => __('Unavailable'), 1 => __('Available')))); ?>
<?php echo $this->Form->input('Inventory.count', array('div' => 'form-group', 'class' => 'form-control', 'label' => array('text' => __('Inventory Level'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>')); ?>
<?php echo $this->Form->hidden('Inventory.thecount');?>
<div class="form-actions">
<?php
	echo $this->Form->hidden('id');
	echo $this->Form->submit('Save', array('class'=>'btn btn-primary', 'div' => false)); 
	echo $this->Html->link('Cancel', array('controller' => 'product_options', 'action' => 'cancel', 'admin' => 'admin', 'plugin' => false), array('class' => 'btn btn-orange'));
	if(!empty($this->data['ProductOption']['id'])){
		echo $this->Html->link('Delete', array('controller' => 'product_options', 'action' => 'delete', 'admin' => 'admin', 'plugin' => false, $this->data['ProductOption']['id']), array('class' => 'btn btn-grey'));
	}
?>	
</div>
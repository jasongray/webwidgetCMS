<section class="se-section">
	<div class="container">
		<div class="row">
            <?php echo $this->Form->create('Contact', array('url' => array('controller' => 'contacts', 'action' => 'index'), 'id' => 'form', 'class' => $m['class']));?>
			<?php echo $this->Session->flash('contact');?>
			<?php echo $this->Form->input('name', array('div' => 'form-group col-md-6', 'class' => 'form-control span4', 'label' => array('text' => __('Name'), 'class' => 'control-label'))); ?>
			<?php echo $this->Form->input('email', array('div' => 'form-group col-md-12', 'class' => 'form-control span4', 'label' => array('text' => __('Email Address'), 'class' => 'control-label'))); ?>
			<?php echo $this->Form->input('message', array('div' => 'form-group col-md-12', 'class' => 'form-control span8', 'label' => array('text' => __('Message'), 'class' => 'control-label'), 'type' => 'textarea', 'rows' => 7)); ?>
			<div class="text-center col-md-12 mt10 mb20">
				<?php echo $this->Form->button('Send', array('class'=>'btn se-btn btn-rounded'));?>
			</div>
			<?php echo $this->Form->end();?>
		</div>
	</div>
</section>
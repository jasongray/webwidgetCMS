
			<div class="form-group">
				<label class="col-md-2 control-label"><?php echo __('Sponsor Image');?></label>
				<div class="col-md-10">
					<?php echo $this->Form->input('Image.file', array('type' => 'file', 'data-style' => 'fileinput', 'div' => false, 'label' => false));?>
					<div class="avatar col-md-5">
					<?php if(!empty($this->data['Sponsor']['image']) ) { 
						echo $this->Resize->image('sponsors'.DS.$this->data['Sponsor']['image'], 300, 300, true, array('alt' => ''));
						echo $this->Html->link('<span class="badge">X</span>', array('controller' => 'sponsors', 'action' => 'removeImage', $this->data['Sponsor']['id'], 'admin' => 'admin', 'plugin' => false), array('escape' => false));
					}?>
					</div>
				</div>
			</div>
			<?php echo $this->Form->input('web_url', array('div' => 'form-group', 'class' => 'form-control', 'label' => array('text' => __('Web address'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>')); ?>
			
			<?php echo $this->Form->input('contact_name', array('div' => 'form-group', 'class' => 'form-control', 'label' => array('text' => __('Contact Name'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>')); ?>
			<?php echo $this->Form->input('contact_number', array('div' => 'form-group', 'class' => 'form-control', 'label' => array('text' => __('Contact Number'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>')); ?>
			<div class="form-group">
			<?php echo $this->Form->input('notes', array('div' => false, 'label' => array('text' => __('Notes'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>', 'type' => 'textarea', 'class' => 'form-control', 'rows' => 10));?>
			</div>
			
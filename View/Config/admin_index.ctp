<?php $this->Html->pageClass = 'config';?>

<?php $this->Html->pageTitle = __('System Config');?>
<?php $this->Html->addCrumb(__('System Config'));?>

<?php echo $this->element('crumb-div');?>
<?php echo $this->element('page-header');?>
<div class="row">
	<div class="col-md-12">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-bullseye"></i> <?php echo __('System Config');?></h4>
			</div>
			<div class="widget-content">
				<?php echo $this->Form->create('Config', array('class' => 'form-horizontal row-border', 'type' => 'file'));?>
				<div class="tabbable box-tabs">
					<ul class="nav nav-tabs">
						<li><a href="#box_tab5" data-toggle="tab"><i class="icon-envelope-alt"></i> <?php echo __('Mail Settings');?></a></li>
						<li><a href="#box_tab4" data-toggle="tab"><i class="icon-file-alt"></i> <?php echo __('Blog Settings');?></a></li>
						<li><a href="#box_tab3" data-toggle="tab"><i class="icon-search"></i> <?php echo __('SEO');?></a></li>
						<li><a href="#box_tab2" data-toggle="tab"><i class="icon-building"></i> <?php echo __('Business Info');?></a></li>
						<li class="active"><a href="#box_tab1" data-toggle="tab"><i class="icon-cogs"></i> <?php echo __('General');?></a></li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane active" id="box_tab1">
							<?php echo $this->Form->input('site_name', array('div' => 'form-group', 'class' => 'form-control', 'label' => array('text' => __('Site Name'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>'));?>
							<?php echo $this->Form->input('site_hdr2', array('div' => 'form-group', 'class' => 'form-control', 'label' => array('text' => __('Site Header 2'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>'));?>
							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __('Show Breadcrumbs');?></label>
								<div class="col-md-10">
									<?php echo $this->Form->input('breadcrumbs', array('div' => false, 'label' => array('text' => '', 'class' => 'checkbox'), 'class' => 'uniform', 'type' => 'checkbox')); ?>
								</div>
							</div>
							<?php echo $this->Form->input('theme', array('div' => 'form-group', 'class' => 'select2-select-00 col-md-12 full-width-fix', 'label' => array('text' => __('Site Theme'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>', 'options' => $themes, 'empty' => ''));?>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __('Log Activity');?></label>
								<div class="col-md-10">
									<?php echo $this->Form->input('log', array('div' => false, 'label' => array('text' => '', 'class' => 'checkbox'), 'class' => 'uniform', 'type' => 'checkbox')); ?>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __('Site Offline');?></label>
								<div class="col-md-10">
									<?php echo $this->Form->input('offline', array('div' => false, 'label' => array('text' => '', 'class' => 'checkbox'), 'class' => 'uniform', 'type' => 'checkbox')); ?>
								</div>
							</div>
							<?php echo $this->Form->input('offline_msg', array('div' => 'form-group', 'class' => 'form-control', 'label' => array('text' => __('Offline Message'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>'));?>
							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __('Save Contact form data');?></label>
								<div class="col-md-10">
									<?php echo $this->Form->input('save_contact', array('div' => false, 'label' => array('text' => '', 'class' => 'checkbox'), 'class' => 'uniform', 'type' => 'checkbox')); ?>
								</div>
							</div>
						</div>
						<div class="tab-pane" id="box_tab2">
							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __('Show on contact page?');?></label>
								<div class="col-md-10">
									<?php echo $this->Form->input('show_contact', array('div' => false, 'label' => array('text' => '', 'class' => 'checkbox'), 'class' => 'uniform', 'type' => 'checkbox')); ?>
									<span class="help-text">Any completed details will show on the contact page</span>
								</div>
							</div>
							<?php echo $this->Form->input('contact_header', array('div' => 'form-group', 'class' => 'form-control input-width-medium', 'label' => array('text' => __('Heading for contact page'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>'));?>
							<?php echo $this->Form->input('business_address', array('div' => 'form-group', 'class' => 'form-control', 'label' => array('text' => __('Address'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>', 'rows' => 4));?>
							<?php echo $this->Form->input('business_phone', array('div' => 'form-group', 'class' => 'form-control input-width-medium', 'label' => array('text' => __('Phone'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>'));?>
							<?php echo $this->Form->input('business_fax', array('div' => 'form-group', 'class' => 'form-control input-width-medium', 'label' => array('text' => __('Fax'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>'));?>
							<?php echo $this->Form->input('business_email', array('div' => 'form-group', 'class' => 'form-control input-width-xlarge', 'label' => array('text' => __('Email'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>'));?>
							<?php echo $this->Form->input('ga', array('div' => 'form-group', 'class' => 'form-control input-width-medium', 'label' => array('text' => __('Google Analytics'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '<span class="help-text">(UA-XXXXXX-X)</span></div>'));?>
							<?php echo $this->Form->input('social_fb', array('div' => 'form-group', 'class' => 'form-control input-width-xlarge', 'label' => array('text' => __('Facebook'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>'));?>
							<?php echo $this->Form->input('social_tw', array('div' => 'form-group', 'class' => 'form-control input-width-xlarge', 'label' => array('text' => __('Twitter'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>'));?>
							<?php echo $this->Form->input('social_in', array('div' => 'form-group', 'class' => 'form-control input-width-xlarge', 'label' => array('text' => __('Instagram'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>'));?>
							<?php echo $this->Form->input('social_in_id', array('div' => 'form-group', 'class' => 'form-control input-width-xlarge', 'label' => array('text' => __('Instagram User id'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>', 'type' => 'text'));?>
							<?php echo $this->Form->input('social_yt', array('div' => 'form-group', 'class' => 'form-control input-width-xlarge', 'label' => array('text' => __('YouTube'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>'));?>
							<?php echo $this->Form->input('social_vi', array('div' => 'form-group', 'class' => 'form-control input-width-xlarge', 'label' => array('text' => __('Vimeo'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>'));?>
							<?php echo $this->Form->input('social_fl', array('div' => 'form-group', 'class' => 'form-control input-width-xlarge', 'label' => array('text' => __('Flicker'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>'));?>
							<?php echo $this->Form->input('social_go', array('div' => 'form-group', 'class' => 'form-control input-width-xlarge', 'label' => array('text' => __('Google +'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>'));?>
							<?php echo $this->Form->input('social_lk', array('div' => 'form-group', 'class' => 'form-control input-width-xlarge', 'label' => array('text' => __('Linkdin'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>'));?>
							<?php echo $this->Form->input('social_pi', array('div' => 'form-group', 'class' => 'form-control input-width-xlarge', 'label' => array('text' => __('Pinterest'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>'));?>
						</div>
						<div class="tab-pane" id="box_tab3">
							<?php echo $this->Form->input('site_name_layout', array('div' => 'form-group', 'class' => 'form-control', 'label' => array('text' => __('Page Title'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '<span class="help-text">{site name} :: '.__('Display site name').'<br/>{model} :: '.__('Display active model').'<br/>{page title} :: '.__('Display page title').'</span></div>'));?>
							<?php echo $this->Form->input('meta_description', array('div' => 'form-group', 'class' => 'form-control', 'label' => array('text' => __('Page Meta'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>', 'rows' => 5));?>
							<?php echo $this->Form->input('meta_keywords', array('div' => 'form-group', 'class' => 'form-control', 'label' => array('text' => __('Page Keywords'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>', 'rows' => 5));?>
						</div>
						<div class="tab-pane" id="box_tab4">
							<?php echo $this->Form->input('bloglimit', array('div' => 'form-group', 'class' => 'form-control input-width-small', 'label' => array('text' => __('No. of articles to show'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>')); ?>
							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __('Allow comments');?></label>
								<div class="col-md-10">
									<?php echo $this->Form->input('allow_comments', array('div' => false, 'label' => array('text' => '', 'class' => 'checkbox'), 'class' => 'uniform', 'type' => 'checkbox')); ?>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __('Auto delete spam?');?></label>
								<div class="col-md-10">
									<?php echo $this->Form->input('delspam_comments', array('div' => false, 'label' => array('text' => '', 'class' => 'checkbox'), 'class' => 'uniform', 'type' => 'checkbox')); ?>
								</div>
							</div>
							<?php echo $this->Form->input('comment_spam_limit', array('div' => 'form-group', 'class' => 'form-control input-width-xsmall', 'label' => array('text' => __('Spam Score'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>'));?>
							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __('Notify Users?');?></label>
								<div class="col-md-10">
									<?php echo $this->Form->input('notify_comments', array('div' => false, 'label' => array('text' => '', 'class' => 'checkbox'), 'class' => 'uniform', 'type' => 'checkbox')); ?>
								</div>
							</div>
						</div>
						<div class="tab-pane" id="box_tab5">
							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __('Send Email via SMTP');?></label>
								<div class="col-md-10">
									<?php echo $this->Form->input('smtp', array('div' => false, 'label' => array('text' => '', 'class' => 'checkbox'), 'class' => 'uniform', 'type' => 'checkbox')); ?>
								</div>
							</div>
							<?php echo $this->Form->input('send_email', array('div' => 'form-group', 'class' => 'form-control', 'label' => array('text' => __('Send mail from'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>')); ?>
							<?php echo $this->Form->input('smtp_host', array('div' => 'form-group', 'class' => 'form-control', 'label' => array('text' => __('Host'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>'));?>
							<?php echo $this->Form->input('smtp_port', array('div' => 'form-group', 'class' => 'form-control input-width-xsmall', 'label' => array('text' => __('Port'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>'));?>
							<?php echo $this->Form->input('smtp_username', array('div' => 'form-group', 'class' => 'form-control input-width-medium', 'label' => array('text' => __('Username'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>'));?>
							<?php echo $this->Form->input('smtp_password', array('div' => 'form-group', 'class' => 'form-control', 'label' => array('text' => __('Password'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>'));?>
						</div>
					</div>
				</div>
				<div class="form-actions">
					<?php echo $this->Form->submit('Save', array('class'=>'btn btn-primary', 'div' => false)); ?>
					<?php echo $this->Html->link('Cancel', array('controller' => 'users', 'action' => 'dashboard', 'admin' => 'admin', 'plugin' => false), array('class' => 'btn btn-orange')); ?>	
				</div>
				<?php echo $this->Form->end();?>
			</div>
		</div>
	</div>
</div>
<?php echo $this->element('forms-js');?>
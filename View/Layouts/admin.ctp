<?php echo $this->Html->docType(); ?>
<html lang="en">
	<head>
		<?php echo $this->Html->charset(); ?>
		<title><?php echo $this->Xhtml->pagetitle($title_for_layout, 'admin');?></title>
		<?php echo $this->Html->meta('icon');?>
		<?php echo $this->Html->meta('description', Configure::read('MySite.meta_description')); ?>
		<?php echo $this->Html->meta('keywords', Configure::read('MySite.meta_keywords'));?>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
		<?php echo $this->Html->css(array('bootstrap.min.css', 'main', 'plugins', 'responsive', 'icons', 'plugins/sceditor/themes/default.min.css'));?>
		<?php echo $this->Html->css(array('fontawesome/font-awesome.min'));?>
		<?php echo $this->Html->css(array('http://fonts.googleapis.com/css?family=Open+Sans:400,600,700'));?>
		<!--[if IE 7]>
		<?php echo $this->Html->css(array('fontawesome/font-awesome-ie7.min.css'));?>
		<![endif]-->
		<!--[if IE 8]>
		<?php echo $this->Html->css(array('ie8'));?>
		<![endif]-->
		<?php echo $this->fetch('css');?>
	</head>
	<body class="theme-dark">
		<header class="header navbar navbar-fixed-top" role="banner">
			<div class="container">
				<ul class="nav navbar-nav">
					<li class="nav-toggle"><a href="javascript:void(0);" title=""><i class="icon-reorder"></i></a></li>
				</ul>
				<?php echo $this->Html->link(__('Webwidget CMS'), array('controller' => 'users', 'action' => 'dashboard', 'admin' => 'admin', 'plugin' => false), array('class' => 'navbar-brand', 'escape' => false));?>
				<a href="#" class="toggle-sidebar bs-tooltip" data-placement="bottom" data-original-title="Toggle navigation"><i class="icon-reorder"></i></a>
				<ul class="nav navbar-nav navbar-left hidden-xs hidden-sm">
					<li><?php echo $this->Html->link(__('Dashboard'), array('controller' => 'users', 'action' => 'dashboard', 'admin' => 'admin', 'plugin' => false));?></li>
					<li><?php echo $this->Html->link(__('Preview Site'), '/', array('target' => '_blank'));?></li>
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<?php echo $this->element('member-notifications')?>
					<?php echo $this->element('new-comments')?>
					<li class="dropdown user">
						<?php if( empty($user['User']['avatar']) ) { 
							$_img = 'avatar-1.jpg';
						} else {
							$_img = $user['User']['avatar'];
						}
						$img = $this->Resize->image($_img, 30, 30, false, array('alt' => ''));?>
						<?php echo $this->Html->link(sprintf('<i class="icon-male"></i> <span class="username">%s</span> <i class="icon-caret-down small"></i>', $this->Session->read('Auth.User.firstname')), '#', array('class' => 'dropdown-toggle', 'data-toggle' => 'dropdown', 'escape' => false));?>
						<?php echo $this->Module->load('usermenu');?>
					</li>
				</ul>
			</div>
		</header>
		<div id="container">
			<div id="sidebar" class="sidebar-fixed">
				<div id="sidebar-content">
					<?php echo $this->Menu->create(4, array('id' => 'nav'), 'ul', 'sub-menu');?>
					<?php echo $this->element('notifications');?>
				</div>
				<div id="divider" class="resizeable"></div>
			</div>
			<div id="content">
				<div class="container">
					<?php if( $this->Session->check('Message.flash') || $this->Session->check('Message.auth') ) { ?>
						<?php echo $this->Session->flash();?>
						<?php echo $this->Session->flash('auth');?>
					<?php } ?>
					<?php echo $this->fetch('content'); ?>
					<div class="content-footer">
						<span class="tiny"><i class="icon-coffee"></i> <?php echo __('Build');?> <?php echo $version;?>_<?php echo Configure::version();?></span>
						<?php echo $this->Html->image('webwidgetcms-logo.png', array('alt' => __('Webwidget CMS Admin'), 'style' =>'height:40px;width:auto;float:right;clear:both;'));?>
					</div>
					<?php //echo $this->element('sql_dump'); ?>
				</div>
			</div>
		</div>
	<?php echo $this->Html->scriptBlock('var _baseurl = \'' . $this->webroot . '\';');?>
	<?php echo $this->Html->script(array('libs/jquery-1.10.2.min', 'plugins/jquery-ui/jquery-ui-1.10.2.custom.min', 'bootstrap.min', 'libs/lodash.compat.min'));?>
	<!--[if lt IE 9]>
	<?php echo $this->Html->script(array('libs/html5shiv'));?>
	<![endif]-->
	<?php echo $this->Html->script(array('plugins/touchpunch/jquery.ui.touch-punch.min', 'plugins/event.swipe/jquery.event.move.js', 'plugins/event.swipe/jquery.event.swipe'));?>
	<?php echo $this->Html->script(array('libs/breakpoints', 'plugins/respond/respond.min', 'plugins/cookie/jquery.cookie.min', 'plugins/slimscroll/jquery.slimscroll.min', 'plugins/slimscroll/jquery.slimscroll.horizontal.min'));?>
	<?php echo $this->Html->script(array('plugins/noty/jquery.noty.js', 'plugins/noty/layouts/top.js', 'plugins/noty/themes/default.js'));?>
	<?php echo $this->fetch('script');?>
	<?php echo $this->Html->script(array('app', 'plugins', 'plugins.form-components', 'custom'));?>
	<?php echo $this->Html->scriptBlock("$(document).ready(function(){'use strict'; App.init(); Plugins.init(); FormComponents.init(); });");?>
	<?php //echo $this->Xhtml->googleAnalytics();?>
	<?php //echo $this->Xhtml->visit();?>
	</body>
</html>

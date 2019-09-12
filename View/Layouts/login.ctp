<?php echo $this->Html->docType(); ?>
<html lang="en">
	<head>
		<?php echo $this->Html->charset(); ?>
		<title><?php echo $this->Xhtml->pagetitle($title_for_layout, 'admin');?></title>
		<?php echo $this->Html->meta('icon');?>
		<?php echo $this->Html->meta('description', Configure::read('MySite.meta_description')); ?>
		<?php echo $this->Html->meta('keywords', Configure::read('MySite.meta_keywords'));?>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
		<?php echo $this->Html->css(array('bootstrap.min.css', 'main', 'plugins', 'responsive', 'icons', 'login', 'fontawesome/font-awesome.min.css'));?>
		<?php echo $this->Html->css(array('http://fonts.googleapis.com/css?family=Open+Sans:400,600,700|Shadows+Into+Light'));?>
		<!--[if IE 7]>
		<?php echo $this->Html->css(array('fontawesome/font-awesome-ie7.min.css'));?>
		<![endif]-->
		<!--[if IE 8]>
		<?php echo $this->Html->css(array('ie8'));?>
		<![endif]-->
		<?php echo $this->fetch('css');?>
	</head>
	<body class="login">
		<div class="logo alternative-font">
			<?php echo $this->Html->image('webwidgetcms-logo.png', array('alt' => __('Webwidget CMS Admin')));?>
		</div>
		<div class="box">
			<div class="content">
				<?php echo $this->fetch('content'); ?>
			</div>
		</div>
	<?php echo $this->Html->script(array('libs/jquery-1.10.2.min', 'bootstrap.min'));?>
	<?php echo $this->Html->script(array('libs/lodash.compat.min'));?>
	<!--[if lt IE 9]>
	<?php echo $this->Html->script(array('libs/html5shiv'));?>
	<![endif]-->
	<?php echo $this->Html->script(array('login'));?>
	<?php echo $this->Html->scriptBlock("$(document).ready(function(){'use strict'; Login.init();});");?>
	<?php echo $this->fetch('script');?>
	<?php echo $this->Xhtml->googleAnalytics();?>
	<?php echo $this->Xhtml->visit();?>
	</body>
</html>

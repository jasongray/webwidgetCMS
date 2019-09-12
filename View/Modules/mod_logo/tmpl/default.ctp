<div class="logo <?php echo  $m['class'];?>">
<?php if ($m['logo_layout'] == 1 && !empty($m['logo'])) { ?>
	<?php echo $this->Html->image('/img/logo/'.$m['logo'], array('url' => array('controller' => 'pages', 'action' => 'home'), 'height' => 50));?>
<?php } else if ($m['logo_layout'] == 2 && !empty($m['logo'])) { ?>
	<?php echo $this->Html->image('/img/logo/'.$m['logo'], array('url' => array('controller' => 'pages', 'action' => 'home'), 'height' => 50));?>
	<?php echo $this->Html->tag('h3', Configure::read('MySite.site_name'));?>
<?php } else { ?>
	<?php echo $this->Html->tag('h3', Configure::read('MySite.site_name'));?>
<?php } ?>
</div>
<?php $tags = $helper->getTags($m);?>
<?php if (!empty($tags)) { ?>
<div class="widget <?php echo $m['class'];?>">
	<?php if ($m['show_title'] == 1) { ?>
	<?php echo $this->Html->tag('h4', $m['title'], array('class' => 'widget_title'));?>
	<?php } ?>
	<div class="tagcloud">
	<?php foreach ($tags as $t) { ?>
		<?php echo $this->Html->link($t, array('controller' => 'news', 'action' => 'tagged', 'tag' => urlencode(strtolower($t))), array('escape' => false));?>
	<?php } ?>
	</div>
</div>
<?php } ?>
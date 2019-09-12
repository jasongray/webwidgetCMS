<?php $alerts = $helper->getAlerts($m);?>
<?php if ($alerts) { ?>
<div class="breaking_news hidden-xs">
	<?php if ($m['show_title'] == 1) { ?>
	<div class="bn_title">
		<?php echo $this->Html->tag('h2', $m['title'], array('class' => $m['header_class']));?><span></span>
	</div>
	<?php } ?>
	<ul class="breaking_news_slider<?php if(count($alerts) <= 1){ echo ' single-slide';}?>">
		<?php foreach ($alerts as $a) { ?>
		<?php $_class = (!empty($a['Alert']['hexcode']))? ' style="color:'.$a['Alert']['hexcode'].';"': '';?>
		<li<?php echo $_class;?>><?php echo $a['Alert']['content'];?></li>
		<?php } ?>
	</ul>
</div>
<?php echo $this->Html->scriptBlock('
$(window).load(function () {
	$(".breaking_news_slider").show();
	var slider = $(".breaking_news_slider").bxSlider({
		mode: "vertical",
		slideMargin: 5,
		auto: true,
		controls: false
	});
	if ($(".breaking_news_slider.single-slide").length > 0) {
		slider.destroySlider();
	}
});', array('inline' => false));?>
<?php } ?>
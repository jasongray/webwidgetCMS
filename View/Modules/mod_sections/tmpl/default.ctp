<?php if (isset($m['images']) && !empty($m['images']) && $m['in_content'] == 0) { ?>
<section class="se-section <?php echo $m['class'];?> parallex-bg" data-parallax="scroll" data-image="<?php echo $this->Resize->image('backgrounds' . DS . $m['images']['0'], 1300, 800, true, array(), true);?>" data-speed="0.4">
<?php } else { ?>
<section class="se-section <?php echo $m['class'];?>">
<?php } ?>
	<?php if (isset($m['images']) && !empty($m['images']) && $m['in_content'] == 1) { ?>
	<?php $_side = ($m['img_side'] == 'right')? 'col-md-push-6': '';?>
	<div class="col-md-6 zero-padding <?php echo $_side;?> side-img">
		<?php echo $this->Resize->image('backgrounds' . DS . $m['images']['0'], 1300, 800, true, array('class' => 'img-responsive', 'alt' => ''));?>
	</div>
	<?php } ?>
	<div class="container <?php echo $m['header_class'];?>">
		<?php if ($m['show_title'] == 1) { ?>
		<div class="row text-center">
			<?php echo $this->Html->tag('h3', $m['title'], array('class' => 'underline mtn'));?>
		</div>
		<?php } ?>
		<?php if (!empty($m['content'])) { ?>
		<div class="row">
			<?php echo $m['content'];?>
		</div>
		<?php } ?>
	</div>
</section>
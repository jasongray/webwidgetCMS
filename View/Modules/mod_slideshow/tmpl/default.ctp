<?php if (isset($m['images']) && !empty($m['images'])) { ?>
<div class="main_slider clearfix">
	<div class="big_silder col-md-12">
		<div class="row">
			<ul id="big-slid-post" class="a-post-box<?php if(count($m['images']) <= 1){ echo ' single-slide';}?>">
			<?php foreach($m['images'] as $i) { ?>
				<li>
					<?php echo $this->Resize->image('slideshows' . DS . $i, 1000, 800, true);?>
				</li>
			<?php } ?>
			</ul>
		</div>
	</div>
</div>
<?php } ?>
<?php echo $this->Html->css(array('owl.transitions'), array('block' => 'css'));?>
<?php $slider_speed = (isset($m['sliderspeed']) && !empty($m['sliderspeed']))? 'slideSpeed : '.$m['sliderspeed'].',': 'slideSpeed : 1000,';?>
<?php $slider_transition = (isset($m['slidertransition']) && !empty($m['slidertransition']))? 'transitionStyle : "'.$m['slidertransition'].'",': '';?>
<?php echo $this->Html->scriptBlock('
$(window).load(function () {
	$("#big-slid-post").show();
});
$(document).ready(function() {
  $("#big-slid-post").owlCarousel({
    autoPlay: true, //Set AutoPlay to 3 seconds
    navigation : true, // Show next and prev buttons
    paginationSpeed : 1000,
    stopOnHover : true,
    singleItem:true,
    responsive:true,
    '.$slider_speed.'
    '.$slider_transition.'
  });
  if ($("#big-slid-post.single-slide").length > 0) {
	$("#big-slid-post").data("owlCarousel").destroy();
  }
});', array('inline' => false));?>
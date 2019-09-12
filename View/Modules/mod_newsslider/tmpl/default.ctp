<?php $posts = $helper->getPosts($m);?>
<?php if (!empty($posts)) { ?>
<div class="main_slider clearfix">
	<div class="big_silder col-md-12">
		<div class="row">
			<ul id="big-slid-post" class="a-post-box<?php if(count($posts) <= 1){ echo ' single-slide';}?>">
			<?php foreach($posts as $p) { ?>
				<li>
					<div class="feat-item img-section" data-bg-img="<?php echo $this->Resize->image('articles'.DS.$p['News']['image'], 1100, 1100, true, array(), true);?>">
						<div class="latest-overlay"></div>
						<div class="latest-txt">
							<span class="latest-cat"><?php echo $this->Html->link($p['Category']['title'], array('controller' => 'categories', 'action' => 'view', 'id' => $p['Category']['id']));?></span>
							<h3 class="latest-title"><?php echo $this->Html->link($p['News']['subheading'], array('controller' => 'news', 'action' => 'view', $p['News']['id']), array('rel' => 'bookmark', 'title' => $p['News']['title']));?></h3>
							<div class="big-latest-content">
								<?php echo $p['News']['intro_text'];?>
							</div>
							<div class="hz_bottom_post">
								<div class="hz_read_more">
									<?php echo $this->Html->link(__('Read More'), array('controller' => 'news', 'action' => 'view', $p['News']['id']), array());?>
								</div>
								<span class="latest-meta">
									<span class="latest-date"><i class="fa fa-clock-o"></i> <?php echo date('M j, Y', strtotime($p['News']['start_publish']));?></span>
								</span>
							</div>
						</div>
					</div>
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
	$(".img-section").each( function() {
    var section = $(this);
    var bg = $(this).attr("data-bg-img");
    section.css("background-image", "url(\'"+bg+"\')");
  });
  }
});', array('inline' => false));?>
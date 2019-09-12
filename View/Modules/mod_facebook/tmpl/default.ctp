<?php $class = !empty($m['class'])? $m['class']: 'span12';?>
<div class="<?php echo $class;?>">
	<?php if ($m['show_title']) { ?>
	<?php echo $this->Html->tag('h4', $this->Html->link('<span>' . $m['title'] . '</span>', Configure::read('MySite.social_fb'), array('escape' => false)), array('class' => 'title ' . $m['header_class']));?>
	<?php } ?>
	<div class="fb-like-box" data-href="<?php echo Configure::read('MySite.social_fb');?>" data-colorscheme="light" data-show-faces="true" data-header="false" data-stream="false" data-show-border="false">&nbsp;</div>
    <div id="fb-root">&nbsp;</div>
</div>
<?php echo $this->Html->scriptBlock('
(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_GB/all.js#xfbml=1&appId=250244214990547";
  fjs.parentNode.insertBefore(js, fjs);
}(document, \'script\', \'facebook-jssdk\'));', array('inline' => false));?>
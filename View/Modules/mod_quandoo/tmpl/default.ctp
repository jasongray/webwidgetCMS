<?php 
$quandoo_url = "https://booking-widget.quandoo.com.au/iframe.html";
$quandoo_qs  = array(
	'agentId' => 2, 
	'merchantId' => 0, 
	'origin' => 'http://dev.quandoodrafts.com',
	'path' => 'https://booking-widget.quandoo.com/',
);
if (!empty($m['merchid'])) {
	$quandoo_qs['merchantId'] = $m['merchid'];
}
?>
<div class="<?php echo $m['class'];?>">
<?php /* inline calendar */
if($m['layout'] == 1) { ?>
	<div id="quandoo-booking-widget"></div>
	<?php echo $this->Html->script('https://booking-widget.quandoo.com/index.js', array('data-merchant-id' => $m['merchid']));?>
<?php } ?>

<?php /* floating widget */   
if($m['layout'] == 3) { ?>
<div style="position: fixed; bottom: 0px; right: 0px; z-index: 9999;"> 
	<?php echo $this->Html->link($this->Html->image('http://dev.quandoodrafts.com/widget-sources/button.png', array('alt' => '', 'width' => 145))
, $quandoo_url . '?' . http_build_query($quandoo_qs), array('class' => 'quandoo_btn_floating', 'escape' => false));?>
</div>
<?php echo $this->Html->scriptBlock("
$(document).ready(function(){

	if ($('.quandoo_btn_floating').length > 0) {
		$('.quandoo_btn_floating').click(function(e) {
			e.preventDefault();
			window.open(this.href, '" . Configure::read('MySite.site_name') . "', 'resizable=no,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no,width=350,height=650');
		});
	}

});
", array('inline' => false));?>
<?php } ?>

<?php /* modal form */
if($m['layout'] == 4) { ?>
	<?php echo $this->Html->link($m['btn_text'], '#', array('class' =>  'quandoo_btn_modal ' . $m['btn_class']));?>
	<div class="modal fade" id="quandoo_modal" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title"><?php echo __('Book a table');?></h4>
				</div>
				<div class="modal-body" style="min-height:810px;">
					<iframe src="<?php echo $quandoo_url . '?' . http_build_query($quandoo_qs);?>" frameborder="0" width="380" height="810" style="display:block;margin: 0 auto;"></iframe>
				</div>
				<div class="modal-footer">
					<?php echo $this->Html->link(__('Close'), array('#'), array('class' => 'btn btn-primary', 'data-dismiss' => 'modal'));?>
				</div>
			</div>
		</div>
	</div>
<?php echo $this->Html->scriptBlock("
$(document).ready(function(){

	if ($('.quandoo_btn_modal').length > 0) {
		$('#quandoo_modal').modal({
			'show' : false
		});
		$('.quandoo_btn_modal').click(function(e) {
			e.preventDefault();
			$('#quandoo_modal').modal('show');
		});
	}

});
", array('inline' => false));?>
<?php } ?>
</div>
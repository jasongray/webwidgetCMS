<?php $this->Html->pageClass = 'galleries';?>
<?php $this->Html->pageTitle = __('Gallery Manager');?>
<?php $this->Paginator->options(array('url' => $this->passedArgs)); ?>
<?php $this->Html->addCrumb(__('Galleries'));?>
<?php echo $this->element('crumb-div');?>
<?php echo $this->element('page-header');?>
<div class="row">
	<div class="col-md-12">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-camera"></i> <?php echo __('Galleries', true);?></h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content">
				<?php echo $this->Html->link('Create Gallery', array('controller' > 'galleries', 'action' => 'add', 'admin' => 'admin'), array('class'=>'btn btn-warning', 'escape' => false))?>
				<?php if (!empty($gallery)) { ?>
				<div class="gallery row">
					<?php foreach ($gallery as $g) { ?>
					<div id="<?php echo $g['Gallery']['id'];?>" class="col-md-3">
						<div class="thumbnail">
							<?php echo $this->Resize->image('galleries/'.$g['Gallery']['id'].'/'.$g['Gallery']['primaryfile'], 300, 300, true);?>
							<div class="caption">
								<?php echo $this->Html->tag('h4', $this->Html->link($g['Gallery']['name'], array('controller' => 'galleries', 'action' => 'edit', $g['Gallery']['id']), array('escape' => true)));?>
								<?php echo $this->Html->tag('h5', '<i class="icon-calendar"></i> ' . date('d M Y H:i A', strtotime($g['Gallery']['created'])));?>
								<?php echo $this->Html->tag('h5', '<i class="icon-camera"></i> ' . $g['Gallery']['imgcount']);?>
							</div>
						</div>
					</div>
					<?php } ?>
				</div>
				<?php } ?>
				<?php echo $this->element('paginator');?>
			</div>
		</div>
	</div>
</div>
<?php echo $this->Html->scriptBlock('
	$(".gallery").sortable({ 
		update: function(e, u){ 
			$.post("'.$this->Html->url(array('controller' > 'galleries', 'action' => 'sort', 'admin' => 'admin')).'", 
				{ order: $(".gallery").sortable("toArray").join(",") }
			); 
		}
	});', array('inline' => false));?>
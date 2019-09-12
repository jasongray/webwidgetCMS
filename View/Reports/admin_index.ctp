<?php $this->Html->pageClass = 'reports';?>
<?php $this->Html->pageTitle = __('Reports');?>
<?php $this->Paginator->options(array('url' => $this->passedArgs)); ?>
<?php $this->Html->addCrumb(__('Reports'));?>
<?php echo $this->element('crumb-div');?>
<?php echo $this->element('page-header');?>
<div class="row">
	<div class="col-md-12">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-reorder"></i> <?php echo __('Reports', true);?></h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content">
				<div class="row">
				<?php if(!empty($reports)) { ?>
					<?php foreach($reports as $r) { ?>
					<div class="col-md-2">
					<?php echo $this->Html->link('<i class="icon-bar-chart"></i><div>'.Inflector::humanize($r).'</div>', array('controller' => 'reports', 'action' => 'run', $r), array('class' => 'btn btn-icon input-block-level', 'escape' => false));?>
					</div>
					<?php } ?>
				<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>
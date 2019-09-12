<?php $this->Html->pageClass = 'system';?>
<?php $this->Html->pageTitle = __('System Manager');?>
<?php $this->Paginator->options(array('url' => $this->passedArgs)); ?>
<?php $this->Html->addCrumb(__('System'));?>
<?php echo $this->element('crumb-div');?>
<?php echo $this->element('page-header');?>
<?php echo $this->Html->script(array('plugins/bootbox/bootbox.min', 'system'), array('inline' => false));?>
<div class="row">
	<div class="col-md-5">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-reorder"></i> <?php echo __('CMS Changelog');?></h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content">
				<?php if ($update) { ?>
				<?php echo $this->element('update-available');?>
				<?php } else { ?>
					<div class="alert alert-success fade in">
						<i data-dismiss="alert" class="icon-remove close"></i>
						<strong><?php echo __('WebwidgetCMS is up to date!');?></strong>
					</div>
				<?php } ?>
				<pre><?php echo $changelog;?></pre>
			</div>
		</div>

	</div>

	<div class="col-md-7">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-reorder"></i> <?php echo __('Database');?></h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content">
				<?php echo $this->Html->link(__('Backup Database'), array('controller' => 'system', 'action' => 'backupDB', 'admin' => 'admin'), array('class' => 'btn btn-navbar right'));?>
				<table class="table table-striped databasetables">
					<thead>
					<tr>
						<th><?php echo __('Table Name');?></th>
						<th><?php echo __('Rows');?></th>
						<th><?php echo __('Actions');?></th>
						<th><?php echo __('Size');?></th>
						<th><?php echo __('Overhead');?></th>
					</tr>
					</thead>
					<tbody>
					<?php foreach ($tables as $t) { ?> 
					<tr>
						<td><?php echo $t['Name'];?></td>
						<td><?php echo $t['Rows'];?></td>
						<td><?php echo $this->Html->link('<i class="icon icon-trash"></i>', array('controller' => 'system', 'action' => 'emptyTable', 'table' => $t['Name']), array('escape' => false));?></td>
						<td><?php echo $this->Number->toReadableSize($t['Index_length']);?></td>
						<?php if ($t['Data_free'] > 0) { ?>
						<td><span class="badge badge-warning"><?php echo $this->Number->toReadableSize($t['Data_free']);?> <?php echo $this->Html->link('<i class="icon icon-magic"></i>', array('controller' => 'system', 'action' => 'optimiseTable', 'table' => $t['Name']), array('escape' => false));?></span></td>
						<?php } else { ?>
						<td>-</td>
						<?php } ?>
					</tr>
					<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<?php echo $this->Html->scriptBlock('
jQuery(document).ready(function() {
	System.init();
});', array('inline' => false));?>
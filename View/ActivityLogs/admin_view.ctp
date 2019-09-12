<?php $this->Html->pageClass = 'logs';?>
<?php $this->Html->pageTitle = __('View Log');?>
<?php $this->Html->addCrumb(__('Activity Logs'), array('controller' => 'activityLogs', 'action' => 'index', 'admin' => 'admin', 'plugin' => false));?>
<?php $this->Html->addCrumb(__('View Log Entry'));?>
<?php echo $this->element('crumb-div');?>
<?php echo $this->element('page-header');?>
<?php echo $this->Form->create('ActivityLog', array('class' => 'form-horizontal row-border', 'type' => 'file'));?>
<div class="row">
	<div class="col-md-12">
		<div class="row">
			<div class="col-md-1">
				<div class="list-group">
						<?php $label = (!empty($l['ActivityLog']['type']))? 'label-'.$this->Xhtml->iconreplace($l['ActivityLog']['type']): '';?>
						<span class="label <?php echo $label;?>" style="display:block;padding:0.5em 0.7em 0.3em;">
							<i class="<?php echo $this->Xhtml->iconme($l['ActivityLog']['description']);?> icon-5x"></i>
						</span>
				</div>
			</div>
			<div class="col-md-9">
				<div class="row profile-info">
					<div class="col-md-12">
						<h1><?php echo strtoupper($l['ActivityLog']['type']);?></h1>

						<dl class="dl-horizontal">
							<dt><?php echo __('Log Description');?></dt>
							<dd><?php echo $l['ActivityLog']['description'];?></dd>
							<dt><?php echo __('User');?></dt>
							<dd><?php echo $l['User']['username'];?></dd>
							<dt><?php echo __('IP');?></dt>
							<dd><?php echo $l['ActivityLog']['ipaddr'];?></dd>
							<dt><?php echo __('Created');?></dt>
							<dd><?php echo date('d-m-Y H:i', strtotime($l['ActivityLog']['created']));?></dd>
						</dl>
						<?php echo $this->Html->link(__('Delete'), array('activityLogs' => 'comments', 'action' => 'delete', $l['ActivityLog']['id']), array('class' => 'btn btn-lg btn-default pull-right'));?>
						<?php echo $this->Html->link(__('Back to Logs'), array('activityLogs' => 'comments', 'action' => 'index'), array('class' => 'btn btn-lg btn-inverse pull-right'));?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php echo $this->Form->end();?>
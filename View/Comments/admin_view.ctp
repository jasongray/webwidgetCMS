<?php $this->Html->pageClass = 'comments';?>
<?php $this->Html->pageTitle = __('View Comment');?>
<?php $this->Html->addCrumb(__('Comments'), array('controller' => 'comments', 'action' => 'index', 'admin' => 'admin', 'plugin' => false));?>
<?php $this->Html->addCrumb(__('View Comment'));?>
<?php echo $this->element('crumb-div');?>
<?php echo $this->element('page-header');?>
<?php echo $this->Form->create('Comment', array('class' => 'form-horizontal row-border', 'type' => 'file'));?>
<div class="row">
	<div class="col-md-12">
		<div class="row">
			<div class="col-md-3">
				<div class="list-group">
					<li class="list-group-item no-padding">
						<?php if( empty($c['User']['avatar']) ) { 
							$_img = 'avatar-large.jpg';
						} else {
							$_img = $c['User']['avatar'];
						}
						echo $this->Resize->image($_img, 390, 390, false, array('alt' => ''));?>
					</li>
				</div>
			</div>
			<div class="col-md-9">
				<div class="row profile-info">
					<div class="col-md-7">
						<?php if($c['Comment']['status'] == 9){ ?>
						<div class="alert alert-warning"><?php echo __('Approval required before comment is published')?></div>
						<?php } ?>
						<h1><?php echo $c['Comment']['name']?></h1>

						<dl class="dl-horizontal">
							<dt><?php echo __('News Article');?></dt>
							<dd><?php echo $c['News']['title'];?></dd>
							<dt><?php echo __('User Email');?></dt>
							<dd><?php echo $c['Comment']['email'];?></dd>
							<dt><?php echo __('Spam Score');?></dt>
							<dd><?php echo $c['Comment']['points'];?></dd>
							<dt><?php echo __('Comment Posted');?></dt>
							<dd><?php echo date('d-m-Y H:i', strtotime($c['Comment']['created']));?></dd>
							<dt><?php echo __('Comment Details');?></dt>
							<dd><?php echo nl2br($c['Comment']['details']);?></dd>
							<?php if($c['Comment']['status'] != 9) { ?>
							<dt><?php echo __('Date Approved');?></dt>
							<dd><?php echo date('d-m-Y H:i', strtotime($c['Comment']['modified']));?></dd>	
							<?php } ?>
						</dl>
						<?php if($c['Comment']['status'] != 1) { ?>
						<?php echo $this->Html->link(__('Approve Comment'), array('controller' => 'comments', 'action' => 'approve', md5($c['Comment']['id'] . $c['Comment']['email'])), array('class' => 'btn btn-lg btn-info'));?>	
						<?php } ?>
						<?php echo $this->Html->link(__('Delete'), array('controller' => 'comments', 'action' => 'delete', $c['Comment']['id']), array('class' => 'btn btn-lg btn-default pull-right'));?>
						<?php echo $this->Html->link(__('Back to Comments'), array('controller' => 'comments', 'action' => 'index'), array('class' => 'btn btn-lg btn-inverse pull-right'));?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php echo $this->Form->end();?>
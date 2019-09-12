<?php $posts = $helper->getPosts($m);?>
<?php if (!empty($posts)) { ?>
<?php $i = 0;?>
<div class="block_posts  <?php echo $m['class'];?>">
	<?php if ($m['show_title'] == 1) { ?>
	<div class="featured_title">
		<?php echo $this->Html->tag('h4', $m['title']);?>
		<?php if (isset($m['category_id'])) { ?>
		<?php echo $this->Html->link(__('View All'), array('controller' => 'categories', 'action' => 'view', $m['category_id'], 'plugin' => false, 'admin' => false), array('class' => 'view_button'));?>
		<?php } else { ?>
		<?php echo $this->Html->link(__('View All'), array('controller' => 'news', 'action' => 'index', 'plugin' => false, 'admin' => false), array('class' => 'view_button'));?>
		<?php } ?>
	</div>
	<?php } ?>
	<div class="block_inner row">
	<?php if (!empty($m['leading_articles'])) { ?>
		<div class="big_post col-sm-7 col-sm-push-5">
		<?php for ($i=0;$i<$m['leading_articles'];$i++) { ?>
			<?php $p = $posts[$i];?>
			<div class="big_post_wrapper">
				<?php if(!empty($p['News']['image'])) { ?>
				<div class="block_img_post">
					<?php echo $this->Resize->image('articles/'.$p['News']['image'], 550, 550, true, array('alt' => $p['News']['title'], 'url' => array('controller' => 'news', 'action' => 'view', $p['News']['id'])));?>
				</div>
				<?php } ?>
				<div class="inner_big_post">
					<div class="title_post">
						<?php echo $this->Html->link($this->Html->tag('h4', $p['News']['title']), array('controller' => 'news', 'action' => 'view', $p['News']['id']), array('escape' => false));?>
					</div>
					<div class="big_post_content">
						<?php echo $p['News']['intro_text'];?>
					</div>
					<div class="post_date">
						<?php echo $this->Html->tag('em', $this->Html->link(date('M j, Y', strtotime($p['News']['start_publish'])), array('controller' => 'news', 'action' => 'view', $p['News']['id']), array('escape' => false)));?>
					</div>
				</div>
			</div>
		<?php } ?>
		</div>
	<?php } else { ?>
		<div class="small_list_post col-sm-7 col-sm-push-5">
			<ul>
			<?php $count = count($posts)-$i;?>
			<?php for ($j=0;$j<$count;$j++) { ?>
				<?php $p = $posts[$i];?>
				<li class="small_post clearfix">
					<?php if(!empty($p['News']['image'])) { ?>
					<div class="img_small_post">
						<?php echo $this->Resize->image('articles/'.$p['News']['image'], 250, 250, true, array('alt' => $p['News']['title'], 'url' => array('controller' => 'news', 'action' => 'view', $p['News']['id'])));?>
					</div>
					<?php } ?>
					<div class="small_post_content">
						<div class="title_small_post">
							<?php echo $this->Html->link($this->Html->tag('h5', $p['News']['title']), array('controller' => 'news', 'action' => 'view', $p['News']['id']), array('escape' => false));?>
						</div>
						<div class="post_date">
							<?php echo $this->Html->tag('em', $this->Html->link(date('M j, Y', strtotime($p['News']['start_publish'])), array('escape' => false)));?>
						</div>
					</div>
				</li>
				<?php $i++;?>
			<?php } ?>
			</ul>
		</div>
	<?php } ?>
		<div class="small_list_post col-sm-5 col-sm-pull-7">		
			<ul>
			<?php $count = count($posts)-$i;?>
			<?php for ($j=0;$j<$count;$j++) { ?>
				<?php $p = $posts[$i];?>
				<li class="small_post clearfix">
					<?php if(!empty($p['News']['image'])) { ?>
					<div class="img_small_post">
						<?php echo $this->Resize->image('articles/'.$p['News']['image'], 250, 250, true, array('alt' => $p['News']['title'], 'url' => array('controller' => 'news', 'action' => 'view', $p['News']['id'])));?>
					</div>
					<?php } ?>
					<div class="small_post_content">
						<div class="title_small_post">
							<?php echo $this->Html->link($this->Html->tag('h5', $p['News']['title']), array('controller' => 'news', 'action' => 'view', $p['News']['id']), array('escape' => false));?>
						</div>
						<div class="post_date">
							<?php echo $this->Html->tag('em', $this->Html->link(date('M j, Y', strtotime($p['News']['start_publish'])), array('escape' => false)));?>
						</div>
					</div>
				</li>
				<?php $i++;?>
			<?php } ?>
			</ul>
		</div>
	</div>
</div>
<?php } ?>
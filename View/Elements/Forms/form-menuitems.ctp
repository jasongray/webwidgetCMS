<?php echo $this->Form->input('title', array('div' => 'form-group', 'class' => 'form-control', 'label' => array('text' => __('Title'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>')); ?>
<?php echo $this->Form->input('iclass', array('div' => 'form-group', 'class' => 'form-control', 'label' => array('text' => __('Icon'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10"><div class="input-group">', 'after' => '<span class="input-group-addon"><a href="#iconModal" role="button" data-toggle="modal"><i class="icon-question-sign"></i></a></span></div></div>')); ?>
<?php echo $this->Form->input('class', array('div' => 'form-group', 'class' => 'form-control', 'label' => array('text' => __('Item Class'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>')); ?>
<div class="form-group">
	<label class="col-md-2 control-label"><?php echo __('Publish');?></label>
	<div class="col-md-10">
		<?php echo $this->Form->input('published', array('div' => false, 'label' => array('text' => '', 'class' => 'checkbox'), 'class' => 'uniform', 'type' => 'checkbox')); ?>
	</div>
</div>
<div class="form-group">
	<label class="col-md-2 control-label"><?php echo __('Homepage Link');?></label>
	<div class="col-md-10">
		<?php echo $this->Form->input('default', array('div' => false, 'label' => array('text' => '', 'class' => 'checkbox'), 'class' => 'uniform', 'type' => 'checkbox')); ?>
	</div>
</div>
<?php echo $this->Form->input('parent_id', array('div' => 'form-group', 'class' => 'select2-select-00 col-md-12 full-width-fix', 'label' => array('text' => __('Parent Item'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>', 'options' => $parents, 'empty' => '')); ?>
<?php echo $this->Form->input('menu_id', array('div' => 'form-group', 'class' => 'select2-select-00 col-md-12 full-width-fix', 'label' => array('text' => __('Menu selection'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>', 'options' => $menus, 'selected' => $this->passedArgs['menu_id'], 'empty' => '')); ?>
<?php echo $this->Form->input('permissions', array('div' => 'form-group', 'class' => 'select2-select-00 col-md-12 full-width-fix', 'label' => array('text' => __('Select Group'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '<span class="help-block align-right">'.__('Select the lowest group who can access this menu item. Leave blank for all user levels.').'</span></div>', 'options' => $_groups, 'data-placeholder' => __('Lowest group who can access this menu item?'), 'empty' => ''));?>
<div class="form-actions">
<?php
	echo $this->Form->hidden('id');
	echo $this->Form->submit('Save', array('class'=>'btn btn-primary', 'div' => false)); 
	echo $this->Html->link('Cancel', array('controller' => 'menuItems', 'action' => 'cancel', 'admin' => 'admin', 'plugin' => false, 'menu_id' => $this->passedArgs['menu_id']), array('class' => 'btn btn-orange'));
	if(!empty($this->data['MenuItem']['id'])){
		echo $this->Html->link('Delete', array('controller' => 'menuItems', 'action' => 'delete', 'admin' => 'admin', 'plugin' => false, $this->data['MenuItem']['id'], 'menu_id' => $this->passedArgs['menu_id']), array('class' => 'btn btn-grey'));
	}
?>	
</div>

<?php /* ?>
				<?php echo $this->Form->input('page_title', array('div' => 'control-group', 'class' => 'm-wrap large', 'label' => array('text' => __('Page Title'), 'class' => 'control-label'), 'between' => '<div class="controls">', 'after' => '</div>')); ?>
				<div class="control-group">
					<label class="control-label"><?php echo __('Show Page Title');?></label>
					<div class="controls">
						<div class="info-toggle-button toggle-button">
							<?php echo $this->Form->input('show_title', array('div' => false, 'class' => 'toggle', 'type' => 'checkbox', 'label' => false));?>
						</div>
					</div>
				</div>
				<?php echo $this->Form->input('page_meta', array('div' => 'control-group', 'class' => 'm-wrap span8', 'label' => array('text' => __('Meta Description'), 'class' => 'control-label'), 'between' => '<div class="controls">', 'after' => '</div>', 'rows' => 6));?>
				<?php echo $this->Form->input('page_kw', array('div' => 'control-group', 'class' => 'm-wrap span8', 'label' => array('text' => __('Meta Keywords'), 'class' => 'control-label'), 'between' => '<div class="controls">', 'after' => '</div>', 'rows' => 6));?>
				<div class="form-actions">
				<?php
					echo $this->Form->hidden('id');
					echo $this->Form->submit('Save', array('label' => __('Save'), 'class'=>'btn green', 'div' => false)); 
					echo $this->Html->link(__('Cancel'), array('controller' => 'menuItems', 'action' => 'cancel'), array('class' => 'btn black'));
					if(!empty($this->data['MenuItem']['id'])){
						echo $this->Html->link(__('Delete'), array('controller' => 'menuItems', 'action' => 'delete', $this->data['MenuItem']['id']), array('class' => 'btn red'));
					}
				?>	
				</div>
			</div>
		</div>
	</div>

	<div class="span6">
		<div class="portlet box blue">
			<div class="portlet-title">
				<h4><i class="icon-wrench"></i><?php echo __('Parameters');?></h4>
			</div>
			<div class="portlet-body form">
				<?php $_controller = (!empty($this->request->data['MenuItem']['controller']))? $this->request->data['MenuItem']['controller']: ''; ?>
				<?php echo $this->Form->input('controller', array('div' => 'control-group', 'class' => 'm-wrap', 'label' => array('text' => __('Select Link'), 'class' => 'control-label'), 'between' => '<div class="controls">', 'after' => '</div>', 'options' => $links, 'selected' => $_controller, 'empty' => ''));?>
				<?php echo $this->Form->input('action', array('div' => 'control-group', 'class' => 'm-wrap', 'label' => array('text' => __('Select Page'), 'class' => 'control-label'), 'between' => '<div class="controls">', 'after' => '</div>', 'options' => $slugs, 'selected' => $_slug, 'empty' => '', 'type' => 'select'));?>
				<hr/>
				<?php echo $this->Form->input('url', array('div' => 'control-group', 'class' => 'm-wrap span12', 'label' => array('text' => __('Url'), 'class' => 'control-label'), 'between' => '<div class="controls">', 'after' => '</div>'));?>
			</div>
		</div>
	</div>
	<div class="span6">
		<div class="portlet box blue extras" <?php if ($_controller == 'news') { echo 'style="display:block;"';} else {echo 'style="display:none;"';}?>>
			<div class="portlet-title">
				<h4><i class="icon-paper-clip"></i><?php echo __('Additional Parameters');?></h4>
			</div>
			<div class="portlet-body form">
				<?php echo $this->Form->input('MenuItem.params.leading_articles', array('div' => 'control-group', 'class' => 'm-wrap xsmall', 'label' => array('text' => __('Number of leading articles'), 'class' => 'control-label'), 'between' => '<div class="controls">', 'after' => '</div>'));?>
				<?php echo $this->Form->input('MenuItem.params.column_articles', array('div' => 'control-group', 'class' => 'm-wrap xsmall', 'label' => array('text' => __('Number of columns'), 'class' => 'control-label'), 'between' => '<div class="controls">', 'after' => '</div>'));?>
				<?php echo $this->Form->input('MenuItem.params.template', array('div' => 'control-group', 'class' => 'm-wrap small', 'label' => array('text' => __('Article Template'), 'class' => 'control-label'), 'between' => '<div class="controls">', 'after' => '</div>', 'options' => array('list' => 'List', 'grid' => 'Grid')));?>
				<?php echo $this->Form->input('MenuItem.params.bloglimit', array('div' => 'control-group', 'class' => 'm-wrap xsmall', 'label' => array('text' => __('Number of items to show'), 'class' => 'control-label'), 'between' => '<div class="controls">', 'after' => '</div>'));?>
			</div>
		</div>
	</div>
	<div class="span6">
		<div class="portlet box blue contact" <?php if ($_controller == 'contact') { echo 'style="display:block;"';} else {echo 'style="display:none;"';}?>>
			<div class="portlet-title">
				<h4><i class="icon-map-marker"></i><?php echo __('Google Map');?></h4>
			</div>
			<div class="portlet-body form">
				<div class="control-group">
					<label class="control-label"><?php echo __('Show Google Map');?></label>
					<div class="controls">
						<?php echo $this->Form->input('MenuItem.params.show_map', array('div' => false, 'label' => array('text' => '', 'class' => 'checkbox line'), 'type' => 'checkbox'));?>
					</div>
				</div>
				<?php echo $this->Form->input('MenuItem.params.lat', array('div' => 'control-group', 'class' => 'm-wrap xsmall', 'label' => array('text' => __('Latitude'), 'class' => 'control-label'), 'between' => '<div class="controls">', 'after' => '</div>'));?>
				<?php echo $this->Form->input('MenuItem.params.lng', array('div' => 'control-group', 'class' => 'm-wrap xsmall', 'label' => array('text' => __('Longitude'), 'class' => 'control-label'), 'between' => '<div class="controls">', 'after' => '</div>'));?>
				<span class="span12"><?php echo __('OR');?></span>
				<?php echo $this->Form->input('MenuItem.params.address_string', array('div' => 'control-group', 'class' => 'm-wrap span12', 'label' => array('text' => __('Full Address'), 'class' => 'control-label'), 'between' => '<div class="controls">', 'after' => '</div>'));?>
			</div>
		</div>
	</div>
	<div class="span6">
		<div class="portlet box blue categories" <?php if ($_controller == 'categories') { echo 'style="display:block;"';} else {echo 'style="display:none;"';}?>>
			<div class="portlet-title">
				<h4><i class="icon-bookmark"></i><?php echo __('Show on Category page');?></h4>
			</div>
			<div class="portlet-body form">
				<div class="control-group">
					<label class="control-label"><?php echo __('Show News Items?');?></label>
					<div class="controls">
						<?php echo $this->Form->input('MenuItem.params.shownews', array('div' => false, 'label' => array('text' => '', 'class' => 'checkbox line'), 'type' => 'checkbox'));?>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label"><?php echo __('Show Page Items?');?></label>
					<div class="controls">
						<?php echo $this->Form->input('MenuItem.params.showpages', array('div' => false, 'label' => array('text' => '', 'class' => 'checkbox line'), 'type' => 'checkbox'));?>
					</div>
				</div>
				<?php echo $this->Form->input('MenuItem.params.pagestyle', array('div' => 'control-group', 'class' => 'm-wrap small', 'label' => array('text' => __('Page Style'), 'class' => 'control-label'), 'between' => '<div class="controls">', 'after' => '</div>', 'options' => array('list' => 'List', 'grid' => 'Grid')));?>
				<?php echo $this->Form->input('MenuItem.params.catlimit', array('div' => 'control-group', 'class' => 'm-wrap xsmall', 'label' => array('text' => __('Number of items to show'), 'class' => 'control-label'), 'between' => '<div class="controls">', 'after' => '</div>'));?>
			</div>
		</div>
	</div>
</div>
<div id="iconModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
		<h3 id="myModalLabel1"><?php echo __('Sample Icons');?></h3>
	</div>
	<div class="modal-body">
		<?php echo $this->element('icons');?>
	</div>
	<div class="modal-footer">
		<button class="btn green" data-dismiss="modal" aria-hidden="true">Close</button>
	</div>
</div>
<?php echo $this->Form->end(); ?>
*/?>
<?php echo $this->Html->scriptBlock("
$(document).ready(function(){
	$('#MenuItemController').change(function(){
		var method = $(this).val();
		if(method!=''){
			$.ajax({
				type:'GET',
				cache:false,
				url:_baseurl+'admin/menuItems/getViews/method:'+method,
				success:function(html){
					$('#MenuItemAction').html(html);
				}
			});
		}
		if(method == 'news'){
			$('.extras').slideToggle();
		} else {
			$('.extras').slideUp();
		}
		if(method == 'contact'){
			$('.contact').slideToggle();
		} else {
			$('.contact').slideUp();
		}
		if(method == 'categories'){
			$('.categories').slideToggle();
		} else {
			$('.categories').slideUp();
		}
		return false;
	});
});
" , array('inline' => false));?>
	

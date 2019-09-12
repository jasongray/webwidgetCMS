<?php $attr = array(); ?>
<?php $attr = !empty($m['idclass'])? array_merge(array('id' => $m['idclass']), $attr): $attr;?>
<div class="widget <?php echo $m['class'];?>">
<?php if ($m['show_title']) { ?>
<?php $hdr = (!empty($m['header']))? $m['header']: 'h6';?>
<?php echo $this->Html->tag($hdr, $m['title'], array('class' => $m['header_class']));?>
<?php } ?>
<?php $this->Menu->selected = $m['selected'];?>
<?php echo $this->Menu->create($m['menu_id'], $attr, 'ul', $m['dropclassul'], $m['dropclassli']);?>
</div>
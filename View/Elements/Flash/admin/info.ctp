<?php echo $this->Html->scriptBlock(sprintf("var n = noty({type: 'information', text: '%s'});", $message), array('inline' => false));?>
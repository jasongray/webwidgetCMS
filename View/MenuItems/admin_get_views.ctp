<?php
if($options){
	echo '<option value=""></options>';
	foreach($options as $k => $v){
		echo '<option value="' . $k . '">' . $v . '</option>';
	}
}
?>
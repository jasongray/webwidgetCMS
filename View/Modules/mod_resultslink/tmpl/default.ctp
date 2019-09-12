<?php
$url = $m['link'];
$page = isset($_GET['page']) && !empty($_GET['page'])? $_GET['page'] : 'index.htm';
if (preg_match('/(.*?)\/series.htm/', $page, $m)) {
	$suffix = $m[1].'/';
	$linkback = 'index.htm';
	$linktext = 'Back to all results';
} else if(preg_match('/(.*?)\/(.*?).htm/', $page, $m)) {
	$suffix = $m[1].'/';
	$linkback = $suffix.'series.htm';
	$linktext = 'Back to series index';
} else {
	$suffix = '';
	$linkback = '';
	$linktext = '';
}
$data = @file_get_contents($url.$page);
preg_match("'<center>(.*?)<\/center>'si", $data, $result);
if (!empty($result)) {
	$result = preg_replace("/<br>/", '', $result);
	$result = preg_replace("/<p class=\"imgdisplay(.*?)<\/p>/", '', $result);
	$result = preg_replace("/<p>Results by(.*?)<\/p>/", '', $result);
	$result = preg_replace("/<p><a(.*)><u>LinkBack(.*?)<\/u><\/a><\/p>/", '', $result);
	$result = preg_replace("/<p><a(.*)><i>LinkBack(.*?)<\/i><\/a><\/p>/", '', $result);
	$result = preg_replace("/<a(.*)>Close Window<\/a>/", '', $result);
	$result = preg_replace("/<a(.*)>Print(.*)<\/p>/", '', $result);
	$result = preg_replace("/<a href=\"javascript:;\"(.*)<\/a>/", '', $result);
	$result = preg_replace("/<a href=\"(.*?)\">|<a href=(.*?)>/", "<a href=\"results.php?page=$suffix$1$2\">", $result[1]);
	$result = preg_replace("/<p align=\"center\"><img src=\"linkfile.gif\"><\/p>/", '<span class="icon"><i class="fa fa-file-text"></i></span>', $result);
}
echo $result;
?>

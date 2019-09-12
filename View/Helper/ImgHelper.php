<?php
/**
 *	Img Helper class file
 *
 *	Resizes images and stores a cached version of the resized image
 *
 *	Copyright (c) Webwidget Pty Ltd (http://webwidget.com.au)
 *
 *	Licensed under The MIT license
 *	Redistributions of this file must retain the above copyright notice
 *
 *	@copyright  	Copyright (c) Webwidget Pty Ltd (http://webwidget.com.au)
 *	@package		WebCart
 *	@author			Jason Gray
 *	@version		1.8
 *	@license 		http://www.opensource.org/licenses/mit-license.php MIT License
 *
 */

App::uses('AppHelper', 'View/Helper');

/**
*	ImgHelper class for creation of resized images
*
*	@package 		WebCart.Cake.View.Helper
*
*/
class ImgHelper extends AppHelper  {

/**
*	Reference to local img directory
*
*	@var 	string
*/
	public $localDir;

/**
*	Reference to local cache directory under the $localDir
*
*	@var 	string
*/
	public $cacheDir;

/**
*	Reference to remote directory to obtain images from
*
*	@var 	string
*/
	public $remoteDir;

/**
*	Reference to watermark image
*
*	@var 	string
*/
	public $watermarkImg;

/**
*	Reference to cached image file
*
*	@var 	string
*/
	public $cacheFile;

/**
*	Reference to local image file
*
*	@var 	string
*/
	public $localFile;

/**
*	Reference to remote image
*
*	@var 	string
*/
	public $remoteFile;

/**
*	Reference to temporary directory
*
*	@var 	string
*/
	public $tempDir;

/**
*	Reference to temporary file
*
*	@var 	string
*/
	public $tempFile;

/**
*	CakePHP helpers to load
*
*	@var 	array
*/
	public $helpers = array('Html');

/**
*	Constructor
*
*	Using the configFile option can define the watermark image
*
*	@param View $view The View this helper is being attached to.
*	@param array $settings Configuration settings for the helper.
*
*/
	public function __construct(View $View, $settings = array()) {
		parent::__construct($View, $settings);
		
		// set variables
		$this->localDir = APP . 'webroot' . DS . 'img' . DS;
		$this->cacheDir = 'cache';
		$this->watermarkImg = 'watermark.png';
		$this->tempDir = APP . 'tmp' . DS . 'imgs' . DS;
		
		$_watermk = Configure::read('MySite.watermark');
		if (!empty($_watermk)) {
			$this->watermarkImg = $_watermk;
		} 
		$_remoted = Configure::read('MySite.remoteDir');
		if (!empty($_remoted)) {
			$this->remoteDir = $_remoted;
		}
		
	}


/**
*	Resizes an image and stores a local cached copy of the resized image.
*
*	@param string $path Text for image file name.
*	@param integer $width Maximum width to resize the image to.
*	@param integer $height Maximum height to resize the image to.
*	@param bool $aspect Set to true to retain image proportions on resizing. Default true.
*	@param array $options Array of options and Html attributes. See CakePHP HtmlHelper.
*	@param bool $watermark Set to true to include a watermark image referenced by $watermarkImg. Default false.
*	@param bool $return Set to true to return image path, false to return formated img tag. Default false.
*	@return mixed String of image path or formatted `<img />` tags.
*/
	public function resize($path, $width, $height, $aspect = true, $options = array(), $watermark = false, $return = false, $greyscale = false) {

		$types = array(1 => "gif", "jpeg", "png", "swf", "psd", "wbmp"); 

		$file = explode('/', $path);
		$imgfile = end($file);

		$this->cacheFile = $width . 'x' . $height . '_' . $imgfile;
		$this->localFile = $this->cacheDir . DS . $this->cacheFile;
		$this->remoteFile = $this->remoteDir . $path;

		/* copy file to temp directory */
		$this->tempFile = $this->tempDir . $imgfile;
		if (!file_exists($this->tempFile)) {
			$this->copyfile();
		} else {
			if (@filemtime($this->tempFile) < @filemtime($this->remoteFile)) {
				$this->copyfile();
			}
		}

		if (!($size = @getimagesize($this->tempFile))) {
			if($return){
	            return $this->localFile;
	        }else{
	        	//$options = array_merge(array('height' => $height, 'width' => $width), $options);
	            //return $this->Html->image('unknown.png', $options);
	            return;
	        }
		}

		$o_width = $width;
		$o_height = $height;

		if ($aspect) {
			if (($size[1]/$height) > ($size[0]/$width)) {
				$width = round(($size[0]/$size[1]) * $height);
			} else {
				$height = round($width / ($size[0]/$size[1]));
			}
		}

		if (file_exists($this->localDir . $this->localFile)) {
			$cached = true;
		} else {
			$cached = false;
			if ($size[0] < $width || $size[1] < $height) {
            	$width = $size[0];
            	$height = $size[1];
            	$this->cacheFile = $width . 'x' . $height . '_' . $imgfile;
				$this->localFile = $this->cacheDir . DS . $this->cacheFile;
				if (file_exists($this->localDir . $this->localFile)) {
					$cached = true;
				} else {
					$cached = false;
				}
            }
		}
        
        if ($size[0] == $width || $size[1] == $height) {
            $r_file = $path;
            $resize = false;
        }
        
        if (!$cached) {

        	$temp = imagecreatetruecolor($o_width, $o_height);
        	//$transparent = imagecolorallocatealpha($temp, 255, 255, 255, 0);
        	//imagefill($temp, 0, 0, $transparent);
        	$fill = imagecolorallocate($temp, 255, 255, 255);
        	imagefilledrectangle($temp, 0, 0, $o_width, $o_height, $fill);

        	$image = call_user_func('imagecreatefrom'.$types[$size[2]], $this->tempFile);
        	imagealphablending($image, true);

 			$sx = imagesx($image);
    		$sy = imagesy($image);

    		if ($sx > $width || $sy > $height) {
    			if (($sy/$height) > ($sx/$width)) {
					$wm_width = round(($sx/$sy) * $height);
					$wm_height = $height;
				} else {
					$wm_width = $width;
					$wm_height = round($width / ($sx/$sy));
				}
    			imagecopyresampled($temp, $image, ($o_width / 2) - ($wm_width / 2), ($o_height / 2) - ($wm_height / 2), 0, 0, $wm_width, $wm_height, $sx, $sy);
    		} else {
    			imagecopy($temp, $image, ($o_width / 2) - ($sx / 2), ($o_height / 2) - ($sy / 2), 0, 0, $sx, $sy);
    		}
            imagealphablending($temp, true);
/*            
pr($o_width);
pr($o_height);
pr($width);
pr($height);
pr($size);exit;
*/
            if ($watermark) {
            	$watermark = imagecreatefrompng($this->localDir . $this->watermarkImg);
	        	$wmsize = getimagesize($this->localDir . $this->watermarkImg);

            	$sx = imagesx($watermark);
    			$sy = imagesy($watermark);

    			if ($sx > $width || $sy > $height) {
    				if (($sy/$height) > ($sx/$width)) {
						$wm_width = round(($sx/$sy) * $height);
						$wm_height = $height;
					} else {
						$wm_width = $width;
						$wm_height = round($width / ($sx/$sy));
					}
    				imagecopyresampled($temp, $watermark, ($o_width / 2) - ($wm_width / 2), ($o_height / 2) - ($wm_height / 2), 0, 0, $wm_width, $wm_height, $sx, $sy);
    			} else {
    				imagecopy($temp, $watermark, ($o_width / 2) - ($sx / 2), ($o_height / 2) - ($sy / 2), 0, 0, $sx, $sy);
    			}
            	imagealphablending($temp, true);

	        }

			imagesavealpha($temp, true);

			if ($greyscale) {
				imagefilter($temp, IMG_FILTER_GRAYSCALE);
			}

			imagepng($temp, $this->localDir . $this->localFile);
			imagedestroy ($image);
            imagedestroy ($temp);

        }
         
        if($return){
            return $this->localFile;
        }else{
        	if (!array_key_exists('escape', $options)) {
        		$options = array_merge(array('escape' => false), $options);
        	}
        	
            return $this->Html->image($this->cacheDir . '/' . $this->cacheFile, $options);
        }

	}



/**
*	Copies image from remote server to local server.
*
*	@return 
*/
	private function copyfile() {

		$parts = parse_url($this->remoteFile);
		$path_parts = array_map('rawurldecode', explode('/', $parts['path']));
		$remote_file = $parts['scheme'] . '://' . $parts['host'] . implode('/', array_map('rawurlencode', $path_parts));

		$content = @file_get_contents($remote_file);
		$fp = fopen($this->tempFile, 'w');
		fwrite($fp, $content);
		fclose($fp);

	}

}
	
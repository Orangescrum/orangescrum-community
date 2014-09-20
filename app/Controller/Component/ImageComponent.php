<?php

App::import('Vendor', 's3', array('file' => 's3' . DS . 'S3.php'));

class ImageComponent extends Component {

    var $save_to_file = true;
    var $image_type = -1;
    var $quality = 100;
    var $max_x = 0;
    var $max_y = 0;
    var $cut_x = 0;
    var $cut_y = 0;

    function SaveImage($im, $filename) {

	$res = null;

	// ImageGIF is not included into some GD2 releases, so it might not work
	// output png if gifs are not supported
	if (($this->image_type == 1) && !function_exists('imagegif'))
	    $this->image_type = 3;

	switch ($this->image_type) {
	    case 1:
		if ($this->save_to_file) {
		    //$res = ImageGIF($im,$filename);
		    $res = ImageGIF($im, NULL);
		} else {
		    header("Content-type: image/gif");
		    $res = ImageGIF($im);
		}
		break;
	    case 2:
		if ($this->save_to_file) {
		    $res = ImageJPEG($im, NULL, $this->quality);
		} else {
		    header("Content-type: image/jpeg");
		    $res = ImageJPEG($im, NULL, $this->quality);
		}
		break;
	    case 3:
		if (PHP_VERSION >= '5.1.2') {
		    // Convert to PNG quality.
		    // PNG quality: 0 (best quality, bigger file) to 9 (worst quality, smaller file)
		    $quality = 9 - min(round($this->quality / 10), 9);
		    if ($this->save_to_file) {
			//$res = ImagePNG($im, $filename, $quality);
			$res = ImagePNG($im, NULL, $quality);
		    } else {
			header("Content-type: image/png");
			$res = ImagePNG($im, NULL, $quality);
		    }
		} else {
		    if ($this->save_to_file) {
			$res = ImagePNG($im, $filename);
		    } else {
			header("Content-type: image/png");
			$res = ImagePNG($im);
		    }
		}
		break;
	}

	return $res;
    }

    function ImageCreateFromType($type, $filename) {
	$im = null;
	switch ($type) {
	    case 1:
		$im = ImageCreateFromGif($filename);
		break;
	    case 2:
		$im = ImageCreateFromJpeg($filename);
		break;
	    case 3:
		$im = ImageCreateFromPNG($filename);
		break;
	}
	return $im;
    }

    // generate thumb from image and save it
    //function GenerateThumbFile($from_name, $to_name, $max_x, $max_y) {
    function generateTemporaryURL($resource) {
	$bucketname = BUCKET_NAME;
	$awsAccessKey = awsAccessKey;
	$awsSecretKey = awsSecretKey;
	$expires = strtotime('+1 day'); //1.day.from_now.to_i; 
	$s3_key = explode(BUCKET_NAME, $resource);
	$x = $s3_key[1];
	$s3_key[1] = substr($x, 1);
	$string = "GET\n\n\n{$expires}\n/{$bucketname}/{$s3_key[1]}";
	$signature = urlencode(base64_encode((hash_hmac("sha1", utf8_encode($string), $awsSecretKey, TRUE))));
	//echo $expires."=====";echo $signature;
	return "{$resource}?AWSAccessKeyId={$awsAccessKey}&Signature={$signature}&Expires={$expires}";
	//https://s3.amazonaws.com/orangescrum-dev/files/case_files/1.jpg?AWSAccessKeyId=AKIAJAVFGWOGKGBOWPWQ&Signature=gZ90JslqYADtRK6haMVR9e2guko%3D&Expires=1360239119
    }

    function GenerateThumbFile($from_name1, $to_name, $max_x, $max_y, $filename) {
	// if src is URL then download file first
	if (defined('USE_S3') && USE_S3 && $filename != 'user.png') {
	    $from_name = $this->generateTemporaryURL($from_name1); //print_r($from_name);exit;
	} else {
	    $from_name = $from_name1;
	}
	$temp = false;
	if (substr($from_name, 0, 7) == 'https://') {
	    $tmpfname = tempnam("tmp/", "TmP-");
	    $temp = @fopen($tmpfname, "w");
	    if ($temp) {
		@fwrite($temp, @file_get_contents($from_name)) or die("Cannot download image");
		@fclose($temp);
		$from_name = $tmpfname;
	    } else {
		die("Cannot create temp file");
	    }
	}

	// check if file exists
	if (defined('USE_S3') && USE_S3 && $filename != 'user.png') {
	    $s3 = new S3(awsAccessKey, awsSecretKey);
	    $info = $s3->getObjectInfo(BUCKET_NAME, DIR_USER_PHOTOS_S3_FOLDER . $filename); //print_r($info);
	    $file_mime = $info['type'];
	} else if (file_exists($from_name)) {
	    $info = 1;
	} else if (file_exists(DIR_USER_PHOTOS . 'user.png')) {
	    $from_name = DIR_USER_PHOTOS . 'user.png';
	    $info = 1;
	}
	if ($info) {
	    // get source image size (width/height/type)
	    // orig_img_type 1 = GIF, 2 = JPG, 3 = PNG

	    $getimagesize = @getimagesize($from_name);
	    //list($orig_x, $orig_y, $orig_img_type, $img_sizes) = @getimagesize($from_name);
	    $orig_x = $getimagesize[0];
	    $orig_y = $getimagesize[1];
	    $orig_img_type = $getimagesize['2'];
	    if (!$file_mime) {
		$file_mime = $getimagesize['mime'];
	    }

	    // cut image if specified by user
	    if ($this->cut_x > 0)
		$orig_x = min($this->cut_x, $orig_x);
	    if ($this->cut_y > 0)
		$orig_y = min($this->cut_y, $orig_y);
	    // should we override thumb image type?
	    $this->image_type = ($this->image_type != -1 ? $this->image_type : $orig_img_type);

	    // check for allowed image types
	    if ($orig_img_type < 1 or $orig_img_type > 3)
		die("Image type not supported");

	    if ($orig_x > $max_x or $orig_y > $max_y) {
		if (!$file_mime) {
		    $file_mime = @finfo_file(finfo_open(FILEINFO_MIME_TYPE), $from_name);
		}
		if ($file_mime) {
		    header("Content-Type:$file_mime");
		}
		// resize
		$per_x = $orig_x / $max_x;
		$per_y = $orig_y / $max_y;
		if ($per_y > $per_x) {
		    $max_x = $orig_x / $per_y;
		} else {
		    $max_y = $orig_y / $per_x;
		}
	    } else if ($orig_x < $max_x or $orig_y < $max_y) {
		$max_x = $orig_x;
		$max_y = $orig_y;

		if (!$file_mime) {
		    $file_mime = @finfo_file(finfo_open(FILEINFO_MIME_TYPE), $from_name);
		}
		if ($file_mime) {
		    header("Content-Type:$file_mime");
		}
	    } else {
		// keep original sizes, i.e. just copy
		if ($this->save_to_file) {
		    @copy($from_name, $to_name);
		} else {
		    switch ($this->image_type) {
			case 1:
			    header("Content-type: image/gif");
			    readfile($from_name);
			    break;
			case 2:
			    header("Content-type: image/jpeg");
			    readfile($from_name);
			    break;
			case 3:
			    header("Content-type: image/png");
			    readfile($from_name);
			    break;
		    }
		}
		return;
	    }

	    if ($this->image_type == 1) {
		// should use this function for gifs (gifs are palette images)
		$ni = imagecreate($max_x, $max_y);
	    } else {
		// Create a new true color image
		$ni = ImageCreateTrueColor($max_x, $max_y);
	    }

	    // Fill image with white background (255,255,255)

	    $white = imagecolorallocate($ni, 255, 255, 255);

	    /* if($this->image_type == 3)
	      {
	      $white = imagecolorallocate($ni, 0, 0, 0);
	      imagecolortransparent($ni, $white);
	      }
	     */
	    imagefilledrectangle($ni, 0, 0, $max_x, $max_y, $white);
	    // Create a new image from source file
	    $im = $this->ImageCreateFromType($orig_img_type, $from_name);
	    // Copy the palette from one image to another
	    imagepalettecopy($ni, $im);
	    // Copy and resize part of an image with resampling
	    imagecopyresampled(
		    $ni, $im, // destination, source
		    0, 0, 0, 0, // dstX, dstY, srcX, srcY
		    $max_x, $max_y, // dstW, dstH
		    $orig_x, $orig_y);    // srcW, srcH
	    // save thumb file
	    $this->SaveImage($ni, $to_name);

	    if ($temp) {
		unlink($tmpfname); // this removes the file
	    }
	} else {
	    //File doesn't exists
	    echo "Source image does not exist!";
	    exit;
	}
    }

}

?>
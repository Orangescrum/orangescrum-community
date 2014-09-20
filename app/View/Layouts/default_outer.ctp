<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="X-Frame-Options" content="deny">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
<link rel="shortcut icon" href="<?php echo HTTP_ROOT; ?>favicon.ico"/>
<meta name="robots" content="noindex,nofollow" />
<title>Orangescrum</title>
<?php 
echo $this->Html->meta('icon'); 
echo $this->Html->css('style_outer.css?v='.RELEASE);
?>
<script type="text/javascript">
    var PROTOCOL = '<?php echo PROTOCOL;?>';
    var DOMAIN = '<?php echo DOMAIN;?>';
    var HTTP_APP = "<?php echo HTTP_APP; ?>";
    var DOMAIN_COOKIE = "<?php echo DOMAIN_COOKIE; ?>";
    
    //For google login and signup start
    var CLIENT_ID = "<?php echo CLIENT_ID; ?>";
    var CLIENT_ID_SIGNUP = "<?php echo CLIENT_ID_SIGNUP; ?>";
    var REDIRECT = "<?php echo REDIRECT_URI; ?>";
    var REDIRECT_SIGNUP = "<?php echo REDIRECT_URI_SIGNUP; ?>";
    //For google login and signup end
</script>  
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH; ?>index/common_outer.js"></script>

</head>
<?php flush(); ?>
<body class="head_back" id="headbody">
	<div id="cover" class="outer" style="filter:alpha(opacity=50);"></div>
	<?php echo $content_for_layout; ?>
</body>
</html>

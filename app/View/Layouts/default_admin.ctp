<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
header("Cache-Control: no-store, no-cache, must-revalidate"); 
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>
<!doctype html>
<!--[if IE 7 ]>    <html lang="en" class="no-js ie7"> <![endif]-->
<!--[if IE 8 ]>    <html lang="en" class="no-js ie8"> <![endif]-->
<!--[if IE 9 ]>    <html lang="en" class="no-js ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html lang="en" class="no-js"> <!--<![endif]-->
<head>
<meta charset="UTF-8">
<meta http-equiv="X-Frame-Options" content="deny">
<?php echo $this->element('metadata'); ?>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php 

echo $this->Html->meta('icon');

echo $this->Html->css('style_admin.css?v='.RELEASE);
echo $this->Html->css('colors_admin.css?v='.RELEASE);
echo $this->Html->css('jquery.fileinput');
echo $this->Html->css('stylesheet_admin.css?v='.RELEASE);
echo $this->Html->css('jquery-ui');
echo $this->Html->css('google-font');
echo $this->Html->css('chromatron-red_admin.css?v='.RELEASE);
echo $this->Html->css('multiautocomplete');
echo $this->Html->css('osadmin');
?>
<!--script type="text/javascript" src="<?php echo JS_PATH; ?>jquery/jquery.min.1.5.1.js"></script--> 
<script type="text/javascript" src="<?php echo JS_PATH; ?>jquery-1.7.2.min.js"></script> 
</head>
<body id="easycase">
	<?php echo $this->element('admin_header_inner'); ?>
	<div style="min-height:350px"><?php echo $content_for_layout; ?></div>
	<?php echo $this->element('admin_footer_inner');?>
</body>
</html>

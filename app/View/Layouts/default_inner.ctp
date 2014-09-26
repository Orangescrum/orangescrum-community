<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-Frame-Options" content="deny">
<title>Orangescrum</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="noindex,nofollow" />
<?php 
echo $this->Html->meta('icon');
echo $this->Html->css('bootstrap.min_1');
echo $this->Html->css('style_inner.css?v='.RELEASE);
echo $this->Html->css('jquery-ui');

if(PAGE_NAME == "mydashboard" || PAGE_NAME == "dashboard" || PAGE_NAME=='milestonelist') {
	echo $this->Html->css('jquery.jscrollpane');
}

if(PAGE_NAME == "profile") {
	echo $this->Html->css('img_crop/imgareaselect-animated.css');
}

echo $this->Html->css('fcbkcomplete');

echo $this->Html->css('pace-theme-minimal');
echo $this->Html->css('prettyPhoto.css');

//Moved from Create New project ajax request page
echo $this->Html->css('wick_new.css?v='.RELEASE);

if(PAGE_NAME == "help" || PAGE_NAME=='tour') {
	echo $this->Html->css('help');
}

if(!defined('USE_LOCAL') || (defined('USE_LOCAL') && USE_LOCAL==0)) {
	$js_arr = array('//code.jquery.com/jquery-1.10.1.min.js', '//code.jquery.com/jquery-migrate-1.2.1.min.js');
	echo $this->Html->script($js_arr);
}
?>
<!--[if lte IE 9]>
    <style>
        body {font-family: 'Arial';}
        .col-lg-3 .btn.gry_btn.smal30{padding-left:15px;}
        .task_ie_width {width:4%;}
    </style>
<![endif]-->
<!--[if lte IE 8]>
   <link href="<?php echo CSS_PATH; ?>ie_lte_8.css" rel="stylesheet">
<![endif]-->
<!--[if lte IE 7]>
   <style>
   	.top_nav2{margin-top:0px;}
    .filters ul li.filter_cb{width:0px; height:0px; margin:0px;}
    .drp_flt{display:inline-block; float:none;}
    .navbar-form.navbar-left.top_search{padding:0px;}
   </style>
<![endif]-->

<script type="text/javascript">
  if (typeof jQuery == 'undefined') {
	 document.write(unescape("%3Cscript src='<?php echo JS_PATH; ?>jquery-1.10.1.min.js' type='text/javascript'%3E%3C/script%3E"));
	 document.write(unescape("%3Cscript src='<?php echo JS_PATH; ?>jquery-migrate-1.2.1.min.js' type='text/javascript'%3E%3C/script%3E"));
  }
</script>

<?php
	//Bootstrap core JavaScript
	$js_files = array( 'bootstrap.min.js', 'modernizer.js');
	echo $this->Html->script($js_files);
?>
</head>
<body>
	<?php
	$styleClass = "";
	if(PAGE_NAME == 'help' || PAGE_NAME=='tour') {
		$styleClass = 'style="padding-left:0px;"';
	}
	?>
	<div id="wrapper" <?php echo @$styleClass; ?>>
	<?php
	echo $this->element('header_inner');
	if(PAGE_NAME=='tour') {
		echo $this->element('help_tabbs');
	}
	echo $this->fetch('content');?>
	</div>
	<?php
	echo $this->element('footer_inner');
	
	echo $this->Html->script('pace.min.js');
	?>
	<script>
    paceOptions = {
      elements: true
    };
	$(document).ajaxStart(function(){
	  Pace.restart();
	});
	$(document).ajaxStop(function(){
	  Pace.stop();
	});
    </script>
	<?php
	
	if(GA_CODE == 1){?>
	<!-- GA CODE -->
    <script type="text/javascript">
	  var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', 'UA-24950841-1']);
	  //_gaq.push(['_trackPageview']);
	
	  (function() {
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();
	</script>
	<?php
	}
	?>
</body>
</html>

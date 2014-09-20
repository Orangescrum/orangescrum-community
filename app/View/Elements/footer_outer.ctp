<style type="text/css">
	.outer{background:#333}
	.bg_digit{
	background:url(/img/images/ae27-45.jpg) no-repeat ;
	width:27px;
	height:48px;
	float:left;
	margin-right:3px;
	font-family:digital-7;
	line-height:51px;
	font-size:42px;
	text-align:center;
	color:#000;
}
.ft_outer2{text-align:center;position: relative;}
.ft_outer2 .ft_top_hd{font-size: 17px; margin-top:2px;}
.ft_outer2 img{margin-top:20px;}
</style>

<?php
if(PAGE_NAME == "home") { ?>
<div class="twt_content">
    
    <!-- Start of freebie -->
    <div id="freebie">
		<div class="case_cnt"><div id="totalcase" align="center"> </div></div>
        <div style="clear:both;"></div>
		<h4 style="font-family:MyriadPro-Regular; font-size:28px;color:#333333; padding-bottom:5px;" >activities and counting</h4>
        <div style="clear:both"></div>
    </div>
    <!-- End of freebie -->
    
</div>
<?php } ?>
<div class="footer_content">
    <div class="footer_inner">
        <div class="fl ft_btm_ul">
			<ul class="fl wd_200">
				<li><a href="<?php echo HTTPS_HOME;?>">Home</a></li>
				<li><a href="<?php echo HTTPS_HOME; ?>pricing<?php echo $ablink; ?>">Pricing</a></li>
				<li><a href="<?php echo HTTPS_HOME; ?>how-it-works<?php echo $ablink; ?>">How it Works</a></li>
				<li><a href="<?php echo HTTPS_HOME; ?>help<?php echo $ablink; ?>">Support</a></li>
				<li><a href="<?php echo HTTPS_HOME; ?>aboutus<?php echo $ablink; ?>">About Us</a></li>
			</ul>
			<ul class="fl wd_200">
            	<li><a href="<?php echo HTTPS_HOME; ?>affiliates<?php echo $ablink; ?>">Affiliate Program</a></li>
				<li><a target="_blank" href="http://blog.orangescrum.com">Blog</a></li>
				<li><a href="<?php echo HTTPS_HOME; ?>securities<?php echo $ablink; ?>">Security</a></li>
                <li><a href="<?php echo HTTPS_HOME; ?>termsofservice<?php echo $ablink; ?>">Terms</a></li>
				<li><a href="<?php echo HTTPS_HOME; ?>privacypolicy<?php echo $ablink; ?>">Privacy</a></li>
			</ul>
			<div class="cb"></div>
		</div>
        <div class="fl ft_btm_div">
            <div class="ft_top_hd">Android &amp; iOS apps coming soon!</div>
            <div class="and_os">
            <img class="lazy" data-src="<?php echo HTTP_IMAGES;?>os_ipad_android.png?v=1" src="<?php echo HTTP_ROOT; ?>img/loading.gif?v=<?php echo RELEASE; ?>" />
            </div>
       </div>
       <div class="cb"></div>
     </div>
    </div>
</div>

<div class="footer_btm" style="height:auto;text-align:center;background:#222222">
	<span class="pink">&copy; <?php echo gmdate('Y');?> Orangescrum. <a href="http://www.andolasoft.com/" target="_blank">Andolasoft</a>.</span>
</div>

<div id="inner_success" class="inner" style="top:10px;left:30%;margin:auto;">
	<table cellspacing="0" cellpadding="0" class="success_home_feed" align="center" style="margin-top:40px;width:550px;">
		<tr style="height:35px;">
			<td valign="middle" class="succ_feed_home_td" align="center">
				<span id="successmsg" class="topalerts success_home_feed"></span>
			</td>
		</tr>
	</table>
</div>

<?php if(stristr($_SERVER['SERVER_NAME'],"orangescrum.com") && !$this->Session->read('Auth.User.id')){ ?>
<style>    
#feedback_outer{
    left:auto;
    right:190px !important;
}
</style>
<?php } ?>

<script type="text/javascript" src="<?php echo JS_PATH; ?>index/jquery.jcarousel.min.js"></script>
<script type="text/javascript" src="<?php echo HTTP_ROOT; ?>js/jquery.lazy.min.js"></script>
	<script type="text/javascript">
	$(document).ready(function(){
		$("img.lazy").lazy();
		$(".ms_cls img").hover(function(){
			$(this).attr({src:"<?php echo HTTP_IMAGES;?>images/close_hover.png"})
		},function(){
			$(this).attr({src:"<?php echo HTTP_IMAGES;?>images/popup_close.png"});
		});	
	});
	
	function getstarted_ga(type){
		  <?php if(stristr($_SERVER['SERVER_NAME'],"orangescrum.com")){?>
				console.log('Start OrangScrum Free Trial'+type);
				_gaq.push(['_trackEvent', 'Signup', 'Start', 'Start OrangScrum Free Trial'+type]);
		  <?php } ?>	
		return true;
	}
	function ga_tracking_google_signup(type){
		  <?php if(stristr($_SERVER['SERVER_NAME'],"orangescrum.com")){?>
				console.log('Start OrangScrum Free Trial'+type);
				_gaq.push(['_trackEvent', 'Signup', 'Google', type]);
		  <?php } ?>	
		return true;
	}
	function ga_tracking_video(type){
		  <?php if(stristr($_SERVER['SERVER_NAME'],"orangescrum.com")){?>
				console.log(type);
				_gaq.push(['_trackEvent', 'Video', type]);
		  <?php } ?>	
		return true;
	}
	
	function closetip(){
		$("#showmydata").slideUp(800);
		//$("#help_img").delay(700).slideDown(800);
	}

	$("#close_showmydata").click(function(){
		//clearTimeout (TimerId );		
		$("#showmydata").slideUp(800);
		//$("#help_img").delay(700).slideDown(800);
	});

	$("#help_img").click(function(e){
		if($("#showmydata").is(":visible")){
			$("#showmydata").slideUp(800);
		}
		else{
		$("#showmydata").slideDown(800);
		}

		//$("#help_img").slideUp(800);
		e.stopPropagation();
	});

	$("#showmydata").click(function(e){
		e.stopPropagation();
	});

	$(document).click(function(){
		$("#showmydata").slideUp(800);
		//$("#help_img").delay(700).slideDown(800);
		
	});
	$(document).keydown(function(e) {
		if(e.keyCode == 27) {
			$("#cover").fadeOut('fast');
			$("#inner_support").slideUp('fast');
			$("#inner_success").slideUp('fast');
			$(".cover").fadeOut('fast');
		}
	});
	</script>
<script type="text/javascript">
$(document).ready(function() 
{ 
	var pageurl = document.getElementById("pageurl").value;
	var url =pageurl+"users/ajax_totalcase";
		$.post(url,function(data){
			  if(data) {
				$('#totalcase').html(data);
			  }
		});
});

function openDetails(id) {
	if($('#'+id).is(':visible')) {
		$("#"+id).slideUp();
	}
	else {
		$("#"+id).slideDown();
	}
}
</script>
<script type="text/javascript">
$(document).ready(function() {
$("#tour_btm").click(function(){
	$( 'html, body' ).animate( { scrollTop: 1900 }, 1000 );
});
});
function signupheader_ga(){
		console.log('Signup Header Button');
	  <?php if(stristr($_SERVER['SERVER_NAME'],"orangescrum.com")){?>
			
			_gaq.push(['_trackEvent', 'Signup', 'SignupHeader', 'Signup Header Button']);
	  <?php } ?>	
	return true;
}
</script>
<?php
if(isset($_GET['affiliate_key']) && $_GET['affiliate_key'] && $_SERVER['REMOTE_ADDR']){

	$ref_url = "";
	$refHost = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
	if($refHost && !stristr($refHost,"orangescrum.com")) {
		$ref_url = $refHost;
	}
	?>
	<script id="affiliateScript" data-key="<?php echo urldecode($_GET['affiliate_key']); ?>" ref-url="<?php echo urlencode($ref_url); ?>" type="text/javascript" src="https://www.sukinda.com/js/affiliate.js?ip=<?php echo $_SERVER['REMOTE_ADDR']; ?>"></script>
<?php
}
?>

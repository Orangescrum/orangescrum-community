<style>
.what_get_mc{width:1020px;margin:20px auto 0;}
.what_feature{width:310px;text-align:center;font-family:Muli-regular;font-size:14px;margin:0px 20px 20px;color:#5C5C5C;}
.what_feature h4{font-size:18px;color:#333;margin:15px 0;font-weight:bold}

</style>
<div>
	<div class="what_feature fl wt_fl_320">
	    <img  src="<?php echo HTTP_ROOT; ?>img/ftr_1.png?v=<?php echo RELEASE; ?>" alt="" width="66px" height="62px" /><br/>
	<h4>Easy Import &amp; Export</h4>
	Import your data from any other application.<br/>Leave whenever you want with just a simple click to download all your data.
	</div>
	
	<div class="what_feature fl wt_fl_320">
	<img  src="<?php echo HTTP_ROOT; ?>img/ftr_2.png?v=<?php echo RELEASE; ?>" alt="" width="68px" height="66px" /><br/>
	<h4>Fast &amp; Secure </h4>
	We believe in faster response time.<br/> With our Advanced SSL, we provide communication security over the Internet.
	</div>
	
	<div class="what_feature fl wt_fl_320">
	<a href="http://blog.orangescrum.com/2014/01/announcing-open-source-warranty-on-orangescrum.html" target="_blank" onclick="opensource_ga()" style="color:#333333;border:none;outline:none"><img  src="<?php echo HTTP_ROOT; ?>img/ftr_3.png?v=<?php echo RELEASE; ?>" alt="" width="43px" height="66px" /></a><br/>
	<a href="http://blog.orangescrum.com/2014/01/announcing-open-source-warranty-on-orangescrum.html" target="_blank" onclick="opensource_ga()" style="color:#333333"><h4>Open Source Warranty</h4></a>
	There will be no nasty surprises.<br/>In case of any ceased activity, Orangescrum will be released as open-source for our customers.
	</div>
</div>
<div class="cb"></div>
<script>
function opensource_ga(){
		console.log('Opensource Warranty');
	  <?php if(stristr($_SERVER['SERVER_NAME'],"orangescrum.com")){?>
			
			_gaq.push(['_trackEvent', 'Opensource', 'Warranty', 'Opensource Warranty']);
	  <?php } ?>	
	return true;
}
</script>
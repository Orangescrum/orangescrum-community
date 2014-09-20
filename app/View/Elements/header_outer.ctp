<div class="wrapper_login">
<input type="hidden" name="pageurl" id="pageurl" value="<?php echo HTTP_ROOT; ?>" size="1" readonly="true"/>
<input type="hidden" name="pageurlhome" id="pageurlhome" value="<?php echo HTTP_HOME; ?>" size="1" readonly="true"/>

<div id="topmostdiv" style="display:block; position:fixed;top: 15%; width:100%; text-align:center;z-index: 2147483647; position:fixed">
	<?php 
	if($success){
	?>
		<div onClick="removeMsg();" id="upperDiv" align="center" style="margin:0px auto;position:relative; text-align:center;margin-top:10px;">
			<span style="position:relative;">
				<span class="topalerts success_flash msg_span" style="font-family:MyriadProSemibold">
					<?php echo $success; ?>
				</span>
			</span>	
		</div>
		<script>setTimeout('removeMsg()',6000);</script>
	<?php
	} 
	elseif($error){
          if(stristr($error,'Object(CakeResponse)')){

          }else{
	?>
		<div onClick="removeMsg();" id="upperDiv" align="center" style="margin:0px auto;position:relative; text-align:center;margin-top:10px;">
			<span style="position:relative;">
				<span class="topalerts error_flash msg_span" style="font-family:MyriadProSemibold">
					<?php echo $error; ?>
				</span>
			</span>	
		</div>
		<script>setTimeout('removeMsg()',6000);</script>
	<?php
	} }
	else{
	?>
		<div onClick="removeMsg();" id="upperDiv" align="center" style="display:none; margin:0px auto;position:relative; text-align:center;margin-top:10px;">
			<span style="position:relative;">
				<span class="topalerts success_flash msg_span" style="font-family:MyriadProSemibold">
					<?php echo $success; ?>
				</span>
			</span>	
		</div>
	<?php
	}
	?>
</div>

<!--<a class="fdbk_tab_right" id="fdbk_tab" href="#contactForm" onClick="randomNum();cover_open('cover','inner_feedback');">FEEDBACK</a>-->
<span class="preload"></span>
<div id="beta"></div>
<?php if(PAGE_NAME == "registration"){ ?><div class="top_menu_land" style="display:none"> <?php } else { ?> <div class="top_menu_land"> <?php } ?> 
	<div id="wrapper">
    	<div class="fl logo_landing"> 
        	<?php 
			$added_url = ""; $image_path = "";
			if(PAGE_NAME == "pricing" || PAGE_NAME == "tour" || PAGE_NAME == "signup") {
				//$added_url = PAGE_NAME;
			}
			if($abtest) {
				$image_path = "_".$abtest;
			}
			?>
			<span class="b_lg"><a href="<?php echo HTTPS_HOME.$added_url; ?>"><img src="<?php echo HTTP_ROOT; ?>img/home<?php echo $image_path; ?>/logo_outer_home.png"  width="208px" height="43px" border="0" alt="Orangescrum.com" title="Orangescrum.com"/></a></span>
			<div class="sb_ttl" style="display:none">Each task matters</div>
        </div>
	  <?php if(PAGE_NAME != "signup") { ?>  
        <div class="fr top_land_menu">
        	<ul>
				<!--<li <?php //if(PAGE_NAME == "home") { ?>class="active"<?php //} ?>><a href="<?php //echo HTTP_HOME; ?>" class="active"></a></li>-->
				<li <?php if(PAGE_NAME == "signup") { ?>class="active"<?php } ?> style="padding:8px 0px;"><a href="<?php echo HTTPS_HOME; ?>signup/free<?php echo $ablink; ?>" style="border:none;border-radius:5px;padding:4px 12px 6px;margin:0">Start Free!</a></li>
				<li <?php if(PAGE_NAME == "tour") { ?>class="active"<?php } ?>><a href="<?php echo HTTPS_HOME; ?>how-it-works<?php echo $ablink; ?>">How it Works</a></li>
				<li <?php if(PAGE_NAME == "request_demo") { ?>class="active"<?php } ?>><a href="<?php echo HTTPS_HOME; ?>demo<?php echo $ablink; ?>">Demo</a></li>
				<li ><a href="http://blog.orangescrum.com/" target="_blank">Blog</a></li>
				

				<li><a href="<?php echo HTTP_APP; ?>users/login">Login</a></li>
				
				<!--<li class="signup_hm_ie8" <?php //if(PAGE_NAME == "signup") { ?>class="active"<?php //} ?> ><a href="<?php //echo PROTOCOL."www.".DOMAIN; ?>signup" style="border:none;">Sign Up</a></li>-->
            </ul>
        </div>
	  <?php } ?>  
        <div class="cb"></div>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function() {
	$("#feature_btn").click(function(event) {
		var scrl_pos=$("#fet").offset().top;
		$('html,body').animate({ scrollTop: scrl_pos }, 1000);
	});
	$("#tour_scroll").click(function(){
		$( 'html, body' ).animate( { scrollTop: 1900 }, 1000 );
	});
	/*$(window).scroll(function() {
		if ( $(window).scrollTop() >67 ){
			$(".top_menu_land").css({height:"53px"});
			$(".s_lg").css({display:"block"});	
			$(".b_lg").css({display:"none"});			
			$(".sb_ttl").css({"font-size":"11px"});			
		} 
		
		else{
			$(".top_menu_land").css({height:"67px"});
			$(".s_lg").css({display:"none"});	
			$(".b_lg").css({display:"block"});			
			$(".sb_ttl").css({"font-size":"12px"});	
		}
		});*/
});
</script>

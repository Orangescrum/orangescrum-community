<?php 
if(!$this->Session->read('Auth.User.id')){
	$family1 = "Muli-regular";
	$family2 = "RobotoCondensed-Regular";
	$family3 = "myriadpro-regular";
}
else {
	$family1 = "'HelveticaNeue-Roman','HelveticaNeue','Helvetica Neue','Helvetica','Arial',sans-serif";
	$family2 = "'HelveticaNeue-Roman','HelveticaNeue','Helvetica Neue','Helvetica','Arial',sans-serif";
	$family3 = "'HelveticaNeue-Roman','HelveticaNeue','Helvetica Neue','Helvetica','Arial',sans-serif";
}
?>
<style>
.faq_qno{color:#40474F;font-size: 19px;font-weight: bold;padding:3px 0px;margin-bottom: 2px;margin-right:4px;}
.container{ font-family:<?php echo $family2; ?>;}
.big_hd_1{ font-size:44px;color:#555;text-align:center;/*padding-top:37px;*/font-family:myriadpro-regular}
.big_hd_2{ font-size:44px;color:#555;text-align:center;/*padding-top:40px;*/font-family:myriadpro-regular}
h1.big_hd_2,h1.big_hd_1{border-bottom:1px solid #E7E7E7;color:#333333;font-size:36px;height:55px;text-align:left; font-weight:normal;font-family:<?php echo $family2; ?>;}
.big_hd{ font-size:38px; color:#424D58; text-align:center;padding-top:80px;font-family:MyriadProSemibold}
.sml_hd{ color:#1e6ea1;font-size:17px; text-align:left; line-height:28px; margin-top:0px;font-family:<?php echo $family3; ?>; font-weight:bold;}
.sml_hd_1{ font-size:16px; color:#4B4B4B; text-align:center; line-height:28px; margin-top:10px;font-family:<?php echo $family1; ?>;}
.faq_ans{text-align:justify; line-height:22px;}
#pricing_div{margin:0px auto;}
.price_main_div{padding-top:15px;}
.mglt50{margin-left:50px;}
</style>
<!--[if IE 8]>
    <style>
        .big_hd_1{padding-top:90px;}
    </style>	
<![endif]-->
<h1  <?php if(!$this->Session->read('Auth.User.id')){ ?>class="big_hd_2"<?php }else{ ?>class="big_hd_1"<?php } ?>>Frequently Asked Questions</h1>
			<!--<div class="sml_hd" style="margin:0px auto;width:585px; text-align:center" align="center">You may have questions, we've tried to address them.<br />If you still have questions, please write to us at <a href='mailto:support@orangescrum.com' style="color:#066D99">support@orangescrum.com</a></div>
			<div style="border-top:3px solid #fff; border-bottom:1px solid #dddddd;width:100%;position:relative"></div>-->
			<div class="container faq_480" style="margin-top:25px;">
				<div class="fl">
					<div class="fl faq_con">
						<!--<div class="fl lft_faq"></div>-->
						<div class="fl" style="padding:5px;">	
							<div class="faq_blk">
								<div class="fl" style="position:relative;">
									<!--<div class="faq_qno fl">Q1.</div>-->
									<div class="sml_hd faq_qn fl">Can I avail a Free trial Plan with all features?</div>
								</div>
								<!--<div class="fl qn_bord" style="border-bottom:1px solid #e7e7e7; width:100%; position:relative; top:-12px; z-index:0"></div>-->
								
								<div class="cb"></div>
								<div class="sml_hd_1 faq_ans">
									The paid subscription entitles you FREE access for 30 days from the date of your subscription. Here, you get complete access to all the features.
								</div>
							</div>
							<div class="cb20"></div>
							<div class="faq_blk">
								<div class="fl" style="position:relative;">
									<!--<div class="faq_qno fl">Q2.</div>-->
									<div class="sml_hd faq_qn fl">I'm not sure which plan best suits me.</div>
								</div>
								<!--<div class="fl qn_bord" style="border-bottom:1px solid #e7e7e7; width:100%; position:relative; top:-12px; z-index:0"></div>-->
								
								<div class="cb"></div>
								<div class="sml_hd_1 faq_ans">
									You can start with the plan that suits your needs today, and upgrade to higher plan any time. You will be charged a pro-rated amount based on the remaining time in the current billing cycle.
								</div>
							</div>
							<div class="cb20"></div>
							<div class="faq_blk">
								<div class="fl" style="position:relative;">
									<!--<div class="faq_qno fl">Q3.</div>-->
									<div class="sml_hd faq_qn fl">Can I downgrade or cancel?</div>

								</div>
								<!--<div class="fl qn_bord" style="border-bottom:1px solid #e7e7e7; width:100%; position:relative; top:-12px; z-index:0"></div>-->
								
								<div class="cb"></div>
								<div class="sml_hd_1 faq_ans">
									You can downgrade to a lower paid plan at any time.
Also you can cancel at any time. If you are cancelling in between the billing period, your card will be charged for that entire month, however your account will be canceled with immediate effect.

								</div>
							</div>
							<div class="cb20"></div>
							<div class="faq_blk">
								<div class="fl" style="position:relative;">
									<!--<div class="faq_qno fl">Q4.</div>-->
									<div class="sml_hd faq_qn fl">What if I have a huge team and expecting unlimited activities?</div>

								</div>
								<!--<div class="fl qn_bord" style="border-bottom:1px solid #e7e7e7; width:100%; position:relative; top:-12px; z-index:0"></div>-->
								
								<div class="cb"></div>
								<div class="sml_hd_1 faq_ans">
									No problems, <a href="mailto:support@orangescrum.com?subject=What if I have a huge team and expecting unlimited activities?" style="text-decoration: none;color: #1e6ea1;">Contact us</a> to avail customized pricing.

								</div>
							</div>
							<div class="cb20"></div>
							<div class="faq_blk">
								<div class="fl" style="position:relative;">
									<div class="sml_hd faq_qn long_qn fl">Apart from online payment, is there any other way of making payment ?</div>
								</div>
								<div class="cb"></div>
								<div class="sml_hd_1 faq_ans">
									Yes. The other modes of payment is Wire Transfer. 
									This mode is accepted only for the yearly subscription.
									<a href="mailto:support@orangescrum.com" style="text-decoration: none;color: #1e6ea1;">Contact us</a> for yearly subscription
								</div>
							</div>
							<div class="cb20"></div>
							<div class="faq_blk">
								<div class="fl" style="position:relative;">
									<div class="sml_hd faq_qn fl">Can I get a Custom Plan which best suits my business?</div>
								</div>	
								<div class="cb"></div>
								<div class="sml_hd_1 faq_ans">
									Yes. <a href="mailto:support@orangescrum.com?subject=Can I get a Custom Plan which best suits my business?" style="text-decoration: none;color: #1e6ea1;">Contact us</a> to get custom plan for your account.
								</div>
							</div>
							<div class="cb20"></div>
						</diV>	
					</div>
				</div>
				<div class="fl mglt50">	
					<div class="fl faq_con faq_con_rht_bx_shd">
						<div class="fl" style="padding:5px;">	
							<div class="faq_blk">
								<div class="fl" style="position:relative;">
									<!--<div class="faq_qno fl">Q7.</div>-->
									<div class="sml_hd faq_qn fl">When will I be charged for the paid plan?</div>
								</div>
								<!--<div class="fl qn_bord" style="border-bottom:1px solid #e7e7e7; width:100%; position:relative; top:-12px; z-index:0"></div>-->
								
								<div class="cb"></div>
								<div class="sml_hd_1 faq_ans">
									You'll be billed after your 30 days use at the end of your 30 days free trial. That means, your first payment will be after 60 days from the date of sign up.<br />Refund policy does not apply, as you will be paying at the end of the month of your usage.
								</div>
							</div>
							<div class="cb20"></div>
							<div class="faq_blk">
								<div class="fl" style="position:relative;">
									<!--<div class="faq_qno fl">Q8.</div>-->
									<div class="sml_hd faq_qn fl">What payment modes do you accept?</div>

								</div>
								<!--<div class="fl qn_bord" style="border-bottom:1px solid #e7e7e7; width:100%; position:relative; top:-12px; z-index:0"></div>-->
								
								<div class="cb"></div>
								<div class="sml_hd_1 faq_ans">
									We accept payments through all major Credit Card services.

								</div>
							</div>
							<div class="cb20"></div>
							<div class="faq_blk">
								<div class="fl" style="position:relative;">
									<!--<div class="faq_qno fl">Q9.</div>-->
									<div class="sml_hd faq_qn long_qn fl" style="text-align: left;">Is there any option to have Orangescrum on my premises?</div>

								</div>
								<!--<div class="fl qn_bord" style="border-bottom:1px solid #e7e7e7; width:100%; position:relative; top:-12px; z-index:0"></div>-->
								
								<div class="cb"></div>
								<div class="sml_hd_1 faq_ans">
									Of course, we can install Orangescrum in your environment with a customized price.<br /><a href="mailto:support@orangescrum.com?subject=Is there any option to have Orangescrum on my premises?" style="text-decoration: none;color: #1e6ea1;">Contact us</a>  for on-premise solutions.   

								</div>
							</div>
							<div class="cb20"></div>
							<div class="faq_blk">
								<div class="fl" style="position:relative;">
									<!--<div class="faq_qno fl">Q10.</div>-->
									<div class="sml_hd faq_qn long_qn fl">Is my data secured?</div>

								</div>
								<!--<div class="fl qn_bord" style="border-bottom:1px solid #e7e7e7; width:100%; position:relative; top:-12px; z-index:0"></div>-->
								
								<div class="cb"></div>
								<div class="sml_hd_1 faq_ans">
									The security of your information is extremely important to us. We do not save any credit card number in our database, neither share your email ids to third parties. All the sensitive information are encrypted using secure socket layer technology (SSL).

								</div>
							</div>
							<div class="cb20"></div>
							<div class="faq_blk">
								<div class="fl" style="position:relative;">
									<div class="sml_hd faq_qn fl" id="creditcard">Why do you need my credit card for a free trial?</div>
								</div>	
								<div class="cb"></div>
								<div class="sml_hd_1 faq_ans">
									We ask for your credit card to allow your membership to continue after your free trial. This also allows us to reduce fraud and prevent multiple free trials for one person. This helps us deliver better service for all the honest customers. 
									Remember that we won't bill you anything during your free trial and that you can cancel at any moment before your trial ends.
								</div>
							</div>
							<div class="cb20"></div>
						</div>	
						<!--<div class="fl rht_faq"></div>-->
						<div class="cb"></div>
					</div>
					<div class="cb"></div>
				</div>	
			</div>
<?php ?>
<style type="text/css">
    .demo_lunch_l.btn_try_top_land.try_btm{width: 185px;font-size:23px;margin-top:0px;}
    .demo_lunch_l.btn_try_top_land.try_btm:hover{box-shadow: 0 0 8px #FF9600;-moz-box-shadow: 0 0 8px #FF9600;-webkit-box-shadow: 0 0 8px #FF9600;}
    .mem_typ{position:relative;padding:10px;font-size:14px;border:1px solid #ccc;width:95%}
    .mem_typ h5{color:#555555;font-family:myriadpro-regular;font-size:16px;margin:46px 52px 24px 74px;}
    .mem_typ.logo_landing{padding:31px 0px 32px;margin-left:3%; width:95%;}
    .hapy_cust{color: #666666;font-family: Muli-italics;font-size: 16px;font-style: italic;line-height: 24px;}

    .success_msg{line-height:30px;padding:6px 15px 6px 35px;border:solid 1px #17BB00;background-color:#FDFDFD;-moz-border-radius:5px;border-radius:5px;color:#108200;font-size:13px;text-align:left;background:url("<?php echo HTTP_ROOT; ?>img/images/success.gif") #E5FEE2;background-position:5px 10px;background-repeat:no-repeat;cursor:pointer;z-index:9999;/*display:none;*/}
    .error_msg{line-height:30px;padding:6px 15px 6px 35px;border:solid 1px #FF0000;background-color:#B64926;-moz-border-radius:5px;border-radius:5px;color:#822A00;font-size:13px;text-align:left;background:url("<?php echo HTTP_ROOT; ?>img/images/wrong.png") #FEEBE2;background-position:5px center;background-repeat:no-repeat;cursor:pointer;z-index:9999;/*display:none;*/}
    .big_hd{ font-size:38px; color:#424D58; text-align:center;padding-top:20px;font-family:MyriadProSemibold}
    h3.reg {padding-bottom:5px;font-size:27px;color:#555555;text-align:left;font-weight: normal;font-family:RobotoCondensed-Regular}
    /*#login_dialog input[type="text"].domainname:focus{border-color:#66afe9;outline:0;-webkit-box-shadow:inset 0 1px 1px rgba(0,0,0,.075), 0 0 8px rgba(102, 175, 233, 0.6);box-shadow:inset 0 1px 1px rgba(0,0,0,.075), 0 0 8px rgba(102, 175, 233, 0.6);}*/
    .join_txt {position:relative;left:8%;margin-left:0;}
    .login_box{font-family:myriadpro-regular;font-size:12px;margin:0px auto 25px;padding:1px 0 10px;text-align:left;width:1100px;}
    .top_m_cont_land {
        /*background: url("/img/images/top_mc_bg_land.png") repeat-x scroll center top transparent;*/
        height: auto;
    }
    .sign_label{ color: #495766;
                 font-family:myriadpro-regular;
                 font-size: 20px;}
    input#company, .seo_url,input.domainname,input#email,input#skype,input#name,input#password,input#confirm_password,input#card_number,input#name_oncard,input#expiry_month,input#expiry_year,input#card_cvc{
        font-size:20px;
        color:#636363;
        border:1px solid #bfbfbf;
        font-family: 'MyriadPro-Regular';
        outline:none;
        background:#fff;
        width:450px;
        height:inherit;
        padding:10px;
        margin:2px 1px 10px;
        float:left;
    }
    input#company:focus, .seo_url:focus,input#email:focus,input#name:focus,input#skype:focus,input#password:focus,input#confirm_password:focus,input#card_number:focus,input#name_oncard:focus,input#expiry_month:focus,#expiry_year:focus,input#card_cvc:focus{border-color:#66afe9 !important;outline:0;-webkit-box-shadow:inset 0 1px 1px rgba(0,0,0,.075), 0 0 8px rgba(102, 175, 233, 0.6);box-shadow:inset 0 1px 1px rgba(0,0,0,.075), 0 0 8px rgba(102, 175, 233, 0.6);}
    input.domainname{}
    .seo_url{border-radius:0}
    .login_dialog {
        padding-left: 30px;
        padding-right: 15px;
        margin-top:10px;
    }
    .gap10{clear:both}
    .suc_img{height:16px; width:16px; float:right; margin:-17px 0 0 -14px;position:relative}
    .exp_block{font-size: 20px;padding: 10px;margin-top:1px;margin-left:18px;color: #AFB4B8;font-family:myriadpro-regular;}
    .bg_logo_inner{
        position:absolute;
        background:none;
        height:320px;
        width:300px;
        left:-160px;
        top:39px;
    }
    .wrapper_new{
        margin:0px auto;
        min-height:100%;
        height:100%;
        width:545px;
        display:table;
    }
    .card_i {
        background:#E8F4FF;
        border: 1px solid #B7D3EB;
        margin:2% 0 0 3%;
        padding:2%;
        width:91%;
    }
    .card_i_blue{
        /*background:#E8F4FF;border-radius:7px;-webkit-border-radius:7px;-moz-border-radius:7px;padding:10px;border:1px solid #B7D3EB;color:#367fbf;padding:10px 30px;*/
        color:#5593BD;
        padding-left:3px;
        font-size: 17px;
    }
    .pay_user {
        color: #FF7E00;
        font-size: 20px;
    }
    .card_img {
        color: #FF7E00;
        font-size: 30px;
        margin: -114px -180px;
        position: absolute;
    }
    .card_img img {
        height:35px;
        width:55px;
    }

    .pay_user sup{font-size:25px;vertical-align: super;}
    .pay_user sub{font-size:16px;vertical-align: sub;line-height: 25px;}
    .pay_user span{font-size:14px;font-weight: bold;}
    ul.risk_free {margin-left:24px;list-style:decimal}
    ul.risk_free li{color:#494949;font-size:16px;line-height:20px;font-family:myriadpro-regular;}
    .info_small{position:absolute;margin:20px -20px;}
    .info_small:hover .info_cvv{display:block;}
    .info_cvv{position:absolute;top:31px;z-index:999;display:none;}
    input, textarea, select {height:auto;}
    .srn_768{ margin-left:40px;}
    input.domainname.input_http{width:60px;}
    input.domainname.input_url{width:135px;}
    .ml55_sup{margin-left:25px;}
    .con_wid{width:49%;margin-left:4%}
    .h1{width:390px;margin-left:13px}
    .h2{width:433px;margin-left:13px}
    .fre_30{left:-8px;position:absolute;top: -11px;}
    .m9p{margin-right:9%}
    .btn.btn_blue{
        background-image:-webkit-linear-gradient(top, #43C86F, #2FB45B);
        background-image: -ms-linear-gradient(top, #43C86F, #2FB45B);
        background-image: -o-linear-gradient(top, #43C86F, #2FB45B);
        background-image: linear-gradient(to bottom, #43C86F, #2FB45B);
        background-image: -moz-linear-gradient(top, #43C86F, #2FB45B);
        color:#fff;font-size:14px;padding:6px 27px;font-family:'OPENSANS-REGULAR';margin-right:10px;
    }
    .btn.btn_blue:hover{background:#2FB45B;}
    .btn {
        display: inline-block;
        padding: 6px 12px;
        margin-bottom: 0;
        font-size: 14px;
        font-weight: normal;
        line-height: 1.428571429;
        text-align: center;
        vertical-align: middle;
        cursor: pointer;
        border: 1px solid transparent;
        border-radius: 3px;
        white-space: nowrap;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        -o-user-select: none;
        user-select: none;
    }
    @font-face{
        font-family:'OPENSANS-REGULAR';
        src: url('../fonts/OPENSANS-REGULAR.woff');
        font-weight:normal;
        font-style:normal;
    }
@media (max-width:1290px) {
	input.domainname.input_http{width:70px; padding:10px!important;}
	input.domainname.input_url{width:137px; padding:10px 3px 10px 4px!important;}
	.demo_login_bg_l .login_box .srn_768{margin-left:9px;}
	.demo_login_bg_l .login_box .srn_768 input#company{}
	.demo_login_bg_l .login_box  .ml55_sup{margin-left:10px;}
	input#company, .seo_url,input.domainname,input#email,input#name,input#password,input#confirm_password,input#card_number,input#name_oncard, input#expiry_date, input#card_cvc{font-size:17px;}
	#card_info input#company, #card_info .seo_url,#card_info input.domainname,#card_info input#email,#card_info input#name,#card_info input#password,#card_info input#confirm_password,#card_info input#card_number,#card_info input#name_oncard,#card_info input#expiry_date,#card_info input#card_cvc{width:390px;}
	.demo_login_bg_l .login_box  h3.reg{margin-right:25px;}
	.faq_con{width:430px!important;}
	.card_i{margin: 2% 0 0 2%;padding: 2% 0 2% 2%;width: 95.6%;}
	.con_wid{width:44%;margin-left:4% !important}
	.h1, .h2{width:305px}
	.mem_typ h5 {margin:46px 52px 18px 74px;}
	.fre_30{top:-9px;width: 14%;}
	.mem_typ.logo_landing{margin-left:2%; width: 97.7%;}
	.m9p{margin-right:5%}
	input#card_cvc{width:128px!important}

}
@media (min-width:751px) and (max-width:959px){
	.top_m_cont_land{height:769px!important;}
	#DemoRequestRequestDemoForm #su_bmit{margin-left: 80px;}
	#DemoRequestRequestDemoForm .demo_fl{float:none;width:76%;margin:75px auto 0;}
	.demo_fl h3.reg{font-size:26px!important;}
	.demo_fl span{font-size:19px!important;}
	.wrapper_new{width:640px}
	.login_box{width:766px;}
	ul.risk_free li{width:500px;font-size:12px}
	.srn_768{ margin:30px 0 0 55px;}
	.card_i{ padding: 2% 5% 2% 30%; margin: 2% 0 0;  width: 62%;}
	.donot_refresh{height:auto!important; width:74%!important;}
	.donot_refresh img{margin-top:3px;}
	input.domainname.input_http,input.domainname.input_url{padding:10px!important;}
	input#company, .seo_url,input.domainname,input#email,input#name,input#password,input#confirm_password,input#card_number,input#name_oncard, input#expiry_date, input#card_cvc{font-size:17px;width:450px}

	#card_info input#company, #card_info .seo_url,#card_info input.domainname,#card_info input#email,#card_info input#name,#card_info input#password,#card_info input#confirm_password,#card_info input#card_number,#card_info input#name_oncard,#card_info input#expiry_date,#card_info input#card_cvc{width:586px;}
	.faq_con{width: 328px!important;}
	#password_conf_err,#password_err{top: 48px!important;}
	.seo_url.domain_ipad{width:415px!important;}
	.footer_btm{display:none}
	.con_wid{width:93%;margin-left:1.5% !important;margin-top:20px}
	.mem_typ{width:97%}
	.mem_typ.logo_landing{width:92.7%;padding:9px 0;}
	.h1, .h2{width:545px;}
	.join_txt {left:1%;}
	h3.reg{font-size:25px;}
	.fre_30{left:-5px;top:-6px;width:12%;}
	.card_i{margin:2% 2% 0;padding: 2% 0 2% 3%;width: 89.6%;}
	.m9p{float:left}
	input#card_cvc{width:324px!important}
	.info_cvv img{width:60%}
}
@media (min-width:450px) and (max-width:500px){
	input#company, .seo_url,input.domainname,input#email,input#name,input#password,input#confirm_password,input#card_number,input#name_oncard, input#expiry_date, input#card_cvc{ margin: 2px 1px 10px 3px!important;width:400px!important}
	#su_bmit{margin-left: 15px!important;}
	.demo_fl{padding-left:10px;width:99%!important;}
}

@media (min-width:300px) and (max-width:440px){
	input#company, .seo_url,input.domainname,input#email,input#name,input#password,input#confirm_password,input#card_number,input#name_oncard, input#expiry_date, input#card_cvc{ margin: 2px 1px 0px 5px!important;width:240px!important}
	#su_bmit{margin-left: 15px;}
	.demo_fl h3.reg{font-size:26px!important;}
	.demo_fl span{font-size:20px!important;}
	.sub_form_bg.tour_btn button.tk_tour{font-size:20px;padding:10px 22px;}
	.sub_form_bg{width:248px;}
	.demo_fl{padding-left:10px;width:93%!important;}
	
}
	.demo_fl{margin-top:50px;margin-left:0;width:91%}
	#su_bmit{text-align:left}
    .donot_refresh{border-radius: 2px 2px 2px 2px;font-family:myriadpro-regular;font-size: 19px;left: 0;margin: 20px auto;padding-top: 8px;position: fixed;right: 0;text-align: center;top: 20%; width: 60%; z-index: 99999999;border:0px solid #ABACAD;line-height:30px;background:#FDE7D1;box-shadow:0 4px 2px #EEEEEE;-moz-box-shadow:0 4px 2px #EEEEEE;-webkit-box-shadow:0 4px 2px #EEEEEE;height:auto;}
    .cb20{clear:both; height:20px}
    .faq_con{padding-left:0px;width:520px;}

    .faq_con_rht_bx_shd{/*background:url('<?php echo HTTP_ROOT; ?>img/rht_ppr.png/>') right top repeat-y;box-shadow:-1px 2px 1px -1px rgba(0, 0, 0, 0.27);-moz-box-shadow:-1px 2px 1px -1px rgba(0, 0, 0, 0.27);-webkit-box-shadow:-1px 2px 1px -1px rgba(0, 0, 0, 0.27);-o-box-shadow:-1px 2px 1px -1px rgba(0, 0, 0, 0.27);*/ padding-left: 0; padding-right:0px;}
    .faq_qn{color:#494949; text-align:left;font-size:18px}
    .lbl_ie{font-family:Muli-regular;color:#555555;}
    input[type=radio].css-checkbox {
        display:none;
    }
    input[type=radio].css-checkbox + label.css-label {
        padding-left:25px;
        height:21px;
        display:inline-block;
        line-height:21px;
        background-repeat:no-repeat;
        background-position: 0 0;
        font-size:20px;
        vertical-align:middle;
        cursor:pointer;
        margin-top:-5px;

    }

    input[type=radio].css-checkbox:checked + label.css-label {
        background-position: 0 -21px;
    }
    label.css-label {
        background-image:url("<?php echo HTTP_IMAGES;?>radio.png");
        -webkit-touch-callout: none;
        -webkit-user-select: none;
        -khtml-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }
    .mtype_text{text-decoration: none;font-size: 20px;font-weight:normal;	margin-right: 20px;	color: #555555;}
    .signup-card-icon { background: url("<?php echo HTTP_ROOT;?>img/signup_card.png") no-repeat scroll -17px 0 rgba(0, 0, 0, 0);    display: inline-block;    height: 46px;    width: 390px;}
    .secure_icon{background: url("<?php echo HTTP_ROOT;?>img/signup_card.png") no-repeat scroll 0px 0 rgba(0, 0, 0, 0);display: inline-block;height:28px;width: 17px;}

    .mtype_text:hover{color:#555555 !important;}
    /*Tipsy*/
    .tipsy {padding:5px;font-size:13px;opacity:.9;filter:alpha(opacity=90);background:url(../img/tooltip.gif) no-repeat;font-family:"Lucida Sans Unicode", "Lucida Grande", sans-serif;}
    .tipsy-inner {padding:5px 8px 4px;background-color:black;color:white;max-width:200px;text-align:center;-moz-border-radius:3px;-webkit-border-radius:3px;	border-radius:3px;}
    .tipsy-north { background-position:top center; }
    .tipsy-south { background-position:bottom center; }
    .tipsy-east { background-position:right center; }
    .tipsy-west { background-position:left center; }
    /*blue tooltip*/
    .show_tooltip{position:absolute;bottom:29px;left:0}
    a.wink{position:relative}
    /*blue tooltip ends*/
    /*Tipsy End*/
    
</style>
<script>
function loader() {
	/*$('#submit_button').html("Scheduling...");
	$('#submit_button').attr("disabled", "disabled");
	return true;*/
}
</script>
<script type="text/javascript">
    $(function(){
	var visitortime = new Date();
	var visitortimezone = -visitortime.getTimezoneOffset()/60;
	$('#DemoRequestTimezoneId').val(visitortimezone);
    });
</script>
<div class="top_m_cont_land signup_ipad signup_team">
    <div class="wrapper_new" style="display:block;">
        <div style="position:relative">
            <div class="bg_logo_inner"></div>
            <div class="cb ht25_480" style="height:45px;"></div>
            <div class="login_table">
                <div id="container" style="vertical-align:middle;">
                   
                    <div class="cb" style="width:648px;margin-top:30px;"></div>
                    <div class="demo_fl" style="text-align:center;">
                    	<?php if($type == "success") { ?>
                        <div class="gap10" style="height:50px">&nbsp;</div>
                        <h3 class="reg" style="font-size:33px;color:#3D8B25;text-align:center;">Thank You For Your Demo Request</h3>
                        <span style="font-size:22px;color:#333;font-family:'MyriadPro-Regular';">A representative will contact you shortly.</span>
                        
                        <div class="cb"></div>
                        <div class="sub_form_bg tour_btn" style="margin:70px auto;text-align:center;">
                            <a style="text-decoration:none;" href="<?php echo PROTOCOL."www.".DOMAIN; ?>signup/getstarted<?php echo $ablink; ?>" onclick="getstarted_ga(' features');">
                               <span class="tk_tour" style="padding:10px 30px">Get Started Now. Its FREE!</span>
                            </a>
                        </div>
                        <div class="gap10" style="height:50px">&nbsp;</div>
                        
						<?php } else { ?>
                        <?php echo $this->Form->create('DemoRequest',array('url'=>'/users/request_demo')); ?>
						<?php echo $this->Form->hidden('timezone_id'); ?>
                        <h3 class="reg" style="font-size:33px;color:#333">Schedule a Demo</h3>
                        <div style="font-size:22px;color:#333;font-family:'MyriadPro-Regular'; text-align:left">Contact Us Now to See What OrangeScrum Can Do For You!</div>
                        
                        <div class="gap10"></div>
                        <div class="gap10"></div>
                        <div class="gap10"></div>
                        <?php echo $this->Form->input('name',array('size'=>'20','class'=>'seo_url','placeholder'=>'Name','title'=>'Name','id'=>'name','label'=>FALSE)); ?> <span style="color:#FF0000">*</span>

                        <div class="gap10"></div>
                        <div class="gap10"></div>
                        <?php echo $this->Form->input('email',array('size'=>'20','class'=>'','placeholder'=>'Email','title'=>'Email','id'=>'email','label'=>false)); ?> <span style="color:#FF0000">*</span>
                        
                        <div class="gap10"></div>
                        <div class="gap10"></div>
                        <?php echo $this->Form->textarea('message',array('rows'=>3,'class'=>'seo_url','style'=>'' ,'placeholder'=>'Message','title'=>'Message','label'=>FALSE)); ?> <span style="color:#FF0000">*</span>

                        <div class="gap10"></div>
                        <div class="gap10"></div>
                        <?php echo $this->Form->input('company',array('size'=>'20','class'=>'seo_url','placeholder'=>'Company','title'=>'Company','label'=>FALSE)); ?>

                        <div class="gap10"></div>
                        <div class="gap10"></div>
                        <?php echo $this->Form->input('phone',array('size'=>'20','class'=>'seo_url','placeholder'=>'Phone','title'=>'Phone','label'=>FALSE)); ?>

                       
                        <div class="gap10"></div>
                    </div>

                    <div class="gap10"></div>

                    <div id="su_bmit" align="center">
                        <button type="submit" value="Save"  name="submit_button" id="submit_button" class="btn btn_blue"  style="font-size:18px;font-weight:bold;" onclick="return loader()">Submit</button>
                        
                        <?php echo $this->Form->end(); ?>
                    </div>
                    <?php } ?>
                    <div class="gap10"></div>
                    <div class="gap10"></div>
                    <div class="gap10"></div>
                </div>
            </div>
        </div>
    </div>
</div>
 <br/>
<div class="tab tab_comon">
    <ul class="nav-tabs">
	<li <?php if(PAGE_NAME == 'subscription') {?>class="active" <?php }?>>
	    <a href="<?php echo HTTP_ROOT.'users/subscription';?>" id="sett_mail_noti_prof">
                <div class="fl acct_sub"></div>
                <div class="fl">Subscription</div>
                <div class="cbt"></div>
	    </a>
	</li>
	<li <?php if(PAGE_NAME == 'creditcard') {?>class="active" <?php }?>>
	    <a href="<?php echo HTTP_ROOT.'users/creditcard';?>" id="sett_mail_repo_prof">
                <div class="fl acct_cre"></div>
                <div class="fl">Credit Card</div>
                <div class="cbt"></div>
	    </a>
	</li>
	<li <?php if(PAGE_NAME == 'transaction') {?>class="active" <?php }?>>
	    <a href="<?php echo HTTP_ROOT.'users/transaction';?>" id="sett_imp_exp_prof">
                <div class="fl acct_tran"></div>
                <div class="fl">Transactions</div>
                <div class="cbt"></div>
	    </a>
	</li>
	<li <?php if(PAGE_NAME == 'account_activity') {?>class="active" <?php }?>>
	    <a href="<?php echo HTTP_ROOT.'users/account_activity';?>" id="sett_my_comp_prof">
                <div class="fl acct_acv"></div>
                <div class="fl">Account Activities</div>
                <div class="cbt"></div>
	    </a>
	</li>
	<div class="cbt"></div>
    </ul>
</div>
<script type="text/javascript">
function cancel_sub_info_popup(company_id){ 
	$(".popup_overlay").css({display:"block"});
    $(".popup_bg").css({display:"block"});
	$(".popup_bg").css({width:"650px"});
    $(".cancel_sub_popup_content").show();
	$('#cancel_sub_info_popup').show();
	var pageurl = $("#pageurl").val(); 
	var path = "users/cancel_sub";
	$.post(pageurl+path,{"popup":1,'company_id':company_id},function(data){ 
		$('.loader_dv').hide();
		$('#cancel_sub_popup_content').show();
		$("#cancel_sub_popup_content").html(data);
	});
}
function cancelsub_pop_close(){
	$('#popup_overlay').hide();
	$('#cancel_sub_info_popup').hide();
}
// Get all activity related to payment 
	function get_payment_activity(page_no){
		var filter=$('#activity_type_id').val();
		$('#activity_data').html("<span style='position: absolute;margin-top: 2%;margin-left: 1%;'><img src='<?php echo HTTP_ROOT;?>img/images/loading_dark_nested.gif'><br/>Loading...</span>");
		var url = $('#pageurl').val();
		$('.ui_tab_cls').removeClass('class_active');
		$.post(url+"users/account_activity",{"page":page_no,'filter':filter,'ajaxlayout':'1'},function(res){
			$('#activity_data').html(res);
		});
	}

</script>
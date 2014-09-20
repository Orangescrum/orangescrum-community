<div id="crt_new_user" class="onbording_main_div" style="margin-top:-10%;">
	<div class="on_brd">
		<div class="on_brd">Looks like it's just you in here</div>
		<div>Orangescrum works best when you add your coworkers.</div>
		<div class=" fl"   style="padding:7px 12px;background:#ff7800;color:#fff;border:1px solid #fff;font-size:15px; margin-top:20px">
			<div style="background: url('<?php echo HTTP_ROOT;?>img/html5/icons/team_n.png') no-repeat scroll 0 0 rgba(0, 0, 0, 0);height: 16px;left: -3px;position: relative;top: 2px; width: 16px;" class="fl" ></div>	
			<div class="fl" style="margin: 2px;">
				<a href="javascript:void(0);" <?php if(ACCOUNT_STATUS!=2){?> onclick="newUser('menuid1','loaderid1');" <?php }?> style="text-decoration: none;color: #fff; font-weight: bold; font-size: 14px;" > Invite User</a>
			</div>
		</div>
		<a href="javascript:void(0);" <?php if(ACCOUNT_STATUS!=2){?> onclick="skiponboarding();" <?php }?> style="text-decoration: none;color: #fff; font-weight: bold; font-size: 14px;" >
		<div class=" fl"   style="padding:7px 31px;background:#ff7800;color:#fff;border:1px solid #fff;font-size:15px; margin-top:20px;margin-left: 10px;">
			<div class="fl" style="margin: 2px;">Skip >></div>
		</div>
		 </a>	
	</div>
</div>
<script type="text/javascript">
	$('#crt_new_proj').hide();
	$('#cover1').show();
function skiponboarding(){
	//$('#cover1').hide();
	window.location.href=HTTP_ROOT+'dashboard';
}
</script>
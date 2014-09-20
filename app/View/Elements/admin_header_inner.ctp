<!--- views/elements/admin_header_inner.ctp---->
<style type="text/css">
.ui-tabs {
	zoom: 1;
	/*width:280px;*/
	padding:0;
}
.ui-tabs .ui-tabs-nav {
	list-style: none;
	position: relative;
	padding: 0 0 0 15px;
	margin: 0;
	width:500px;
}
.ui-tabs .ui-tabs-nav li {
	position: relative;
	float: left;
	margin: 0 3px -1px -15px;
	padding: 0;
}
.ui-tabs .ui-tabs-nav li a {
	display: block;
	/*padding:6px 10px;*/
	background:url(<?php echo HTTP_ROOT;?>img/images/tab_bg.png) no-repeat;
	outline: none;
	font-family:tahoma;
	color:#fff;
	font-size:13px;
	/*height:19px;*/
	width:86px;
	text-decoration:none;
}

.ui-tabs .ui-tabs-nav li a:hover {
	display: block;
	/*padding:6px 10px;*/
	background:url(<?php echo HTTP_ROOT;?>img/images/tab_hover_bg.png) no-repeat;
	outline: none;
	font-family:tahoma;
	color:#fff;
	font-size:13px;
	/*height:19px;*/
	width:86px;
	text-decoration:none;
	z-index:999;
	position:relative;
}
.class_active{
	padding:6px 10px 7px 10px;
	background:url(<?php echo HTTP_ROOT;?>img/images/tab_hover_bg.png) no-repeat;
	border-bottom:none;
	position:relative;
	font-weight:bold;
}
.ms_hd
{
	background: -moz-linear-gradient(top, #eee 0%, #fff 1%, #eee 100%, #fff 100%, #fff 100%, #F2F2F2 100%, #D6D6D6 100%);
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#eee), color-stop(1%,#fff), color-stop(100%,#eee), color-stop(100%,#fff), color-stop(100%,#fff), color-stop(100%,#F2F2F2), color-stop(100%,#D6D6D6));
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#f9f9f9', endColorstr='#f2f2f2',GradientType=0 )!important;
	height:35px;
	border-radius: 3px 3px 0 0;
	-moz-border-radius:3px 3px 0 0;
	-webkit-border-radius:3px 3px 0 0;
}
.act_dis
{
	float:none;
}
.act_user
{
	float:none;
}

#topmostdiv {
    left: 0;
    position: fixed;
    right: 0;
    text-align: center;
    width:30%;
    margin:0 auto;
}
</style>
<input type="hidden" name="pageurl" id="pageurl" value="<?php echo HTTP_ROOT; ?>" size="1" readonly="true"/>
<input type="hidden" name="pagename" id="pagename" value="<?php echo PAGE_NAME; ?>" size="1" readonly="true"/>
<div id="topmostdiv">
	<?php
	if($success){
	?>
	<div id="upperDiv" onClick="removeMsg();" style="display:block;color:green" class="topalerts success">
		<?php echo $success; ?>
	</div>
	<!--<div id="btnDiv" onClick="removeMsg();" style="position:absolute;right:12px;top:15px;cursor:pointer;display:blobk;">-->
	<div id="btnDiv" style="position: absolute; cursor: pointer; top: 1px; right: 0px; left: 220px;" onclick="removeMsg();">
		<img src="<?php echo HTTP_IMAGES;?>images/clbtn.png" />
	</div>
	<?php
	}
	elseif($error){
	?>
	<div id="upperDiv_err" onClick="removeMsg_err();" style="display:block;color:#FADAD8" class="topalerts error">
		<?php echo $error; ?>
	</div>
	<!--<div id="btnDiv" onClick="removeMsg_err();" style="position:absolute;right:12px;top:15px;cursor:pointer;display:block;">-->
	<div id="btnDiv" style="position: absolute; cursor: pointer; top: 1px; right: 0px; left: 220px;" onclick="removeMsg();">
		<img src="<?php echo HTTP_IMAGES;?>images/clbtn.png" />
	</div>
	<script>setTimeout('removeMsg()',7000);</script>
	<?php
	}
	else{
	?>
		<div id="upperDiv" onClick="removeMsg();" style="display:none;" class="topalerts success"></div>
		<div id="upperDiv_err" onClick="removeMsg_err();" style="display:none;" class="topalerts error"></div>
		
	<?php
	}
	?>
<div id="btnDiv" onClick="removeMsg();removeMsg_err()" style="position:absolute;right:12px;top:15px;cursor:pointer;display:none;">
<img src="<?php echo HTTP_IMAGES;?>images/clbtn.png" />
</div>
</div>
<div>
<div class="admin_logo">   
	<a href="<?php echo HTTP_ROOT.Configure::read('default_action'); ?>">
		<img src="<?php echo HTTP_IMAGES.'images/os_inner.png'; ?>" />
	</a>
</div>
    <!--<div class="fleft" style="margin-top: 45px;">
		<div class="fleft" style="margin-left:10px;">
			<span style="width:100%;text-align:center;"><a href="<?php echo HTTP_ROOT.'osadmins/manage_subscription';?>" class="button-link" style="font-size:16px;">Subscription</a></span>
		</div>
		<div class="fleft" style="margin-left:10px;">
			<span style="width:100%;text-align:center;"><a href="<?php echo HTTP_ROOT.'osadmins/manage_company';?>" class="button-link" style="font-size:16px;">Company</a></span>
		</div>
		<div class="fleft" style="margin-left:10px;">
			<span style="width:100%;text-align:center;"><a href="<?php echo HTTP_ROOT.'osadmins/add_company';?>" class="button-link" style="font-size:16px;">Add Company</a></span>
		</div>
	</div>-->
	<div class="ui-tabs admin_ui" id="osadmin_menutab">
     <ul class="ui-tabs-nav">
        <li id="dashboard" <?php if(PAGE_NAME == 'dashboard'){?> class="class_active"<?php }?> >
            <a href="<?php echo HTTP_ROOT.'osadmins';?>">Dashboard</a>
         </li>
	 <li id="com_pany">
            <a href="<?php echo HTTP_ROOT.'osadmins/manage_company';?>">Company</a>
         </li>
	 <?php if(IS_MODERATOR==0){ ?>
	 <li id="addcom_pany">
            <a href="<?php echo HTTP_ROOT.'osadmins/add_company';?>">Add Company</a>
         </li>
        <li id="sub_scription">
            <a href="<?php echo HTTP_ROOT.'osadmins/manage_subscription';?>">Subscription</a>
         </li>
	 <li id="moderator_user" <?php if(PAGE_NAME == 'moderator'){?> class="class_active"<?php }?> >
            <a href="<?php echo HTTP_ROOT.'osadmins/moderator';?>">Moderator</a>
         </li>
	 <?php } ?>
	 
	 <li id="unlimited" <?php if(PAGE_NAME == 'manage_company' && isset($this->params['pass']['0']) && $this->params['pass']['0'] == 'unlimited'){?> class="class_active"<?php }?> >
            <a href="<?php echo HTTP_ROOT.'osadmins/manage_company/unlimited';?>">Unlimited</a>
         </li>
	 
	 <?php if(IS_MODERATOR==0){ ?>
	 <li id="beta_user" <?php if(PAGE_NAME == 'betauser'){?> class="class_active"<?php }?> >
            <a href="<?php echo HTTP_ROOT.'osadmins/betauser';?>">Beta User (Old)</a>
         </li>
	 <?php } ?>
	 
	 <li id="sandbox" <?php if(PAGE_NAME == 'manage_company' && isset($this->params['pass']['0']) && $this->params['pass']['0'] == 'sandbox'){?> class="class_active"<?php }?> >
            <a href="<?php echo HTTP_ROOT.'osadmins/manage_company/sandbox';?>">Sandbox</a>
         </li>
         
	  
         <!--li id="cpn">
            <a href="<?php //echo HTTP_ROOT.'osadmins/manageCoupon';?>">Coupon</a>
         </li>
         <li id="addcpn">
            <a href="<?php //echo HTTP_ROOT.'osadmins/addCoupon';?>">Add Coupon</a>
         </li-->
		 <li id="beta_user" <?php if(PAGE_NAME == 'deleted_company'){?> class="class_active"<?php }?> >
            <a href="<?php echo HTTP_ROOT.'osadmins/deleted_company';?>">Cancelled</a>
         </li>
      </ul>
     </div>

<div class="admin_cover">
<!--    <div class="wel_admin fleft">WELCOME ORANGESCRUM ADMINISTRATOR</div>--> 
 <div class="clear"></div>
     
 <div class="clear"></div>

<!--		<div style="margin-top:20px;float:left;margin-left:80px;font-size:16px;color:#F16624;font-weight:bold">
			<span style="width:100%;text-align:center;">WELCOME ORANGESCRUM ADMINISTRATOR</span>
		</div>
-->	

<div class="td_outer_box">
<?php if(PAGE_NAME!='dashboard' && PAGE_NAME !='authenticate'){?>	
<div class="top_nav_bar">
	<table cellspacing="0" cellpadding="0">
		<tbody>
			<tr>
				<td>
					<ul style="border:0px solid #FF0000;font-style:italic;margin:0;padding:0 0 0 14px;" id="breadcrumbs">
						<li><a original-title="Home" href="<?php echo HTTP_ROOT.'osadmins/';?>">Home</a></li>
					</ul>
				</td>
				<td style="padding-left:10px;">
					<img src="<?php echo HTTP_ROOT;?>img/html5/icons/icon_breadcrumbs.png">
				</td>
				<td style="padding-left:10px;">
					<h1 class="popup_link" id="pageheading" style="font-size:20px;margin:0px;padding:0px;border:0px solid #FF0000;">
						<?php if(PAGE_NAME=='company_details'){?>
						Company&nbsp;&nbsp;
						<img src="<?php echo HTTP_ROOT;?>/img/html5/icons/icon_breadcrumbs.png" style="padding:2px 0px;">&nbsp;&nbsp;Details
						<?php }
						if(PAGE_NAME=='manage_company'){?>
						Company&nbsp;&nbsp;
						<img src="<?php echo HTTP_ROOT;?>/img/html5/icons/icon_breadcrumbs.png" style="padding:2px 0px;">&nbsp;&nbsp;Manage
						<?php } 
						if(PAGE_NAME=='manage_subscription'){?>
						Subscription&nbsp;&nbsp;
						<img src="<?php echo HTTP_ROOT;?>/img/html5/icons/icon_breadcrumbs.png" style="padding:2px 0px;">&nbsp;&nbsp;Manage
						<?php } 
						if(PAGE_NAME=='add_company'){?>
						Company&nbsp;&nbsp;
						<img src="<?php echo HTTP_ROOT;?>/img/html5/icons/icon_breadcrumbs.png" style="padding:2px 0px;">&nbsp;&nbsp;Add Company
						<?php } 
						if(PAGE_NAME=='admin_betauser'){?>
						Beta Company&nbsp;&nbsp;
						<img src="<?php echo HTTP_ROOT;?>/img/html5/icons/icon_breadcrumbs.png" style="padding:2px 0px;">&nbsp;&nbsp;Manage
						<?php } 
						if(PAGE_NAME=='company_user_details'){?>
						Company&nbsp;&nbsp;
						<img src="<?php echo HTTP_ROOT;?>/img/html5/icons/icon_breadcrumbs.png" style="padding:2px 0px;">&nbsp;&nbsp;Users
						<?php } 						
						if(PAGE_NAME=='user_details'){?>
						Company&nbsp;&nbsp;
						<img src="<?php echo HTTP_ROOT;?>/img/html5/icons/icon_breadcrumbs.png" style="padding:2px 0px;">&nbsp;&nbsp;User Details
						<?php } 
						if(PAGE_NAME=='subscription'){?>
						Subscription&nbsp;&nbsp;
						<img src="<?php echo HTTP_ROOT;?>/img/html5/icons/icon_breadcrumbs.png" style="padding:2px 0px;">&nbsp;&nbsp;Edit
						<?php }
						if(PAGE_NAME=='project_details'){?>
						Company&nbsp;&nbsp;
						<img src="<?php echo HTTP_ROOT;?>/img/html5/icons/icon_breadcrumbs.png" style="padding:2px 0px;">&nbsp;&nbsp;Project Details
						<?php } 
						if(PAGE_NAME=='company_project_details'){?>
						Company&nbsp;&nbsp;
						<img src="<?php echo HTTP_ROOT;?>/img/html5/icons/icon_breadcrumbs.png" style="padding:2px 0px;">&nbsp;&nbsp;Projects
						<?php } 
						if(PAGE_NAME=='betauser'){?>
						Manage&nbsp;&nbsp;
						<img src="<?php echo HTTP_ROOT;?>/img/html5/icons/icon_breadcrumbs.png" style="padding:2px 0px;">&nbsp;&nbsp;Beta Request
						<?php } 
						if(PAGE_NAME=='moderator'){?>
						Manage&nbsp;&nbsp;
						<img src="<?php echo HTTP_ROOT;?>/img/html5/icons/icon_breadcrumbs.png" style="padding:2px 0px;">&nbsp;&nbsp;Moderator
						<?php }
						if(PAGE_NAME=='deleted_company'){?>
						Company&nbsp;&nbsp;
						<img src="<?php echo HTTP_ROOT;?>/img/html5/icons/icon_breadcrumbs.png" style="padding:2px 0px;">&nbsp;&nbsp;Cancelled
						<?php } ?>
					</h1>
				</td>
			</tr>
		</tbody>
	</table>
</div>
<?php }?>
<script type="text/javascript">
	<?php if(PAGE_NAME == 'manage_company' && !isset($this->params['pass']['0'])){ ?>
			$("#com_pany").addClass("class_active");
	<?php }else if(PAGE_NAME == 'add_company'){ ?>
			$("#addcom_pany").addClass("class_active");
	<?php }else if(PAGE_NAME == 'manage_subscription'){ ?>
			$("#sub_scription").addClass("class_active");
	<?php }else if(PAGE_NAME == 'addCoupon'){ ?>
			$("#addcpn").addClass("class_active");
	<?php }else if(PAGE_NAME == 'manageCompany'){ ?>
			$("#cpn").addClass("class_active");
	<?php } ?>
</script>

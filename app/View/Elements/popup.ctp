<!--[if lte IE 9]>
    <style>
        #chked_all{top:2px!important;}
    </style>	
<![endif]-->
<div class="popup_overlay"></div>
<div class="popup_bg">
    <!-- New project popup starts -->
    <div class="new_project cmn_popup" style="display: none;">
	<div class="popup_title">
	    <span><i class="icon-create-proj"></i> Create New Project</span>
	    <a href="javascript:jsVoid();" onclick="closePopup();"><div class="fr close_popup">X</div></a>
	</div>
	<div class="popup_form">
	    <div class="loader_dv"><center><img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="Loading..." title="Loading..." /></center></div>
	    <div id="inner_proj" style="display: none;"><?php echo $this->element('new_project'); ?></div>
	</div>
    </div>
    <!-- New project popup ends -->
    
    <!-- Task type popup starts -->
    <div class="new_tasktype cmn_popup" style="display: none;">
	<div class="popup_title">
	    <span><i class="icon-create-tsk-type"></i> New Task Type</span>
	    <a href="javascript:jsVoid();" onclick="closePopup();"><div class="fr close_popup">X</div></a>
	</div>
	<div class="popup_form">
	    <div class="loader_dv"><center><img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="Loading..." title="Loading..." /></center></div>
	    <div id="inner_tasktype" style="display: none;">
		<center><div id="tterr_msg" style="color:#FF0000;display:none;"></div></center>
		<form name="task_type" id="customTaskTypeForm" method="post" action="<?php echo HTTP_ROOT."projects/addNewTaskType"; ?>" autocomplete="off">
			<input type="hidden" class="form-control" name="data[Type][id]" id="new-typeid"/>
		    <div class="data-scroll">
			<table cellpadding="0" cellspacing="0" class="col-lg-12">
			    <tr>
				<td class="popup_label">Name:</td>
				<td>
				    <input type="text" value="" class="form-control" name="data[Type][name]" id="task_type_nm" placeholder="Design" maxlength="20" />
				</td>
			    </tr>
			    <tr>
				<td class="popup_label">Short Name:</td>
				<td>
				    <input type="text" value="" class="form-control" name="data[Type][short_name]" id="task_type_shnm" placeholder="dgn" maxlength="4" />
				</td>
			    </tr>
			</table>
		    </div>
		    <div style="padding-left:145px;">
			<span id="ttloader" style="display:none;">
			    <img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="Loader"/>
			</span>
			<span id="ttbtn">
			    <button id="newtask_btn" type="button" value="Add" onclick="return validateTaskType();" name="crttasktype" class="btn btn_blue"><i class="icon-big-tick"></i>Add</button>
			    <span class="or_cancel">or
			       <a onclick="closePopup();">Cancel</a>
			   </span>
			</span>
		    </div>
		</form>
	    </div>
	</div>
    </div>
    <!-- Task type popup ends -->
    
    <!-- Help popup starts -->
    <div class="help_popup cmn_popup" style="display: none;">
	<div class="popup_title">
	    <span><i class="icon-help-user"></i> Need help getting started?</span>
	    <a href="javascript:jsVoid();" onclick="trackclick('Close Button');closePopup();"><div class="fr close_popup">X</div></a>
	</div>
	<div class="popup_form">
	    <div class="loader_dv"><center><img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="Loading..." title="Loading..." /></center></div>
	    <div class="help-text"> 
			If you get stuck or need help with anything we are here for you. Just <a data-toggle="dropdown" class="dropdown-toggle support-popup" href="javascript:void(0);" onclick="trackclick('Send us a line')">send us a line</a> we will get back to you as soon as possible or find your answer at our <a href="<?php echo HTTP_ROOT.'help';?>" target="_blank" onclick="trackclick('Help Desk');">help desk</a>.</div>
		<div class="hlpe_popbtn"><button class="btn btn_blue" onclick="closePopup();trackclick('Ok ,got it!');"><i class="icon-big-tick"></i>Ok, got it!</button></div>			
	</div>
    </div>
    <!-- Help popup ends -->
    
    <!-- Move project popup starts -->
    <div class="mv_project cmn_popup" style="display: none;">
	<div class="popup_title">
	    <span>
		<i class="icon-mov-proj"></i> 
		<span class="hdr-cnt">
		    <span id="header_mvprj" class="fnt-nrml"></span>
		</span>
	    </span>
	    <a href="javascript:jsVoid();" onclick="closePopup();"><div class="fr close_popup">X</div></a>
	</div>
	<div class="popup_form">
	    <div class="loader_dv"><center><img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="Loading..." title="Loading..." /></center></div>
	    <div id="inner_mvproj" style="display: none;"></div>
	</div>
    </div>
    <!-- Move project popup ends -->
    
    <!-- New user popup starts -->
    <div class="new_user cmn_popup" style="display: none;">
	<div class="popup_title">
	    <span><i class="icon-create-user"></i> Invite New User</span>
	    <a href="javascript:jsVoid();" onclick="closePopup();"><div class="fr close_popup">X</div></a>
	</div>
	<div class="popup_form">
	    <div class="loader_dv"><center><img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="Loading..." title="Loading..." /></center></div>
	    <div id="inner_user" style="display: none;">
			<?php 
			if(defined('SMTP_PWORD') && SMTP_PWORD != "******") {
				echo $this->element('new_user');
			}
			else {
			?>
				<div style="color:#666;background:#F0F0F0;font-size:16px;padding:5px 10px;text-align:left;font-family:'Courier New', Courier, monospace;border:1px dashed #FF7E00;">
				Make sure that you have done the below required changes in <b style="color:#000">`app/Config/constants.php`</b><br/>
				<ul>
				 <li>You have provided the details of <b>SMTP</b> email sending options in <b>`app/Config/constants.php`</b></li>
				 <li>You have updated FROM_EMAIL_NOTIFY and SUPPORT_EMAIL in <b>`app/Config/constants.php`</b></li>
				
				</ul>
				</div>
			<?php
			}
			?>
		</div>
	</div>
    </div>
    <!-- New user popup ends -->
    <!-- Export csv popup starts -->
    <div class="exportcsv cmn_popup" style="display: none;max-height:570px;overflow-y: auto" id="exporttaskcsv_popup">
	<div class="popup_title">
	    <span><i class="icon-exp"></i><span id="popup_heading">Export Tasks to CSV</span></span>
	    <a href="javascript:jsVoid();" onclick="closePopup();"><div class="fr close_popup">X</div></a>
	</div>
	<div class="popup_form">
	    <div class="loader_dv"><center><img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="Loading..." title="Loading..." /></center></div>
	    <div id="exportcsv_content" style="display: none;"></div>
	</div>
    </div>
    <!-- Export csv popup ends -->
    <!-- Cancel Subscription popup starts -->
    <div class="cancel_sub_popup_content cmn_popup scrollTop" style="display: none;">
	<div class="popup_title">
	    <span><i class="icon-create-proj"></i> Cancel Subscription Information</span>
	    <a href="javascript:jsVoid();" onclick="closePopup();"><div class="fr close_popup">X</div></a>
	</div>
	<div class="popup_form">
	    <div class="loader_dv"><center><img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="Loading..." title="Loading..." /></center></div>
	    <div id="cancel_sub_popup_content" style="display: none;"></div>
	</div>
    </div>
    <!-- Cancel Subscription popup ends -->
    
    <!-- Profile Image popup starts -->
    <div class="prof_img cmn_popup" style="display: none;">
	<div class="popup_title">
	    <span><i class="icon-create-proj"></i>Profile Image</span>
	    <a href="javascript:jsVoid();" onclick="closePopup();"><div class="fr close_popup">X</div></a>
	</div>
	<div class="popup_form popup_form_one">
	    <div id="inner_prof_img">
		<form enctype="multipart/form-data" method="POST" action="<?php echo HTTP_ROOT; ?>users/show_preview_img/" id="file_upload1" class="upload applied file_upload">
		    <table cellpadding="0" cellspacing="0" class="col-lg-12">
		<!--	<tr>
			    <td > -->
				<div class="customfile" id="inputfileid" style="display:none;">                
				    <span aria-hidden="true" class="customfile-button">Browse</span>                
				    <span aria-hidden="true" class="customfile-feedback">Select your profile image...</span>                
				    <input type="file" size="50"  name="data[User][photo]" class="fileupload customfile-input" id="upldphoto" >               
				</div>
			<!--    </td>								
			</tr> -->
			<tr>
			    <td>
				<div>Drag and set the box on the area you want to crop.</div>
				<br/>
				<span id="profLoader" style="display:none">
				    <img src="<?php echo HTTP_IMAGES; ?>images/del.gif" alt="Loading..." width="16" height="16"/>
				</span>
				<div id="up_files_usr" class="up_files"></div>
                                <!--<div id="up_files1" class="up_files"></div>-->
				<input type="hidden" id="imgName" name="data[User][photo]" />
			    </td>
			</tr>
			<tr>
			    <td>									
				<!-- hidden inputs -->
				<input type="hidden" id="x" name="x" />
				<input type="hidden" id="y" name="y" />
				<input type="hidden" id="w" name="w" />
				<input type="hidden" id="h" name="h" />
				<div id="actConfirmbtn" style="display:none;">
				    <button type="button" value="Confirm" class="btn btn_blue file_confirm_btn" onclick="doneCropImage()"><i class="icon-big-tick"></i>Confirm</button>
				    <div id="file_confirm_btn_loader" style="float: left;width: 60px;display:none;"><img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="Loading..." title="Loading..." /></div>
				    <button class="btn btn_grey reset_btn file_confirm_btn" type="button" Onclick="profilePopupCancel();" ><i class="icon-big-cross"></i>Cancel</button>
				</div>
				<div id="inactConfirmbtn" style="display:block;">
				    <button type="button" value="Confirm" class="btn btn_blue btn_impcsv" disabled="disabled" onclick="javascript:void(0);"><i class="icon-big-tick"></i>Confirm</button>
				    <button class="btn btn_grey reset_btn" type="button" Onclick="profilePopupCancel();" ><i class="icon-big-cross"></i>Cancel</button>
				</div>
			    </td>
			</tr>	
		    </table>
		</form>
	    </div>
	</div>
    </div>
    <!-- Profile Image popup ends -->
	
    <!-- Select tabs popup starts -->
    <div class="select_tab cmn_popup" style="display: none;">
	<div class="popup_title">
	    <span><i class="icon-create-proj"></i> Select tabs to enable</span>
	    <a href="javascript:jsVoid();" onclick="closePopup();"><div class="fr close_popup">X</div></a>
	</div>
	<div class="popup_form">
	    <div class="loader_dv"><center><img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="Loading..." title="Loading..." /></center></div>
	    <div id="inner_select_tab"></div>
	</div>
    </div>
    <!-- Select tabs popup ends -->
    
    <!-- Edit Project popup starts -->
    <div class="edt_prj cmn_popup" style="display: none;">
	<div class="popup_title">
	    <span><i class="icon-edit-projct"></i><span id="header_prj"></span></span>
	    <a href="javascript:jsVoid();" onclick="closePopup();"><div class="fr close_popup">X</div></a>
	</div>
	<div class="popup_form">
	    <div class="loader_dv"><center><img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="Loading..." title="Loading..." /></center></div>
	    <div id="inner_prj_edit"></div>
	</div>
    </div>
    <!-- Edit Project popup ends -->
    
    <!-- Remove users from Project popup starts -->
    <div class="rmv_prj_usr cmn_popup" style="display: none;">
	<div class="popup_title pad-10">
	    <div class="hdr-cnt fl">Remove User</div>
		<div class="fl"><img src="<?php echo HTTP_IMAGES; ?>html5/icons/icon_breadcrumbs.png"></div>
		<div id="header_prj_usr_rmv" class="fnt-nrml prj_hd_title fl"></div>
	    <a href="javascript:jsVoid();" onclick="closePopup();"><div class="fr close_popup">X</div></a>
	</div>
	<div class="popup_form">
	    <div class="loader_dv"><center><img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="Loading..." title="Loading..." /></center></div>
	    <div id="remusersrch"  class="col-lg-4 mrt-14 top-10 fr" style="display:none;">
		<?php echo $this->Form->text('name', array('class' => 'form-control pro_srch', 'id' => 'rmname', 'maxlength' => '100', 'onkeyup' => "searchListWithInt('searchuserrem',600)", 'placeholder' => 'Enter User Name')); ?>
		<i class="icon-srch-img chng_icn"></i>
	    </div>
	    <span id="popupload2" class="usr-srh" style="display: none">Loading users... <img src="<?php echo HTTP_IMAGES;?>images/del.gif" title="Loading..." alt="Loading..."/></span>
	    <div class="cb"></div>
	    <div id="inner_prj_usr_rmv"></div>
	    
	    <div class="rmv-btn">
		<center>
		<span id="rmvloader" class="ldr-ad-btn">
		    <img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="loading..." title="loading..."/> 
		</span>
		<span id="rmv_btn">
		    <button class="btn btn_blue" id="rmvbtn"  value="Remove" type="button" onclick="removeusers()"><i class="icon-big-tick"></i>Remove</button>
		    <!--<button class="btn btn_grey" type="button" onclick="closePopup();"><i class="icon-big-cross"></i>Cancel</button>-->
            <span class="or_cancel">or<a onclick="closePopup();">Cancel</a></span>
		</span>
		</center>
	    </div>
	</div>
    </div>
    <!-- Remove users from Project popup ends -->
    
    <!-- Add users from Project popup starts -->
    <div class="add_prj_usr cmn_popup" style="display: none;">
	<div class="popup_title pad-10">
	    <div class="hdr-cnt fl">Add User </div>
		<div class="fl"><img src="<?php echo HTTP_IMAGES; ?>html5/icons/icon_breadcrumbs.png"></div>
		<div id="header_prj_usr_add" class="fnt-nrml fl prj_hd_title"></div>

	    <a href="javascript:jsVoid();" onclick="closePopup();"><div class="fr close_popup">X</div></a>
        <?php
		if(SES_TYPE != 3) {
		?>
	    <a id="invite_usr" class="dropdown-toggle upgrade_btn" onclick="newUser();" href="javascript:jsVoid();">
		<button class="btn blue_btn blue_btn_lrg fr mrt-10" type="button">
		    <i class="icon-invite-usr"></i>
		    Invite New User
		</button>
	    </a>
        <?php
		}
		?>
	</div>
	<div class="popup_form">
	    <div class="loader_dv"><center><img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="Loading..." title="Loading..." /></center></div>
            <input type='hidden' name='prjct_id' id='prjct_id' >
            <input type='hidden' name='prjct_name' id='prjct_name'>      
	    <div class="fl" style="overflow: auto; max-height: 90px; width: 576px;">
	  	<ul id="userList" class="holder" style="border:1px solid #FAFAFA">
	    	</ul>
	    </div>
	    <div id="usersrch"  class="col-lg-4 mrt-14 fr" style="display:none;">
		<?php echo $this->Form->text('name', array('class' => 'form-control pro_srch', 'id' => 'name', 'maxlength' => '100', 'onkeyup' => "searchListWithInt('searchuser',600)", 'placeholder' => 'Enter User Name')); ?>
		<i class="icon-srch-img chng_icn"></i>
	    </div>
	    <span id="popupload1" class="usr-srh">Loading users... <img src="<?php echo HTTP_IMAGES;?>images/del.gif" title="Loading..." alt="Loading..."/></span>
	    <div class="cb"></div>
	    <div id="inner_prj_usr_add"></div>
	    
	    <div class="add-usr-btn" style="display: none;">
		<span id="userloader" style="display: none;">
		    <img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="loading..." title="loading..."/> 
		</span>
		<span id="popupload" class="ldr-ad-btn">Loading users... <img src="<?php echo HTTP_IMAGES;?>images/case_loader2.gif" title="Loading..." alt="Loading..."/></span>
		<span id="confirmbtn" style="display:block;">
		    <button class="btn btn_blue" id="confirmusercls" value="Confirm" type="button" onclick="assignuser(this)"><i class="icon-big-tick"></i>Add</button>
		    <button class="btn btn_blue" id="confirmuserbut" value="Confirm" type="button" onclick="assignuser(this)"><i class="icon-big-tick"></i>Add & Continue</button>
		    <!--<button class="btn btn_grey" type="button" onclick="closePopup();"><i class="icon-big-cross"></i>Cancel</button>-->
                    <span class="or_cancel">or
                        <a onclick="closePopup();">Cancel</a>
                    </span>
		</span>
		
		<span id="excptAddContinue" style="display:none;">
		    <button class="btn btn_blue" id="confirmusercls"  value="Confirm" type="button" onclick="assignuser(this)"><i class="icon-big-tick"></i>Add</button>
		    <!--<button class="btn btn_grey" type="button" onclick="closePopup();"><i class="icon-big-cross"></i>Cancel</button>-->
                    <span class="or_cancel">or
                        <a onclick="closePopup();">Cancel</a>
                    </span>
		</span>
	    </div>
	</div>
    </div>
    <!-- Add users from Project popup ends -->
    
    
    <!-- Add project to a user popup starts -->
    <div class="add_usr_prj cmn_popup" style="display: none;">
	<div class="popup_title pad-10">
	    <span class="hdr-cnt">Assign Project
		<span><img src="<?php echo HTTP_IMAGES; ?>html5/icons/icon_breadcrumbs.png"></span>
		<span id="header_usr_prj_add" class="fnt-nrml"></span>
	    </span>
	    <a href="javascript:jsVoid();" onclick="closePopup();"><div class="fr close_popup">X</div></a>
	    
	    <a id="creat_pro" class="dropdown-toggle upgrade_btn" onclick="newProject();" href="javascript:jsVoid();">
		<button class="btn blue_btn blue_btn_lrg fr mrt-10" type="button">
		    <i class="icon-invite-proj"></i>
		    Create New Project
		</button>
	    </a>
	    
	</div>
	<div class="popup_form">
	    <div class="loader_dv"><center><img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="Loading..." title="Loading..." /></center></div>
	    
	    <div class="fl" style="overflow: auto; max-height: 90px; width: 576px;">
	  	<ul id="prjList" class="holder" style="border:1px solid #FAFAFA">
	    	</ul>
	    </div>
	    <div id="prjsrch"  class="col-lg-4 mrt-14 fr" style="display:none;">
			<?php echo $this->Form->text('name', array('class' => 'form-control pro_srch', 'id' => 'proj_name', 'maxlength' => '100', 'onkeyup' => "searchListWithInt('searchproj',600)", 'placeholder' => 'Enter Project Name')); ?>
			<i class="icon-srch-img chng_icn"></i>
	    </div>
	    <div class="cb"></div>
	    <span id="prjpopupload1" class="usr-srh">Loading projects... <img src="<?php echo HTTP_IMAGES;?>images/del.gif" title="Loading..." alt="Loading..."/></span>
	    <div class="cb"></div>
	    <div id="inner_usr_prj_add"></div>
	    
	    <div class="add-prj-btn" style="display: none;">
		<span id="prjloader" class="ldr-ad-btn">
		    <img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="loading..." title="loading..."/> 
		</span>
		<span id="prjpopupload" class="ldr-ad-btn">Loading projects... <img src="<?php echo HTTP_IMAGES;?>images/case_loader2.gif" title="Loading..." alt="Loading..."/></span>
		<span id="confirmbtnprj" style="display:block;">
		    <button class="btn btn_blue" id="confirmprjcls" value="Confirm" type="button" onclick="assignproject(this)"><i class="icon-big-tick"></i>Assign</button>
		    <button class="btn btn_blue" id="confirmprjbut" value="Confirm" type="button" onclick="assignproject(this)"><i class="icon-big-tick"></i>Assign & Continue</button>
		    <!--<button class="btn btn_grey" type="button" onclick="closePopup();"><i class="icon-big-cross"></i>Cancel</button>-->
            <span class="or_cancel">or<a onclick="closePopup();">Cancel</a></span>
		</span>
	    </div>
	</div>
    </div>
    <!-- Add project to a user popup ends -->
    
    <!-- Remove projects of a user popup starts -->
    <div class="rmv_usr_prj cmn_popup" style="display: none;">
	<div class="popup_title pad-10">
	    <span class="hdr-cnt">Remove Project
		<span><img src="<?php echo HTTP_IMAGES; ?>html5/icons/icon_breadcrumbs.png"></span>
		<span id="header_usr_prj_rmv" class="fnt-nrml"></span>
	    </span>
	    <a href="javascript:jsVoid();" onclick="closePopup();"><div class="fr close_popup">X</div></a>
	</div>
	<div class="popup_form">
	    <div class="loader_dv"><center><img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="Loading..." title="Loading..." /></center></div>
	    <div id="remprjsrch"  class="col-lg-4 mrt-14 top-10 fr" style="display:none;">
		<?php echo $this->Form->text('name', array('class' => 'form-control pro_srch', 'id' => 'rmprjname', 'maxlength' => '100', 'onkeyup' => "searchListWithInt('searchprojrem',600)", 'placeholder' => 'Enter Project Name')); ?>
		<i class="icon-srch-img chng_icn"></i>
	    </div>
	    <span id="rempopupload" class="usr-srh" style="display: none">Loading projects... <img src="<?php echo HTTP_IMAGES;?>images/del.gif" title="Loading..." alt="Loading..."/></span>
	    <div class="cb"></div>
	    <div id="inner_usr_prj_rmv"></div>
	    
	    <div class="rmv-prj-btn">
		<center>
		<span id="rmvprjloader" class="ldr-ad-btn">
		    <img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="loading..." title="loading..."/> 
		</span>
		<span id="rmv_prj_btn">
		    <button class="btn btn_blue" id="rmvprjbtn"  value="Remove" type="button" onclick="removeprojects()"><i class="icon-big-tick"></i>Remove</button>
		    <!--<button class="btn btn_grey" type="button" onclick="closePopup();"><i class="icon-big-cross"></i>Cancel</button>-->
            <span class="or_cancel">or<a onclick="closePopup();">Cancel</a></span>
		</span>
		</center>
	    </div>
	</div>
    </div>
    <!-- Remove projects of a user popup ends -->
    <!-- Add or Edit Milestone popup starts -->
    <div class="mlstn cmn_popup" style="display: none;">
	<div class="popup_title">
	    <span><i id="icon_mlstn" class="icon-create-proj"></i><div id="header_mlstn" class="fl mlstn_nm_long">Create Milestone</div></span>
	    <a href="javascript:jsVoid();" onclick="closePopup();"><div class="fr close_popup">X</div></a>
	</div>
	<div class="popup_form">
		<div class="loader_dv" id="addeditMlst"><center><img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="Loading..." title="Loading..." /></center></div>
	    <div id="inner_mlstn" class="mils_ipad"></div>
	</div>
    </div>
    <!-- Add or Edit Milestone popup ends -->
    
    <!-- Add cases to Milestone popup ends -->
    <div class="mlstn_case cmn_popup" style="display: none;">
	<div class="popup_title pad-10">
	    <div class="hdr-cnt">
		<div class="fl">&nbsp;Add Tasks&nbsp;</div>
		<div class="fl">&nbsp;<img src="<?php echo HTTP_IMAGES; ?>html5/icons/icon_breadcrumbs.png">&nbsp;</div>
		<div id="header_prj_ttl" class="fl fnt-nrml"></div>
		<div class="fl">&nbsp;<img src="<?php echo HTTP_IMAGES; ?>html5/icons/icon_breadcrumbs.png">&nbsp;</div>
		<div id="header_mlstn_ttl" class="fl fnt-nrml ttc adtskmlstn_ttl"></div>
		
		<div class="fl" style="position: relative;">&nbsp;&nbsp;
		<a href="javascript:void(0);"><span id="switch_mlstn" style="font-size: 12px;text-decoration:underline;" onclick="view_project_milestone();">Switch Milestone</span></a>
		<ol style="list-style-type: none;">
		    <li class="dropdown" id="mlstn_drpdwn" style="position: absolute; top: 7px;left: 13px;">
				<div class="dropdown-menu lft popup" id="mlstnpopup" style="left: 0px;left: 0px;min-height: 30px; height:auto;">
			    <center>
				<div id="loader_mlsmenu" style="display:none;position: absolute;left: 93px;">
				    <img src="<?php echo HTTP_IMAGES; ?>images/del.gif" alt="loading..." title="loading..."/>
				</div>
			    </center>
			    <div id="ajaxViewMilestonesCP"></div>
			</div>
		    </li>
		</ol>
		</div>
		<div class="cb"></div>
	    </div>
	    <a href="javascript:jsVoid();" onclick="closePopup();"><div class="fr close_popup" style="margin-top: -36px;">X</div></a>
	</div>
	<div class="popup_form" style="position: relative;">
	    <div class="loader_dv" style="position: absolute; top: 7px;left: 385px;width:33px"><center><img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="Loading..." title="Loading..." /></center></div>
	    
	    <div id="tsksrch"  class="col-lg-4 mrt-14 srchmile_spc fr" style="display:none;">
		<?php echo $this->Form->text('name', array('class' => 'form-control pro_srch', 'id' => 'tsk_name', 'maxlength' => '100', 'onkeyup' => 'searchMilestoneCase()', 'placeholder' => 'Title')); ?>
		<i class="icon-srch-img chng_icn"></i>
	    </div>
	    <span id="tskpopupload1" class="mlstn-srh-ldr">Loading tasks... <img src="<?php echo HTTP_IMAGES;?>images/del.gif" title="Loading..." alt="Loading..."/></span>
	    <div class="cb"></div>
	    <div id="inner_mlstn_case"></div>
	    
	    <div class="add-mlstn-btn" style="display: none;">
		<center>
		<span id="tskloader" style="display: none;">
		    <img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="loading..." title="loading..."/> 
		</span>
		<span id="confirmbtntsk" style="display:block;">
		    <button class="btn btn_blue showhidebtn" id="addtsk" value="Add" type="button" onclick="assignCaseToMilestone(this)"><i class="icon-big-tick"></i>Add</button>
		    <button class="btn btn_blue showhidebtn" id="addtskncont" value="Add" type="button" onclick="assignCaseToMilestone(this)"><i class="icon-big-tick"></i>Add & Continue</button>
		    <!--<button class="btn btn_grey" type="button" onclick="closePopup();"><i class="icon-big-cross"></i>Cancel</button>-->
            <span class="or_cancel">
                or
                <a onclick="closePopup();">Cancel</a>
            </span>
		</span>
		</center>
	    </div>
	</div>
    </div>
    <!-- Add cases to Milestone popup end -->
	
	<!-- Move Task To Milestone popup Start -->
    <div class="movetaskTomlst cmn_popup" style="display: none;">
	<div class="popup_title pad-10">
	    <div class="hdr-cnt">
			<div class="fl">&nbsp;Move task to milestone&nbsp;</div>
			<div class="fl">&nbsp;<img src="<?php echo HTTP_IMAGES; ?>html5/icons/icon_breadcrumbs.png">&nbsp;</div>
			<div  id="mvtask_prj_ttl" class="fnt-nrml fl"></div>
			<div class="cb"></div>
	    </div>
	    <a href="javascript:jsVoid();" onclick="closePopup();"><div class="fr close_popup" style="margin-top: -20px;">X</div></a>
	</div>
	<div class="popup_form">
	    <div class="loader_dv" id="mvtask_loader"><center><img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="Loading..." title="Loading..." /></center></div>
	    <div id="mvtask_mlst"></div>
	    <div class="add-mlstn-btn" style="display: none;">
		<center>
			<span id="tskloader" style="display: none;">
				<img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="loading..." title="loading..."/> 
			</span>
			<span id="mvtask_confirmbtn" style="display:block;">
				<button class="btn btn_blue" id="mvtask_movebtn" value="Add" type="button" onclick="switchTaskToMilestone(this)"><i class="icon-big-tick"></i>Move Task</button>
				<!--<button class="btn btn_grey" type="button" onclick="closePopup();"><i class="icon-big-cross"></i>Cancel</button>-->
                <span class="or_cancel">or<a onclick="closePopup();">Cancel</a></span>
			</span>
			</center>
	    </div>
	</div>
    </div>
    <!-- Move Task To Milestone popup end -->
	
    <!-- Remove cases From Milestone popup starts -->
    <div class="mlstn_remove_task cmn_popup" style="display: none;">
	<div class="popup_title pad-10">
	    <div class="hdr-cnt">
		<div class="fl">&nbsp;Remove Tasks&nbsp;</div>
		<div class="fl">&nbsp;<img src="<?php echo HTTP_IMAGES; ?>html5/icons/icon_breadcrumbs.png">&nbsp;</div>
		<div  id="header_prj_ttl_rt" class="fnt-nrml fl"></div>
		<div class="fl">&nbsp;<img src="<?php echo HTTP_IMAGES; ?>html5/icons/icon_breadcrumbs.png">&nbsp;</div>
		<div id="header_mlstn_ttl_rt" class="fnt-nrml ttc fl"></div>
		
<!--		<div class="fl" style="position: relative;">&nbsp;&nbsp;
		<a href="javascript:void(0);"><span id="switch_mlstn" style="font-size: 12px;text-decoration:underline;" onclick="view_project_milestone();">Switch Milestone</span></a>
		<ol style="list-style-type: none;">
		    <li class="dropdown" id="mlstn_drpdwn" style="position: absolute; top: 7px;left: 13px;">
				<div class="dropdown-menu lft popup" id="mlstnpopup" style="left: 0px;">
			    <center>
				<div id="loader_mlsmenu" style="display:none;">
				    <img src="<?php echo HTTP_IMAGES; ?>images/del.gif" alt="loading..." title="loading..."/>
				</div>
			    </center>
			    <div id="ajaxViewMilestonesCP"></div>
			</div>
		    </li>
		</ol>
		</div>-->
		<div class="cb"></div>
	    </div>
	    <a href="javascript:jsVoid();" onclick="closePopup();"><div class="fr close_popup" style="margin-top: -20px;">X</div></a>
	</div>
	<div class="popup_form">
	    <div class="loader_dv" id="rmv_case_loader"><center><img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="Loading..." title="Loading..." /></center></div>
	    
	    <div id="tsksrch"  class="col-lg-4 mrt-14 fr" style="display:none;">
		<?php echo $this->Form->text('name', array('class' => 'form-control pro_srch', 'id' => 'tsk_name', 'maxlength' => '100', 'onkeyup' => 'searchMilestoneCase()', 'placeholder' => 'Title')); ?>
		<i class="icon-srch-img chng_icn"></i>
	    </div>
	    
	    <span id="tskpopupload1" class="mlstn-srh-ldr">Loading tasks... <img src="<?php echo HTTP_IMAGES;?>images/del.gif" title="Loading..." alt="Loading..."/></span>
	    <div class="cb"></div>
	    <div id="inner_mlstn_removetask"></div>
	    
	    <div class="add-mlstn-btn" style="display: none;">
			<center>
				<span id="tskloader" style="display: none;">
					<img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="loading..." title="loading..."/> 
				</span>
				<span id="confirmbtntsk" style="display:block;">
					<button class="btn btn_blue" id="addtsk" value="Add" type="button" onclick=" return removecaseFromMilestone(this)"><i class="icon-big-tick"></i>Remove</button>
					<!--<button class="btn btn_blue" id="addtskncont" value="Add" type="button" onclick="assignCaseToMilestone(this)"><i class="icon-big-tick"></i>Add & Continue</button>-->
					<!--<button class="btn btn_grey" type="button" onclick="closePopup();"><i class="icon-big-cross"></i>Cancel</button>-->
                    <span class="or_cancel">or<a onclick="closePopup();">Cancel</a></span>
				</span>
			</center>
	    </div>
	</div>
    </div>
    <!-- Add cases to Milestone popup ends -->
    
     <!-- Support popup starts -->
     <?php
     $usrArr = $this->Format->getUserDtls(SES_ID);
     if (count($usrArr)) {
	 $ses_name = $usrArr['User']['name'];
	 $ses_email = $usrArr['User']['email'];
	 $ses_last_name = $usrArr['User']['last_name'];
     }
     ?>
    <div class="support_popup cmn_popup" style="display: none;">
	<div class="popup_title">
		<span>
			<i class="icon-support"></i> <span class="support_title">Feedback</span>
		</span>
	    <a href="javascript:jsVoid();" onclick="closePopup();"><div class="fr close_popup">X</div></a>
	</div>
	<div class="popup_form">
	    <div class="cb"></div>
	    <div id="inner_support">
		<center><div id="support_err" class="fnt_clr_rd" style="display:block;"></div></center>
		<table cellpadding="0" cellspacing="0" class="col-lg-12 new_auto_tab">
		    <tr>
			<td class="v-top">Name:</td>
			<td>
			    <input type="text" name="support_name" id="support_name" class="form-control" value= "<?php echo $ses_name.' '.$ses_last_name; ?>" />
			</td>
		    </tr>
		    <tr>
			<td class="v-top">Email:</td>
			<td>
			    <input type="text" name="support_email" id="support_email" readOnly="readOnly" class="form-control"  value= "<?php echo $ses_email; ?>" />
			</td>
		    </tr>
		    <tr>
			<td class="v-top">Message:</td>
			<td>
				<textarea name="support_msg" id="support_msg" class="form-control" cols="23" rows="4"></textarea>
			</td>
		    </tr>
		    <tr>
			<td></td>
			<td class="btn_align">
			    <span id="sprtloader" class="ldr-ad-btn">
				<img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="loading..." title="loading..."/> 
			    </span>
			    <span id="spt_btn">
				<button class="btn btn_blue" type="button" value="Post" name="addMember" onclick="postSupport();action_ga('Feedback Post');"><i class="icon-big-tick"></i>Post</button>
				<!--<button class="btn btn_grey" type="button" onclick="closePopup();"><i class="icon-big-cross"></i>Cancel</button>-->
                <span class="or_cancel">
                    or
                    <a onclick="closePopup();">Cancel</a>
                </span>
			    </span>
			</td>
		    </tr>
	
		    <input type="hidden"  name="url_sendding" id="url_sendding" />
		</table>
	    </div>
	</div>
    </div>
    <!-- Support popup ends -->
    
    
    
    <!-- Create task popup starts -->
    
    <!-- Create task popup ends -->    
     <!-- <div id="template_mod_cases" style="position:fixed;left:0;top:0px;width:100%;position: absolute;background: white;" class="inner"></div>-->
	 
	 <!-- Task template create popup starts -->
    <div class="task_temp_popup cmn_popup" style="display: none;">
		<div class="popup_title">
			<span>
				<i class="icon-pro-templt"></i> Task Template
			</span>
			<a href="javascript:jsVoid();" onclick="closePopup();"><div class="fr close_popup">X</div></a>
		</div>
		<div class="popup_form">
			<div class="cb"></div>
			<div id="inner_task_temp">
				<center><div id="task_temp_err" class="fnt_clr_rd" style="display:block;font-size:15px;"></div></center>
				<table cellpadding="0" cellspacing="0" class="col-lg-12 new_auto_tab">
					<tr>
					<td>Title:</td>
					<td>
						<input type="text" name="tasktemptitle" id="tasktemptitle" class="form-control" value= "" />
					</td>
					</tr>
					<tr>
					<td valign="top" style="padding-left:33px;">Description:</td>
					<td>
						<textarea class="text_field form-control" id="desc" name="desc"></textarea>
					</td>
					</tr>
					<tr>
					<td></td>
					<td class="btn_align">
						<span id="tasktemploader" class="ldr-ad-btn">
							<img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="loading..." title="loading..."/> 
						</span>
						<span id="task_btn">
							<button class="btn btn_blue" type="button" onclick="createTaskTemplate('add')"><i class="icon-big-tick"></i>Create</button>
							<!--<button class="btn btn_grey" type="button" onclick="closePopup();"><i class="icon-big-cross"></i>Cancel</button>-->
                            <span class="or_cancel">or<a onclick="closePopup();">Cancel</a></span>
						</span>
					</td>
					</tr>
				</table>
			</div>
		</div>
    </div>
    <!-- Task template create popup ends --> 
	
	<!-- Edit Task Template popup starts -->
    <div class="edt_task_temp cmn_popup" style="display: none;">
	<div class="popup_title">
	    <span><i class="icon-edit-projct"></i><span id="header_task_temp"></span></span>
	    <a href="javascript:jsVoid();" onclick="closePopup();"><div class="fr close_popup">X</div></a>
	</div>
	<div class="popup_form">
	    <div class="loader_dv_task"><center><img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="Loading..." title="Loading..." /></center></div>
	    <div id="inner_task_temp_edit">
			<center><div id="task_temp_err" class="fnt_clr_rd" style="display:block;font-size:15px;"></div></center>
			<table cellpadding="0" cellspacing="0" class="col-lg-12 new_auto_tab">
				<input type="hidden" name="hid_edit_id" id="hid_edit_id" value="" />
				<input type="hidden" name="hid_edit_user_id" id="hid_edit_user_id" value="" />
				<input type="hidden" name="hid_edit_company_id" id="hid_edit_company_id" value="" />
				<input type="hidden" name="hid_edit_page_num" id="hid_edit_page_num" value="" />
				<tr>
				<td>Title:</td>
				<td>
					<input type="text" name="tasktemptitle_edit" id="tasktemptitle_edit" class="form-control" value= "" />
				</td>
				</tr>
				<tr>
				<td valign="top" style="padding-left:33px;">Description:</td>
				<td>
					<textarea class="text_field form-control" id="desc_edit" name="desc_edit"></textarea>
				</td>
				</tr>
				<tr>
				<td></td>
				<td class="btn_align">
					<span id="tasktemploader_edit" class="ldr-ad-btn">
						<img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="loading..." title="loading..."/> 
					</span>
					<span id="task_btn_edit">
						<button class="btn btn_blue" type="button" onclick="createTaskTemplate('edit')"><i class="icon-big-tick"></i>Update</button>
						<!--<button class="btn btn_grey" type="button" onclick="closePopup();"><i class="icon-big-cross"></i>Cancel</button>-->
                        <span class="or_cancel">or<a onclick="closePopup();">Cancel</a></span>
					</span>
				</td>
				</tr>
			</table>
		</div>
	</div>
    </div>
    <!-- Edit Task Template popup ends -->
	 
	<!-- project template create popup starts -->
    <div class="project_temp_popup cmn_popup" style="display: none;">
		<div class="popup_title">
			<span>
				<i class="icon-pro-templt"></i> Project Template
			</span>
			<a href="javascript:jsVoid();" onclick="closePopup();"><div class="fr close_popup">X</div></a>
		</div>
		<div class="popup_form">
			<div class="cb"></div>
			<div id="inner_project_temp">
			<center><div id="project_temp_err" class="fnt_clr_rd" style="display:block;"></div></center>
				<table cellpadding="0" cellspacing="0" class="col-lg-12 new_auto_tab" style="width:100%">
					<tr>
					<td style="width:0px;">Template Name:</td>
					<td style="width:220px;">
						<input type="text" name="projtemptitle" id="projtemptitle" class="form-control" value= "" />
					</td>
					</tr>
					<tr>
					<td></td>
					<td class="btn_align">
						<span id="prjtemploader" class="ldr-ad-btn">
							<img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="loading..." title="loading..."/> 
						</span>
						<span id="prj_btn">
							<button class="btn btn_blue" type="button" onclick="createTemplate()"><i class="icon-big-tick"></i>Create</button>
							<button class="btn btn_grey" type="button" onclick="closePopup();"><i class="icon-big-cross"></i>Cancel</button>
						</span>
					</td>
					</tr>
				</table>
			</div>
		</div>
    </div>
    <!-- project template create popup ends --> 
	
	<!-- tasks of project template Edit popup starts -->
    <div class="task_project_edit cmn_popup" style="display: none;">
		<div class="popup_title">
			<span><i class="icon-edit-projct"></i><span id="header_task_prj"></span></span>
			<a href="javascript:jsVoid();" onclick="closePopupEdit();"><div class="fr close_popup">X</div></a>
		</div>
		<div class="popup_form">
			<div class="cb"></div>
			<div class="loader_dv_tsk_prj"><center><img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="Loading..." title="Loading..." /></center></div>
			<div id="inner_task_project_edit">
			<center><div id="task_project_err_edit" class="fnt_clr_rd" style="display:block;"></div></center>
			<form name="templatecase" method="post" action="<?php echo HTTP_ROOT."templates/edit_template_task"; ?>" onsubmit="return validateTaskTemplateEdit()">
				<input type="hidden" name="template_id" id="temp_id" value="" />
				<table cellpadding="0" cellspacing="0" class="col-lg-12 new_auto_tab" style="width:100%;">
					<tr>
						<td align="left" style="width:82%;padding-right:0;">
							<table cellpadding="0" cellspacing="0" class="col-lg-12 new_auto_tab" style="width:100%;margin-left:5px;">
								<tr>
									<td>Task Title:</td>
									<td style="padding-right:0px;">
										<input type="text" name="title_edit" id="title_edit" class="form-control" value= "" style="width:415px;" />
									</td>
								</tr>
								<tr>
									<td valign="top">Description:</td>
									<td style="padding-right:0px;">
										<textarea name="description_edit" id="description_edit" class="form-control" style="width:415px"></textarea>
									</td>
								</tr>
								<tr>
									<td>&nbsp;</td>
									<td class="btn_align">
										<span id="prjtemploader_task_prj" style="display:none;">
											<img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="loading..." title="loading..."/> 
										</span>
										<span id="prj_btn_task_edit">
											<button class="btn btn_blue" name="submit_template_edit" value="1" type="submit"><i class="icon-big-tick"></i>Update</button>
											<button class="btn btn_grey" type="button" onclick="closePopupEdit();"><i class="icon-big-cross"></i>Cancel</button>
										</span>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</form>	
			</div>
		</div>
    </div>
    <!-- tasks of project template Edit popup ends --> 
	
	<!-- project template Edit popup starts -->
    <div class="project_temp_popup_edit cmn_popup" style="display: none;">
		<div class="popup_title">
			<span><i class="icon-edit-projct"></i><span id="header_prj_task_temp"></span></span>
			<!--<span>
				<i class="icon-pro-templt"></i> Project Template
			</span>-->
			<a href="javascript:jsVoid();" onclick="closePopup();"><div class="fr close_popup">X</div></a>
		</div>
		<div class="popup_form">
			<div class="cb"></div>
			<div class="loader_dv_prj"><center><img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="Loading..." title="Loading..." /></center></div>
			<div id="inner_project_temp_edit">
			<center><div id="project_temp_err_edit" class="fnt_clr_rd" style="display:block;"></div></center>
				<table cellpadding="0" cellspacing="0" class="col-lg-12 new_auto_tab" style="width:100%">
					<tr>
					<td style="width:0px;">Template Name:</td>
					<td style="width:220px;">
						<input type="text" name="projtemptitle_edit" id="projtemptitle_edit" class="form-control" value= "" />
						<input type="hidden" name="hid_orig_projtemptitle_edit" id="hid_orig_projtemptitle_edit" class="form-control" value= "" />
						<input type="hidden" name="hid_temptitle_id" id="hid_temptitle_id" class="form-control" value= "" />
						<input type="hidden" name="hid_page_num" id="hid_page_num" class="form-control" value= "" />
					</td>
					</tr>
					<tr>
					<td></td>
					<td class="btn_align">
						<span id="prjtemploader_edit" style="display:none;">
							<img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="loading..." title="loading..."/> 
						</span>
						<span id="prj_btn_edit">
							<button class="btn btn_blue" type="button" onclick="save_edit_template()"><i class="icon-big-tick"></i>Update</button>
							<button class="btn btn_grey" type="button" onclick="closePopup();"><i class="icon-big-cross"></i>Cancel</button>
						</span>
					</td>
					</tr>
				</table>
			</div>
		</div>
    </div>
    <!-- project template Edit popup ends --> 
	
	<!-- Add tasks to Project popup starts -->
    <div class="add_to_project cmn_popup" style="display: none;">
		<div class="popup_title pad-10">
			<span class="hdr-cnt add_prod_temp_name">
				
			</span>
			<a href="javascript:jsVoid();" onclick="closePopup();"><div class="fr close_popup">X</div></a>
		</div>
		<div class="popup_form">
			<div class="loader_dv"><center><img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="Loading..." title="Loading..." /></center></div>
			<div class="cb"></div>
			<div id="inner_tmp_add"></div>
			
			<div class="add-tmp-btn" style="display: none;">
			<span id="userloader" class="ldr-ad-btn">
				<img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="loading..." title="loading..."/> 
			</span>
			
			</div>
		</div>
    </div>
    <!-- Add tasks to Project popup ends -->
	
	<!-- Create task to template popup starts -->
    <div class="add_task_to_temp cmn_popup" style="display: none;">
		<div class="popup_title pad-10">
			<span class="hdr-cnt add_task_temp_name">
				
			</span>
			<a href="javascript:jsVoid();" onclick="closePopup();"><div class="fr close_popup">X</div></a>
		</div>
		<div class="popup_form">
			<div class="loader_dv"><center><img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="Loading..." title="Loading..." /></center></div>
			<div class="cb"></div>
			<center><div id="task_to_temp_err" class="fnt_clr_rd" style="display:block;"></div></center>
			<div id="inner_task_add"></div>
			
			<div class="add-task-btn" style="display: none;">
			<span id="userloader" class="ldr-ad-btn">
				<img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="loading..." title="loading..."/> 
			</span>
			
			</div>
		</div>
    </div>
    <!-- Create task to template popup ends -->
	 
	<!-- Remove tasks from Template popup starts -->
    <div class="remove_from_task cmn_popup" style="display: none;">
		<div class="popup_title pad-10">
			<span class="hdr-cnt proj_temp_name">
				
			</span>
			<a href="javascript:jsVoid();" onclick="closePopup();"><div class="fr close_popup">X</div></a>
		</div>
		<div class="popup_form">
			<div class="loader_dv"><center><img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="Loading..." title="Loading..." /></center></div>
			<div class="cb"></div>
			<div id="inner_tasks"></div>
			
			<div class="add-remove-btn" style="display: none;">
			<span id="removetaskloader" class="ldr-ad-btn">
				<img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="loading..." title="loading..."/> 
			</span>
			
			</div>
		</div>
    </div>
    <!-- Remove tasks from Template popup ends --> 
	 
	<?php if((SES_TYPE == 1 || SES_TYPE == 2) && defined('TWITTED') && TWITTED == 0 && !in_array(PAGE_NAME,array('onbording','profile','changepassword','email_notifications','email_reports'))){
		$osTwAccount = 'TheOrangescrum';
		$tweetTexts = array(
			'#Orangescrum is an Awesome Project Collaboration Tool that gives full visibility and control over your projects',
			'Organize Projects, Tasks, Documents & Meeting Minutes in one place #Orangescrum'.' @'.$osTwAccount,
			'Share of your ideas, feedbacks, questions and discussions across the team #Orangescrum'.' @'.$osTwAccount,
			'See what\'s in progress, what needs to be done and what\'s been accomplished #Orangescrum'.' @'.$osTwAccount,
			'Receive reminders, alert for close deadlines, manage tightly not to exceed budget #Orangescrum'.' @'.$osTwAccount,
			'Break-down tasks into smaller ones, share documents using google Drive & Dropbox #Orangescrum'.' @'.$osTwAccount,
			'Just sit back and keep on watching the Activity even while relaxing #Orangescrum'.' @'.$osTwAccount,
			'Win your customers\'confidence by keeping them informed with daily scrum #Orangescrum'.' @'.$osTwAccount,
			'Keep your team on their toes by reminding them by automatic emails #Orangescrum'.' @'.$osTwAccount,
			'Get instant notification on your cell and respond with your inputs in no time #Orangescrum'.' @'.$osTwAccount,
			'Stay on top and get weekly usage report #Orangescrum'.' @'.$osTwAccount,
			'Managing Project Effectively with project collaboration tool taming inbox #Orangescrum'.' @'.$osTwAccount,
			'Get Daily Progress email from team without fail #Orangescrum'.' @'.$osTwAccount,
			'#Orangescrum is a Awesome project management tool for You & Your Team'.' @'.$osTwAccount
		);
		if($user_subscription['is_free']==1 || ($user_subscription['project_limit'] == 'Unlimited' && $user_subscription['storage'] == 'Unlimited')){
			$twHead = 'Tweet about us!';
			$twBody = 'Tweet about  your favourite project management tool and help us grow.';
		} else {
			$twHead = 'Tweet and get more Project and Storage';
			$twBody = 'Tweet about us and get 1 more Project and 30 MB more Storage.';
		}
		$twStr = http_build_query(array('url' => HTTP_HOME, 'text' => $tweetTexts[array_rand($tweetTexts)], 'related' => $osTwAccount ), '', '&amp;');
	?>
	<!-- Tweet popup starts -->
    <div class="tweet_popup cmn_popup" style="display: none;">
	<div class="popup_title">
	    <span><i class="icon-tweet"></i> <?php echo $twHead; ?></span>
	    <a href="javascript:jsVoid();" onclick="closePopup();"><div class="fr close_popup">X</div></a>
	</div>
	<div class="popup_form">
	    <div class="loader_dv"><center><img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="Loading..." title="Loading..." /></center></div>
	    <div class="help-text"> 
			<center><?php echo $twBody; ?></center>
		</div>
		<div class="hlpe_popbtn">
			<a href="https://twitter.com/intent/tweet?<?php echo $twStr; ?>" target="_blank">
				<button class="btn btn_blue" type="button" onclick="trackEventGoogle('Tweet and share', 'Tweet and share', 'Clicked For Tweet - Let me tweet');">
					<!--<i class="icon-tweet"></i>-->
					Let me tweet!
				</button>
			</a>
			<button class="btn btn_grey" type="button" onclick="closePopup();trackEventGoogle('Tweet and share', 'Tweet and share', 'Clicked For Tweet - No thanks');"><i class="icon-big-cross"></i>No thanks</button>
		</div>
	</div>
    </div>
    <!-- Tweet popup ends -->
	<?php } ?>
</div>

<div id="create_task_pop" class="crt_tsk cmn_popup1 crt_task_slide">
	<div class="popup_form1">
		
	    <div id="inner_task">
			<div class="task_slide_in">
				<span>
				<i class="icon-create-tsk" id="ctask_icons"></i>
				<span id="taskheading">Create</span> Task
				</span>
			</div>
			<div class="cb"></div>
			<div class="loader_dv_edit" style="display: none;"><center><img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="Loading..." title="Loading..." /></center></div>
			<table class="create_table" style="width:75%">
				<tr>
					<td>
						<input type="hidden" name="data[Easycase][istype]" id="CS_istype" value="1" readonly="true"/>
						<div class="fl lbl-m-wid">Project:</div>
						<div class="col-lg-9 createtask fl rht-con">
							<div style="font-weight: bold;" id="edit_project_div" class="ttc"></div>
							<div id="create_project_div">
							<?php if(count($getallproj) == 0){ ?>
								<div id="projUpdateTop">
									<?php if(SES_TYPE <=2){
										echo "<font color='#C4C4C4'>&lt;Yet to Create a Project&gt;</font>";	
									}else{?>
										<span class="no_project_assgn">--None--</span>	
									<?php }  ?>
								</div>
								<?php } ?>
								<?php
								$projUniq1 = "";
								if(PAGE_NAME == "dashboard" && $projName!='All') {
								    if(SES_TYPE<=2){
									    $ctProjName = $projName?$projName:"<Yet to Create a Project>";
								    } else {
										$ctProjName = $projName;
									}
									$projUniq1 = $projUniq;
								    if(count($getallproj) == 1) {
									?>
									<div style="color:#003366;font-size:14px; padding-right:10px;" class="ttc" id="projUpdateTop">
										<?php echo  $ctProjName; ?>
									</div>
								<?php
								    }
								}elseif(count($getallproj) >= 1) {
									$ctProjName = $getallproj['0']['Project']['name'];
									$projUniq1 = $getallproj['0']['Project']['uniq_id'];
									if(count($getallproj) == 1) {?>
									<div id="projUpdateTop" class="ttc">
										<?php echo $ctProjName; ?>
									</div>
								<?php }
								} ?>
								<input type="hidden" readonly="readonly" value="<?php echo $projUniq1; ?>" id="curr_active_project"/>
								<?php if(count($getallproj) > 1){ ?>
									<div class="popup_link link_as_drp_dwn swtchproj fl" id="ctask_popup">
									    <a href="javascript:void(0);" data-toggle="dropdown" class="option-toggle" onclick="show_prjlist(event);">
										<span  id="projUpdateTop" class="ttc"><?php echo $ctProjName; ?></span>
										<i class="caret"></i>
									    </a>
									</div>
									<div id="prjchange_loader" style="display:none;margin-left: 15px;margin-top: -2px;" class="fl">
										<img src="<?php echo HTTP_IMAGES;?>images/del.gif" title="Loading..." alt="Loading..."/>
									</div>
									<div class="cb"></div>
									<div id="openpopup" class="popup dropdown-menu lft popup ctaskproj ttc">
										<div class="popup_con_menu" align="left">
											<?php if(count($getallproj) > 6){ ?>
<!--											<div class="find_prj_ie">Find a Project</div>-->
											<input type="text" id="ctask_input_id" class="form-control pro_srch" placeholder="Find a Project" onkeyup="search_project_easypost(this.value,event)">
											<i class="icon-srch-img"></i>
											<div id="load_find_addtask" style="display:none;" class="loading-pro">
												<img src="<?php echo HTTP_IMAGES;?>images/del.gif"/>
											</div>
											<?php } ?>
											<div align="left" id="ajaxaftersrchc" style="display: none;"></div>
											<div align="left" id="ajaxbeforesrchc">
												<?php foreach($getallproj as $getPrj){ ?>
													<a href="javascript:void(0);" class="proj_lnks" onclick="showProjectName('<?php echo rawurlencode($getPrj['Project']['name']); ?>','<?php echo $getPrj['Project']['uniq_id']; ?>')" ><?php echo $this->Format->shortLength($getPrj['Project']['name'],27); ?></a>
													<hr class="pro_div"/>
											<?php } ?>
											</div>
										</div>
									</div>
								<?php } ?>
							<div id="projAllmsg" style="display:none;color:#C0504D;font-size:14px;  padding-top:10px;">Oops! No project selected.</div>
							</div>
						</div>
					</td>
				</tr>		
				<tr>
					<td colspan="2">
						<div class="fl lbl-m-wid" style="padding-top:16px">Title:</div>
						<div class="col-lg-9 fl rht-con">
							<input class="form-control" type="text" placeholder="Add a task here & hit enter...." id="CS_title" maxlength='240' onblur='blur_txt();checkAllProj();' onfocus='focus_txt()' onkeydown='return onEnterPostCase(event)' onkeyup='checktitle_value();' style="font-size:15px;"/>
						</div>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<div class="case_field">
						<table cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td>
									<table cellpadding="0" cellspacing="0">
										<tr>
											<td class="case_fieldprof">
												<div class="fl lbl-m-wid">Assign To:</div>
											</td>
											<td align="left">
												<div id="sample1" class="dropdown option-toggle p-6" >
													<div class="opt1" id="opt5">
														<a href="javascript:jsVoid()" onclick="open_more_opt('more_opt5');">
															<span id="tsk_asgn_to">
															
															</span>
															<i class="caret mtop-10 fr"></i>
														</a>
													</div>
													<div class="more_opt" id="more_opt5">
														<ul>
														
														</ul>
													</div>
												</div>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td align="left">
									<?php
									$curdate = gmdate("Y-m-d H:i:s");
									$userDate = $this->Tmzone->GetDateTime(SES_TIMEZONE,TZ_GMT,TZ_DST,TZ_CODE,$curdate,"datetime");
									
									$curDay = date('D',strtotime($userDate));
									$friday = date('Y-m-d',strtotime($userDate."next Friday"));
									$monday = date('Y-m-d',strtotime($userDate."next Monday"));
									$tomorrow = date('Y-m-d',strtotime($userDate."+1 day"));
									
									$titleValue = "Daily Update - ".date("m/d");
									?>


									<div class="fl lbl-m-wid" style="padding-top:17px">Due Date:</div>
										<div class="col-lg-9 fl rht-con">	
											<div class="fl dropdown option-toggle p-6">
												<div class="opt1" id="opt3"><a href="javascript:jsVoid()" onclick="open_more_opt('more_opt3');"> 
													<span id="date_dd">	
													<?php if(isset($taskdetails['due_date']) && $taskdetails['due_date']){
														echo date('m/d/Y',strtotime($taskdetails['due_date']));
													 }else{?>
														No Due Date
													<?php }?>
													</span>
													<i class="caret mtop-10 fr"></i></a></div>

				
											<div class="more_opt" id="more_opt3" >
												<ul>
													<li><a href="javascript:jsVoid()">&nbsp;&nbsp;No Due Date<span class="value">No Due Date</span></a></li>
													<li><a href="javascript:jsVoid()">&nbsp;&nbsp;Today<span class="value"><?php echo date('M j, D',strtotime($userDate));?></span> </a></li> 	
													<li><a href="javascript:jsVoid()">&nbsp;&nbsp;Next Monday <span class="value"><?php echo date('M j, D',strtotime($monday));?></span></a></li> 
													<li><a href="javascript:jsVoid()">&nbsp;&nbsp;Tomorrow<span class="value"><?php echo date('M j, D',strtotime($tomorrow));?></span></a></li>
													<li><a href="javascript:jsVoid()">&nbsp;&nbsp;This Friday<span class="value"><?php echo date('M j, D',strtotime($friday));?></span></a></li> 
													<li style="color:#808080; padding-left:10px;">
														<input type="hidden" id="due_date" title="Custom Date" style="min-width:30px;"/>&nbsp;<span style="position:relative;">Custom Date</span>
													</li>
												</ul>
											</div>
										</div>
									</div>
								</td>
							</tr>
							<tr>
								<td id="task_priority_td" colspan="2" style="padding:6px 0 5px 0;">
									<table cellpadding="0" cellspacing="0">
										<tr>
											<td class="case_fieldprof" >
												<span id="hd1">
													<div class="fl lbl-m-wid">Priority:</div>
												</span>
											</td>
											<td align="left">
												<div class="fl prio_radio y_low" onclick="check_priority(this);" ><input type="radio" name="task_priority" value="2" id="priority_low"  class="pri-checkbox" <?php if(isset($taskdetails['priority']) && $taskdetails['priority']==2){?>checked="checked"<?php }?>/><label tabindex=4 class="pri-label"></label></div>
                                                <div class="fl pri_type">Low</div>
												<div class="fl prio_radio g_mid" onclick="check_priority(this);"><input type="radio" name="task_priority" value="1" id="priority_mid"  class="pri-checkbox" <?php if(!isset($taskdetails['priority'])){?>checked="checked"<?php }elseif($taskdetails['priority']==1){?>checked="checked"<?php }?> /><label tabindex=4 class="pri-label"></label></div>
                                                <div class="fl pri_type">Medium</div>
												<div class="fl prio_radio h_red" onclick="check_priority(this);"><input type="radio" name="task_priority" value="0" id="priority_high" class="pri-checkbox" <?php if(isset($taskdetails['priority']) && $taskdetails['priority']==0){?>checked="checked"<?php }?> /><label tabindex=4 class="pri-label"></label></div>
                                                <div class="fl pri_type">High</div>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</div>
					<div id="new_case_more_div" style="display:none;"><?php echo $this->element('case_quick'); ?></div>
					</td>
				</tr>
				<tr>
					<td colspan="2" id="ajxQuickMem">
						<div class="fl lbl-m-wid">Notify via Email:</div>
						<div class="col-lg-9 fl rht-con email rht_bg">
							<input type="checkbox" name="chk_all" id="chked_all"  value="all" onClick="checkedAllRes()">&nbsp;ALL
							<div  id="viewmemdtls">
								<?php /*?>Project's user emails<?php */?>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td colspan="2" align="left">
						<div class="fl lbl-m-wid">&nbsp;</div>
						<div class="col-lg-9 fl rht-con rht_bg" style="padding-left:4px; padding-bottom:0">
							<div class="fr mor_toggle tasktoogle" id="more_tsk_opt_div" style="position: relative;float:left"><a href="javascript:jsVoid();" onclick="opencase('click');" style="text-decoration:none"><img src="<?php echo HTTP_IMAGES;?>description.png" title="Description" rel="tooltip"/>&nbsp;&nbsp;<img src="<?php echo HTTP_IMAGES;?>hours.png" title="Estimated Hours and Hours Spent" rel="tooltip"/>&nbsp;&nbsp;<img src="<?php echo HTTP_IMAGES;?>attachment.png" title="Attachments, Google Drive, Dropbox" rel="tooltip"/>&nbsp;&nbsp;More Options<b class="caret"></b></a></div>

							<div class="fr less_toggle tasktoogle" id="less_tsk_opt_div" style="display:none;position: relative;float:left"><a href="javascript:jsVoid();" onclick="closecase();"  style="text-decoration:none">Less<b class="caret"></b></a></div>
							
							<div style="position:relative;width:20px;" class="fl">
								<img src="<?php echo HTTP_IMAGES;?>images/del.gif" title="Loading..." alt="Loading..." id="loadquick" style="display:none;"/>
							</div>
						</div>

					</td>
				</tr>
			</table>
			<div class="cb"></div>
			<input type="hidden" value="" name="easycase_uid" id="easycase_uid"  readonly="readonly"/>
			<input type="hidden" value="" name="easycase_id" id="CSeasycaseid" readonly="readonly" />
			<input type="hidden" value="" name="editRemovedFile" id="editRemovedFile" readonly="readonly" />
			<div class="col-lg-12 task_slide_in btm_block">
				<div style="float:left;width:255px;">
					<input type="hidden" name="hid_http_images" id="hid_http_images" value="<?php echo HTTP_IMAGES; ?>" readonly="true" />
					<span id="quickcase" style="display:block;" class="nwa">
					<button class="btn btn_blue" <?php if(count($getallproj) == 0) { ?>disabled="disabled"<?php }?> type="submit" onclick ="return submitAddNewCase('Post',0,'','','',1,'');"><i class="icon-big-tick"></i><span id="ctask_btn">Post</span></button>
					<!--<button class="btn btn_grey" type="reset" id="rset" onclick="crt_popup_close();"><i class="icon-big-cross"></i>Cancel</button>-->
                    <span class="or_cancel">or
                    <a id="rset" onclick="crt_popup_close();">Cancel</a>
                    </span>
					</span>
					<span id="quickloading" style="display:none;padding-left:10px;padding-top:5px;">
						<img src="<?php echo HTTP_IMAGES;?>images/case_loader2.gif" title="Loading..." alt="Loading..."/>
					</span>
				</div>
				
				<!--<div style="float:left;width:340px;">
					
				</div>-->
			</div>
		</div>
	</div>
 </div>
 <div class="cb"></div>

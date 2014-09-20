<!--<style type="text/css">
.icon_invt{background: url("<?php echo HTTP_ROOT;?>img/html5/icons/team_n.png") no-repeat scroll 7px 5px rgba(0, 0, 0, 0);height: 26px;margin:-4px -16px;position: absolute;width: 23px;}
.ui-tabs {
	zoom: 1;
	width:38%;
	padding:0;
}
.ui-tabs .ui-tabs-nav {
	list-style: none;
	position: relative;
	padding: 0 0 0 15px;
	margin: 0;
	width:60%;
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
	background:url(../../img/images/ac.png) no-repeat;
	outline: none;
	font-family:tahoma;
	color:#333333;
	font-size:13px;
	/*height:19px;*/
	width:165px;
	text-decoration:none;
}

.ui-tabs .ui-tabs-nav li a:hover {
	display: block;
	/*padding:6px 10px;*/
	background:url(../../img/images/in_ac.png) no-repeat;
	outline: none;
	font-family:tahoma;
	color:#333333;
	font-size:13px;
	/*height:19px;*/
	width:165px;
	text-decoration:none;
	z-index:999;
	position:relative;
}
.class_active{
	padding:6px 10px 7px 10px;
	background:url(../../img/images/in_ac.png) no-repeat;
	border-bottom:none;
	position:relative;
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

/*.inactive{
	background: none repeat scroll 0 0 #FFFFFF;
	border: 1px solid #ddd;
	-moz-border-radius:2px;
	padding-right:4px;
	width: 5px;
}*/
</style>-->
<style type="text/css">
.full_board{position:fixed;height:100%;width:100%; z-index: 999;left: 0px;top:0px;}
.board_popup{
	background:#333;
    height:100%;
    left:0px;
    padding: 10px;
    position:absolute;
    top:0px;
    width:100%;
	opacity: 0.5;
	 -ms-filter:"progid:DXImageTransform.Microsoft.Alpha(opacity=50)";
}
.board_pnt{background: url("<?php echo HTTP_ROOT; ?>img/images/board_arrw.png") no-repeat;height:289px;width:216px;position:absolute;left:84%;margin-top:35px;z-index:99999;}
.board_new{position:absolute;color:#fff;font-size:27px;width:700px;margin:268px 0 0 -522px;text-shadow:1px 2px 3px #000;-webkit-text-shadow:1px 2px 3px #000;-moz-text-shadow:1px 2px 3px #000;}

</style>
<!--[if lt IE 10]>
    <style>
    	.find_prj_ie{display: block; position: absolute; right: 240px; top: 46px;}
    </style>	
<![endif]-->
<script type="text/javascript">
function viewEditDeleteHover(a,b)
{
	document.getElementById(b).style.display='block';
}
function viewEditDelete(a,b)
{
	document.getElementById(b).style.display='none';
}
function delproject(name)
{
	var conf = confirm("Are you sure you want to delete '"+name+"' ?");
	if(conf == true)
	{
		return true;
	}
	else if(conf == false)
	{
		return false;
	}
}
$(document).ready(function(){
	 $('#txt_res').keypress(function(e){
          //alert('hi');
		if (e.keyCode == 13) {
			id = 'txt_res';
			filterProjectSearch(id);
			//searchData();
			return false;		
		}
	});
}); 
</script> 
<?php
//if((SES_TYPE == 1) && ONBORDING_DAILY_UPDATE && !isset($_COOKIE['ONBORDING_DAILY_UPDATE_'.SES_ID]) && (strtotime(ONBORDING_DATE)>strtotime(CMP_CREATED))){
/*
if((SES_TYPE <3) && ONBORDING_DAILY_UPDATE && !isset($_COOKIE['ONBORDING_DAILY_UPDATE_'.SES_ID])){?>
<div class="full_board" id="onbording_dailyupdate">
	<div class="board_popup"></div>
	<div class="board_pnt">
		<div class="board_new">With <font style="font-family:MyriadProSemibold">Daily Update Alert</font>, schedule alert email for your team.</div>
		<div class="fr" style="margin:319px 47px 0 0"><a href="javascript:jsVoid();" class="gray_btn_new" style="padding:7px 12px;background:#ff7800;color:#fff;border:1px solid #fff;font-size:15px;" onclick="close_onbording('ONBORDING_DAILY_UPDATE_','onbording_dailyupdate');">Okay, got it!</a></div>
	</div>
</div>

<?php } */?>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
     <tr>
          <td>
               <div id="active_div" class="ui-tabs" style="display:block">
                    <ul class="ui-tabs-nav" id="ui-tabs-nav">
                         <li style="margin-left:-15px;" id = "activeli" <?php if($projtype == '') { ?>class="class_active" <?php }?>><a id="active"  href="<?php echo HTTP_ROOT."projects/".PAGE_NAME; ?>" >Active</a></li>
                         <li id = "completeli" <?php if($projtype == 'disabled') { ?>class="class_active" <?php }?>><a id="complete"  href="<?php echo HTTP_ROOT."projects/".PAGE_NAME."/disabled"; ?>" >Inactive</a></li>
                    </ul>
				</div>
			  <div class="popup_link_status link_as_drp_dwn fr" id="expimpdiv">
					<a href="javascript:jsVoid();" id="exportimport-root" style="font-style:normal; text-decoration:none" original-title="Click to Export/Import CSV" onclick="view_exportimport_menu();"><font style="font-size:12px;">
						<span class="pname_dashboard">Download/Upload CSV</span>&nbsp; 
						<img src="<?php echo HTTP_IMAGES; ?>images/collapse_lft_menu.png" alt="down arr" style="position:relative; top:-2px" /></font>
					</a>
			  </div>
			  <div class="popup1" style="display:none;position:absolute;right:0px;top: 25px; " id="expimppopup">
				<div class="pop_arrow_new" style="position:absolute;left:7px"></div>
				<div class="popup_con_menu" align="left" style="left:-24px;min-width:100px;">
					<a href="javascript:void(0);" onclick="ajax_exportCsv(1);">Download</a>
					<a href="<?php echo HTTP_ROOT;?>projects/importexport/<?php echo $getallproj[0]['Project']['uniq_id'];?>">Upload</a>
				</div>
			</div>
          </td>
     </tr>
</table>
<table width="100%" border="0" cellspacing="2" cellpadding="2" align="center">
	<tr>
		<td align="left" colspan="2">
			<table border="0" style="border:1px solid #DCDCDC" width=100%>
				<tr height="28px">
					<td style="font-weight:bold;color:#333333;padding-left:5px;" align="left" class="tophead">
						Project Name
					</td>
					<td style="font-weight:bold;color:#333333;padding-left:5px;" width="90px" align="left" class="tophead">
						Short Name
					</td>
					<td style="font-weight:bold;color:#333333;" align="center" width="120px" class="tophead">
						Active Team Size
					</td>
					<td style="font-weight:bold;color:#333333;" align="center" width="90px" class="tophead">
						#of Tasks
					</td>
					<td style="font-weight:bold;color:#333333;" align="center" width="90px" class="tophead">
						Hours Spent
					</td>
					<?php /*?><td style="font-weight:bold;colourl("../../img/images/add_project.png") r:#333333;" align="center" width="80px" class="tophead">
						Type
					</td><?php */?>
					<td style="font-weight:bold;color:#333333;" align="center" width="120px" class="tophead">
						Storage (MB)<?php /*?><br>(Max. <?php echo MAX_SPACE_USAGE; ?> Mb)<?php */?>
					</td>
					<td style="font-weight:bold;color:#333333;" align="center" width="140px" class="tophead">
						Latest Activity
					</td>
					<td style="font-weight:bold;color:#333333;" align="center" width="120px" class="tophead">
						Action
					</td>
				</tr>
				<?php
				$count=0; $clas = "";
				$space = 0;
				$spacepercent=0;
				$totCase = 0;
				$totHours = '0.0';
				if(count($prjAllArr)) {
				foreach($prjAllArr as $prjAllArr){
				$totCase = $prjAllArr[0]['totalcase'];
				$totHours = (!empty($prjAllArr[0]['totalhours'])) ? $prjAllArr[0]['totalhours']: '0.0';
				?>
				<?php
				$count++;
				if($count %2==0) { $clas = "row_col"; }
				else { $clas = "row_col_alt"; }
				?>
				<tr class="<?php echo $clas?>" height="25px" id="userlist<?php echo $count;?>" <?php if($prjAllArr['Project']['isactive'] == 2) { ?> style="background-color:#FEE2E2;" <?php } ?>>	
					<td style="padding-left:5px;">
						<a href="javascript:void(0);" class="classhover" rel="tooltip" original-title="Click here to view users" onclick="openusers('<?php echo $count;?>');userListing('<?php echo $count;?>','<?php echo $prjAllArr['Project']['id'];?> ')">
							<?php echo $prjAllArr['Project']['name'];?>
						</a>
					</td>
					<td align="left" style="padding-left:5px;text-transform:lowercase"><?php echo $prjAllArr['Project']['short_name'];?></td>
					<td align="right" style="padding-right:5px;">
						<?php echo $prjAllArr[0]['totusers']; ?>
					</td>
					<td align="right" style="padding-right:5px;"><?php echo $totCase;?></td>
					<td align="right" style="padding-right:5px;"><?php echo $totHours;?></td>
					<?php /*?><td align="center"><?php if($prjAllArr['Project']['project_type'] =="1"){echo "Internal";}else{echo "External";};?></td><?php */?>
					<td align="right" style="padding-right:5px;">
					<?php 
					    $filesize = 0;
					    if($totCase && isset($prjAllArr[0]['storage_used']) && $prjAllArr[0]['storage_used']){ 
						$filesize = number_format(($prjAllArr[0]['storage_used']/1024),2);
						$space = $space+$filesize;
					    }
					    echo $filesize;
					    ?>
					    
					<?php 
					/*if($totCase) {
						echo $getspace = $this->Casequery->UsedSpace($prjAllArr['Project']['id']);
						$space = $space+$getspace;
					}
					else {
						echo 0;
					}*/
					?>
					</td>
					<td align="center">
						<?php $getactivity=$this->Casequery->getlatestactivitypid($prjAllArr['Project']['id'],1);
						if($getactivity==""){echo "<font color='#A5A5A5'>no activity</font>";
						}else{
							
							$locDT = $this->Tmzone->GetDateTime(SES_TIMEZONE,TZ_GMT,TZ_DST,TZ_CODE,$getactivity,"datetime");
							$gmdate = $this->Tmzone->GetDateTime(SES_TIMEZONE,TZ_GMT,TZ_DST,TZ_CODE,GMT_DATE,"date");
							echo $this->Datetime->dateFormatOutputdateTime_day($locDT,$gmdate,'time');
						}
						?>
						</td>
						<td align="center">
							<table cellpadding="0" cellspacing="0" align="center">
							<tr>
								
								<?php
								$projName = $this->Format->formatText($prjAllArr['Project']['name']);
								if($prjAllArr['Project']['isactive'] == 1) {
								?>
								<td style="padding:0 4px">
									<!--<a href="javascript:void(0);" class="makeHover">-->
										<?php /*?><div class="act_user_new" rel="tooltip" title="Add user" style=""></div><?php */?>
										
										<a href="javascript:void(0);" class="makeHover" onClick="openUsPopup('add_user','<?php echo $prjAllArr['Project']['id']; ?>','<?php echo urlencode($prjAllArr['Project']['name']); ?>','<?php echo $count;?>')"><div class="act_user_new"  rel=tooltip  title="Add user"></div></a>
										
											<div class="act_popup" style="display:none;position:relative; z-index: 10;">
												<div style="position:absolute"><img src="<?php echo HTTP_ROOT; ?>img/images/act_arrow.png" style="margin-top:0;"/></div>
												<div class="act_con">
													<textarea cols="40" style="resize:none"></textarea>
												</div>
												<a href="javascript:void(0);" class="close_act">X</a>
											</div>
										
									<!--</a>-->
								</td>
								<td style="padding:0 4px">
									<a href='<?php echo HTTP_ROOT; ?>projects/settings/?pid=<?php echo $prjAllArr['Project']['uniq_id']; ?>' class="makeHover"><div class="act_set" style="margin-left:3px;" rel=tooltip  title="Edit"></div></a>
								</td>
								<!--<td><a href="<?php echo HTTP_ROOT;?>projects/import_data/<?php //echo $prjAllArr['Project']['id'];?>" class="makeHover"><div class="act_import" style="margin-left:3px;" rel=tooltip  title="Import CSV"></div></a></a></td>-->
								<td style="padding:0 4px">
									<?php
									if($totCase == 0) {
									?>
										<a href="javascript:void(0);" onClick="setprojectAction('delete','<?php echo $projName?>','<?php echo $prjAllArr['Project']['id']?>','<?php echo HTTP_ROOT; ?>projects/gridview/');" class="makeHover">
											<div class="act_del" style="margin-left:3px;" rel=tooltip  title="Delete"></div>
										</a>
									<?php
									}
									else {
									?>
										<a href="javascript:void(0);"  onClick="setprojectAction('deactivate','<?php echo $projName?>','<?php echo $prjAllArr['Project']['id']?>','<?php echo HTTP_ROOT; ?>projects/gridview/');" class="makeHover"><div class="act_dis" style="margin-left:3px;" rel=tooltip  title="Inactive"></div></a>
									<?php
									}
									?>
								</td>
								<?php
								}
								else {
									?>
									<td style="padding:0 4px">
										<?php
										if($totCase == 0) {
										?>
											<a href="javascript:void(0);" onClick="setprojectAction('delete','<?php echo $projName?>','<?php echo $prjAllArr['Project']['id']?>','<?php echo HTTP_ROOT; ?>projects/gridview/');" class="makeHover"><div class="act_del" style="margin-left:3px;" rel=tooltip  title="Delete"></div></a>
											
										<?php
										}
										else {
										?>
											<a href="javascript:void(0);" onClick="setprojectAction('activate','<?php echo $projName?>','<?php echo $prjAllArr['Project']['id']; ?>','<?php echo HTTP_ROOT; ?>projects/gridview/');" class="makeHover"><div class="act_dis" style="margin-left:3px;" rel=tooltip  title="Active"></div></a>
										<?php
										}
										?>
									</td>
									<?php
								}
								?>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td colspan="8" align="center" style="padding-left:40px">
						<div id="userImg<?php echo $count; ?>" style="display:none;width:100%;text-align:center;padding-top:5px;">
							<img src="<?php echo HTTP_IMAGES; ?>images/case_thread_loader.gif" alt="loading..." title="loading..."/>
						</div>
						<div id="userDiv<?php echo $count; ?>" style="display:none;color:#666;" class="lidata_div"></div>
					</td>
				</tr>
				<?php }
				}
				else
				{ 
				?>
				<tr><td style="color:#666666;padding:10px" colspan="8" align="center">
					<?php //echo $prjsrch;
					if($projtype)
					{
                              if($prjsrch){
                                   echo "No Results Found"; ?>
                                   <div style="padding-top:5px;"><a href="<?php echo HTTP_ROOT; ?>projects/gridview/disabled" style="color:#0000FF;font-weight:normal;">View All</a></div>
                              <?php }else{
                                   echo "No inactive project!";
                              }
						
					}
					else
					{
                              if($prjsrch){
                                   echo "No Results Found"; ?>
                                <div style="padding-top:5px;"><a href="<?php echo HTTP_ROOT; ?>projects/gridview/" style="color:#0000FF;font-weight:normal;">View All</a></div>
                              <?php }else{
                                   echo "No project!";
                              }
						?>
						<!--<div align="center"  style="margin:10px;" id="menupj1"><button class="green" onclick="newProject('menupj1','loaderprj1');">+ New Project</button></div>
                              <a href="javascript:jsVoid()" id="loaderprj1" style="text-decoration:none;cursor:wait;display:none;">
													Loading...<img src="<?php echo HTTP_IMAGES;?>images/del.gif" width="16" height="16" alt="loading..." title="loading..." />
												</a>-->
					<?php
					}
					?>
				</td></tr>
				<?php	
				}
				 ?>
			</table>
		</td>
	</tr>
	<tr>
		<td style="font-size:14px;" colspan="2">Total storage used: <b><?php echo $space; ?></b> Mb</td>
	</tr>

	<tr>
		<td>
			<input type="hidden" id="getcasecount" value="<?php echo $caseCount; ?>" readonly="true"/>
			<?php if($caseCount){?>
			<table cellpadding="0" cellspacing="0" border="0" align="right" >
				<tr>
					<td align="center" style="padding-top:5px;">
						<div class="show_total_case" style="font-weight:normal;color:#000;font-size:12px;">
							<?php echo  $this->Format->pagingShowRecords($caseCount,$page_limit,$casePage); ?>
						</div>
					</td>
				</tr>
				<tr>
					<td align="center" style="padding-top:5px">
					<ul class="pagination">
					<?php $page = $casePage;
						if($page_limit < $caseCount){
							$numofpages = $caseCount / $page_limit;
							if(($caseCount % $page_limit) != 0){
								$numofpages = $numofpages+1;
							}
							$lastPage = $numofpages;
							$k = 1;
							$data1 = "";
							$data2 = "";
							if($numofpages > 5){
								$newmaxpage = $page+2;
								if($page >= 3){
									$k = $page-2;
									$data1 = "...";
								}
								if(($numofpages - $newmaxpage) >= 2){
									if($data1){
										$data2 = "...";
										$numofpages = $page+2;
									}else{
										if($numofpages >= 5){
											$data2 = "...";
											$numofpages = 5;
										}
									}
								}
							}
							if($data1){
                                         if($projtype == 'disabled'){
                                             echo "<li><a href='".HTTP_ROOT."projects/gridview/disabled?page=1' class=\"button_act\">&laquo; First</a></li>";
                                        }else{
                                             echo "<li><a href='".HTTP_ROOT."projects/gridview?page=1' class=\"button_act\">&laquo; First</a></li>";
                                        }
								//echo "<li><a href='".HTTP_ROOT."user/manage?page=1' class=\"button_act\" >&laquo; First</a></li>";
								echo "<li class='hellip'>&hellip;</li>";
							}
							if($page != 1){
								$pageprev = $page-1;
                                        if($projtype == 'disabled'){
                                             echo "<li><a href='".HTTP_ROOT."projects/gridview/disabled?page=".$pageprev."' class=\"button_act\">&lt;&nbsp;Prev</a></li>";
                                        }else{
                                             echo "<li><a href='".HTTP_ROOT."projects/gridview?page=".$pageprev."' class=\"button_act\">&lt;&nbsp;Prev</a></li>";
                                        }
								//echo "<li><a href='".HTTP_ROOT."projects/gridview?page=".$pageprev."' class=\"button_act\">&lt;&nbsp;Prev</a></li>";
							}else{
								echo "<li><a href='javascript:jsVoid();' class=\"button_prev\" style=\"cursor:text\">&lt;&nbsp;Prev</a></li>";
							}
							for($i = $k; $i <= $numofpages; $i++){
								if($i == $page) {
									echo "<li><a href='javascript:jsVoid();' class=\"button_page\" style=\"cursor:text\">".$i."</a></li>";
								}else {
                                             if($projtype == 'disabled'){
                                                  echo "<li><a href='".HTTP_ROOT."projects/gridview/disabled?page=".$i."' class=\"button_act\" >".$i."</a></li>";
                                             }else{
                                                  echo "<li><a href='".HTTP_ROOT."projects/gridview?page=".$i."' class=\"button_act\" >".$i."</a></li>";
                                             }
									//echo "<li><a href='".HTTP_ROOT."projects/gridview?page=".$i."' class=\"button_act\" >".$i."</a></li>";
								}
							}
							if(($caseCount - ($page_limit * $page)) > 0){
								$pagenext = $page+1;
                                        if($projtype == 'disabled'){
                                             echo "<li><a href='".HTTP_ROOT."projects/gridview/disabled?page=".$pagenext."' class=\"button_act\" >Next&nbsp;&gt;</a></li>";
                                        }else{
                                             echo "<li><a href='".HTTP_ROOT."projects/gridview?page=".$pagenext."' class=\"button_act\" >Next&nbsp;&gt;</a></li>";
                                        }                                             
								//echo "<li><a href='".HTTP_ROOT."projects/gridview?page=".$pagenext."' class=\"button_act\" >Next&nbsp;&gt;</a></li>";
							}else{
                                        if($projtype == 'disabled'){
                                             echo "<li><a href='".HTTP_ROOT."projects/gridview/disabled?page=".$pagenext."' class=\"button_prev\">Next&nbsp;&gt;</a></li>";
                                        }else{
                                             echo "<li><a href='".HTTP_ROOT."projects/gridview?page=".$pagenext."' class=\"button_prev\">Next&nbsp;&gt;</a></li>";
                                        }
								//echo "<li><a href='".HTTP_ROOT."projects/gridview?page=".$pagenext."' class=\"button_prev\">Next&nbsp;&gt;</a></li>";
							}
							if($data2){
								echo "<li class='hellip'>&hellip;</li>";
                                        if($projtype == 'disabled'){
                                             echo "<li><a href='".HTTP_ROOT."projects/gridview/disabled?page=".floor($lastPage)."' class=\"button_act\" >Last &raquo;</a></li>";
                                        }else{
                                             echo "<li><a href='".HTTP_ROOT."projects/gridview?page=".floor($lastPage)."' class=\"button_act\" >Last &raquo;</a></li>";
                                        }
								//echo "<li><a href='".HTTP_ROOT."projects/gridview?page=".floor($lastPage)."' class=\"button_act\" >Last &raquo;</a></li>";
							}
						} ?>
					</ul>
				</td>
			</tr>
		</table>
		<?php }	?>
	</td>
</tr>	
</table>
<input type="hidden" id="totalcount" name="totalcount" value="<?php echo $count; ?>"/>
<!----------- Add User Popup -------------->
<div id="backgroundPopup"></div>
<div id="popupContact" style="height:425px;width:750px;top:10px;" class="inner">
<table cellspacing="0" cellpadding="0" width="700px" class="div_pop" align="center">
	<tr>
		<td style="padding-left:10px;" valign="middle" class="ms_hd">
			<div style="float:left;padding-top:3px;">
				<table cellpadding="0" cellspacing="0">
					<tr>
						<td>
							<h1 style="margin:0;padding:0;" class="popup_head">Add User</font></h1>
						</td>
						<td style="padding:0 10px">
							<img src="<?php echo HTTP_IMAGES;?>html5/icons/icon_breadcrumbs.png" />
						</td>
						<td>
							<h1 class="popup_head" id="projectname"  style="margin:0;padding:0;color:#666666"></h1>
						</td>
					</tr>
				</table>
			</div>
			
			<div style="float:right;padding-right:13px;">
				<button class="green" id="inviteusr" onclick="newUser('menuid','loaderid','add_user');" style="margin-top:1px;height: 27px;width: 107px;padding:4px 12px;text-align: right;"><div class="icon_invt"></div>Invite User</button>
				<img src="<?php echo HTTP_IMAGES;?>images/del.gif" alt="Loading..." title="Loading..." id="loadinginvt" style="display:none;position:relative;"/>
				&nbsp;&nbsp;&nbsp;
				<img src="<?php echo HTTP_IMAGES;?>images/popup_close.png" alt="Close" title="Close" onclick="usPopupClose()" style="cursor:pointer" />
			</div>
		</td>
	</tr>
	<tr>
		<td style="padding:5px 11px;" align="right">
			<span id="usersrch" style="display:none;">
             	<div class="find_prj_ie">Enter User Name</div>
				<?php echo $this->Form->text('name',array('size'=>'35','class'=>'text_field','style'=>'padding:4px 26px 4px 5px;width: 190px;','id'=>'name','maxlength'=>'100','onkeyup'=>'searchuserkey()','placeholder'=>'Enter User Name')); ?>
				<div class="src_img"><img src="<?php echo HTTP_IMAGES; ?>images/srch.png" /></div> 
			</span>
		</td>
	</tr>
	<tr>
		<td align="center">
			<!--<div id="popup_head" style="border-bottom:1px solid #F2F2F2;margin-bottom:5px;"><h1 class="toplink">Add Task</h1></div>-->
			<span id="popupload" style="display:none;position:absolute;top:17%;left:40%;">Loading users... <img src="<?php echo HTTP_IMAGES;?>images/del.gif" title="Loading..." alt="Loading..."/></span>
			
			<div id="loadcontent" style="width:97%;text-align:center;height:350px;overflow:auto;padding-left: 10px;"></div>
			
			<div id="popup_head" style="text-align:center;width:100%;height:40px;margin-top:20px;">
			<div id="userloader" style="display:none;text-align:center;padding-top:10px;">
				<img src="<?php echo HTTP_IMAGES; ?>images/del.gif" alt="loading..." title="loading..."/> 
			</div>
			<div id="confirmuser" style="display:block">
			<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%">
			<tr>
			<td valign="middle" style="width:500px" align="center" class="nwa">
				<span id="confirmbtn" style="display:block;">
					<button class="pop_btn" id="confirmusercls" style="margin-left:3px;" value="Confirm" type="button" onclick="assignuser(this)">Add</button>
					Or <button class="pop_btn" id="confirmuserbut" style="margin-left:3px;" value="Confirm" type="button" onclick="assignuser(this)">Add & Continue</button>
					Or <a id="javascript:void(0);" style="font-size:14px;" onclick="fbPopupClose()">Cancel</a>
				</span>
				<span id="closebtn" style="display:block;">
					<?php /*?>Or <a id="javascript:void(0);" style="font-size:14px;" onclick="fbPopupClose()">Cancel</a><?php */?>
				</span>
				<span id="excptAddContinue" style="display:none;">
					<button class="blue small" id="confirmusercls" style="margin-left:3px;width:75px" value="Confirm" type="button" onclick="assignuser(this)">Add</button>
					Or <a id="javascript:void(0);" style="font-size:14px;" onclick="fbPopupClose()">Cancel</a>
				</span>
			</td>
			</tr>
			</table>
			</div>
			</div>
		</td>
	</tr>
</table>
</div>
<script type="text/javascript">
function openusers(count) {

	var userDiv = 'userDiv'+count;
	var list = 'userlist'+count;
	
	var total = document.getElementById('totalcount').value;
	for(var i=1;i<=total;i++) 
	{
		if(i == count && document.getElementById(userDiv).style.display == 'block') {
			document.getElementById(userDiv).style.display = 'none';
			document.getElementById(list).style.background = '';
		}
		else {
			var divname = 'userDiv'+i;
			var divStyle = document.getElementById(divname).style;
			divStyle.display=(userDiv==divname)?'block':'none';
			
			var divcolname = 'userlist'+i;
			var divcolStyle = document.getElementById(divcolname).style; 
			divcolStyle.background=(list==divcolname)?'#FFFFCC':'';
		}
	}
}
function userListing(count,project_id) {
	var userDiv = 'userDiv'+count;
	var userImg = 'userImg'+count;
	var strURL = document.getElementById('pageurl').value;
	var strURL = strURL+'projects/user_listing';
	
	if(document.getElementById(userDiv).innerHTML == "") {
		$("#"+userImg).show();
		$.post(strURL,{"count":count,"project_id":project_id},function(data) {
		 if(data) {
			$('#'+userDiv).html(data);
			$("#"+userImg).hide();
			
		  }
		});
	}
}
</script>
<script type="text/javascript">
function searchuserkey()
{
	var name = document.getElementById('name').value;
	var project_id = '';
	try{
			var project_id = document.getElementById('projectId').value;
			var pjname = document.getElementById('project_name').value;
			var cntmng = document.getElementById('cntmng').value;
			}
		catch(e) {
		}
		if(project_id) {
			var strURL1 = document.getElementById('pageurl').value;
			var strURL1 = strURL1+'projects/add_user';
			$("#popupload").show();
			$.post(strURL1,{"pjname":pjname,"pjid":project_id,"name":name,"cntmng":cntmng},function(data) {
			 if(data) { 
					$('#loadcontent').html(data);
					$("#popupload").hide();
					$("#popupContactClose, .c_btn").click(function() {
						disablePopup();
					});
					//document.getElementById('confirmbtn').style.display = 'none';
					//document.getElementById('closebtn').style.display = 'block';
				 }
			});
		}
	
}
function removeuser(count,userid,pjid,uname) {
	
	var conf = confirm("Are you sure you want to remove ' "+uname+"'?");
	if(conf == true) {
		var userDiv = 'userDiv'+count;
		var userImg = 'userImg'+count;
		var listing = 'listing'+count;
		var strURL = document.getElementById('pageurl').value;
		var strURL = strURL+'projects/user_listing';
		//$("#"+userImg).show();
		$("#"+listing).fadeOut(1000);
		$.post(strURL,{"count":count,"userid":userid,"project_id":$.trim(pjid)},function(data) {
		 if(data) {
			
		  }
		});
		
		return true;
	}
	else {
		var checkBox = 'usCheckBox'+count;
		document.getElementById(checkBox).checked = true;
		return false;
	}
}
function removeDisuser(count,userid,pjid,uname) {
	
	var conf = confirm("Are you sure you want to remove '"+uname+"'?");
	if(conf == true) {
		var userDiv = 'userDiv'+count;
		var userImg = 'userImg'+count;
		var disabledlist = 'disabledlist'+count;
		var strURL = document.getElementById('pageurl').value;
		var strURL = strURL+'projects/user_listing';
		$("#"+disabledlist).fadeOut(1000);
		$.post(strURL,{"count":count,"userid":userid,"project_id":$.trim(pjid)},function(data) {
		 if(data) {
			
		  }
		});
		
		return true;
	}
	else {
		var checkBox = 'usDisCheckBox'+count;
		document.getElementById(checkBox).checked = true;
		return false;
	}
}
function removeInviteduser(count,userid,pjid,uname) {
	
	var conf = confirm("Are you sure you want to remove ' "+uname+"'?");
	if(conf == true) {
		var userDiv = 'userDiv'+count;
		var userImg = 'userImg'+count;
		var listing = 'Invitedlisting'+count;
		var strURL = document.getElementById('pageurl').value;
		var strURL = strURL+'projects/user_listing';
		$("#"+listing).fadeOut(1000);
		$.post(strURL,{"InvitedUser":'InvitedUser',"count":count,"userid":userid,"project_id":$.trim(pjid)},function(data) {
		 if(data) {

		  }
		});
		
		return true;
	}
	else {
		var checkBox = 'usInvCheckBox'+count;
		document.getElementById(checkBox).checked = true;
		return false;
	}
}
</script>
<script type="text/javascript">
	$(document).ready(function(){
		$('#expimpdiv').click(function(e){
			e.stopPropagation();
		});
		var url=document.URL;
		var n=url.search("page=");

		if(n == '-1'){
			//$("#add65").removeClass("inactive");
			//$("#add65").addClass("active");
			
		}else {
			var s=url.substr(n+5,2);
			$("#add"+s).addClass("active");
			$("#adds"+s).addClass("active");
		}
		<?php
		if(ASSIGN_USER) {
		?>
			openUsPopup('add_user','<?php echo ASSIGN_USER; ?>','<?php echo PROJ_NAME; ?>','1')

		<?php } ?>
		$(".close_act").click(function(){
			$(".act_popup").css({display:"none"});
		});
	});
	function view_exportimport_menu(){
		if($('#expimppopup').is(":visible")){
			$('#expimppopup').hide();
		}else{
			//$('#expimppopup').css({'display':'block'});
			$('#expimppopup').show();
	}	
		
	}	
</script>
<?php //echo $this->element('sql_dump'); ?>

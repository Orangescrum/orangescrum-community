<% var showQuickAct = showQuickActDD = 0;
if((csLgndRep == 1 || csLgndRep == 2 || csLgndRep == 4) && is_active==1) {
	var showQuickAct = 1;
}
if(showQuickAct && taskTyp.id != 10){
	var showQuickActDD = 1;
}
%>

<div id="t_<%= csUniqId %>" class="task_detail" style="margin-top:-15px;">
	<div class="page-wrapper">
		<div class="col-lg-9 fl task_details_row">
		<div class="row">
		  <div class="col-lg-12 task_details_title"> 
				<h1 class="wrapword">
					#<%= csNoRep %>: <%= formatText(ucfirst(caseTitle)) %>
				</h1>
				<div class="last_update">
					<% if(cntdta && (cntdta>0)) { %>Last updated<% } else { %>Created<% } %> by <b><%= lstUpdBy %></b>
					<% if(lupdtm.indexOf('Today')==-1 && lupdtm.indexOf('Y\'day')==-1) { %>on<% } %>
					<span title="<%= lupdtTtl %>"><%= lupdtm %>.</span>
					<% if(cntdta) { %>
					<span>&nbsp;<i class="icon-twit-count"></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(<%= total %>)</span>
					<% } %>
					<?php /*?>Last updated by <font>Tom Foolery</font> 12 hours ago <span>&nbsp;<i class="icon-twit-count"></i>&nbsp;&nbsp;&nbsp;&nbsp;(40)</span><?php */?>
				</div>
		  </div>
		  <div class="col-lg-12 task_details_title"> 
				 <div class="col-lg-4 task_elements">
					<table cellpadding="0" cellspacing="0">
						<tr>
							<td>Type:</td>
							<td>
								<div id="typdiv<%= csAtId %>" class="fl typ_actions <% if(showQuickAct==1){ %> dropdown<% } %>">
									<div class="fl task_types_<%= taskTyp.short_name %>"></div>
									<b <% if(showQuickAct==1){ %> class="quick_action" data-toggle="dropdown" <% } %>><%= taskTyp.name %></b>
									<% if(showQuickAct==1){ %>
									<ul class="dropdown-menu quick_menu">
										<li class="pop_arrow_new"></li>
										<% for(var k in GLOBALS_TYPE) {
											var v = GLOBALS_TYPE[k];
											var t = v.Type.id;
											var t1 = v.Type.short_name;
											var t2 = v.Type.name;
										%>
										<li>
											<a href="javascript:void(0);" <% if(t > 12){ %> style="margin-left:27px;" <% } %> onclick="changetype(<%= '\'' + csAtId + '\'' %>, <%= '\'' + t + '\'' %>, <%= '\'' + t1 + '\'' %>, <%= '\'' + t2 + '\'' %>, <%= '\'' + csUniqId + '\'' %>, <%= '\'' + csNoRep + '\'' %>)"><div class="task_types_<%= t1 %> fl"></div><%= t2 %></a>
										</li>
										<% } %>
									</ul>
									<% } %>
								</div>
								<span id="dettyplod<%= csAtId %>" style="display:none">
									<img src="<?php echo HTTP_IMAGES; ?>images/del.gif" alt="Loading..." title="Loading..."/>
								</span>
							</td>
						</tr>
						<tr>
							<td>Priority:</td>
							<td>
								<div id="pridiv<%= csAtId %>" data-priority ="<%= csPriRep %>" class="pri_actions <%= protyCls %><% if(showQuickAct==1){ %> dropdown<% } %>">
									<b <% if(showQuickAct==1){%> class="quick_action" data-toggle="dropdown" <% } %>><%= protyTtl %></b>
									<% if(showQuickAct==1){ %>
									<ul class="dropdown-menu quick_menu">
										<li class="pop_arrow_new"></li>
										<li><a href="javascript:void(0);" class="low_priority" onclick="detChangepriority(<%= '\'' + csAtId + '\', \'2\', \'' + csUniqId + '\', \'' + csNoRep + '\'' %>)">Low</a></li>
										<li><a href="javascript:void(0);" class="medium_priority" onclick="detChangepriority(<%= '\'' + csAtId + '\', \'1\', \'' + csUniqId + '\', \'' + csNoRep + '\'' %>)">Medium</a></li>
										<li><a href="javascript:void(0);" class="high_priority" onclick="detChangepriority(<%= '\'' + csAtId + '\', \'0\', \'' + csUniqId + '\', \'' + csNoRep + '\'' %>)">High</a></li>
									</ul>
									<% } %>
								</div>
								<span id="prilod<%= csAtId %>" style="display:none">
									<img src="<?php echo HTTP_IMAGES; ?>images/del.gif" alt="Loading..." title="Loading..."/>
								</span>
							</td>
						</tr>
						<tr>
							<td>Status:</td>
							<td><% if(is_active){ %><%= easycase.getColorStatus(csTypRep, csLgndRep) %> <% } else { %><span class="fnt_clr_rd">Archived</span><% } %></td>
						</tr>
					</table>
				 </div>
				 <div class="col-lg-4 task_elements">
					<table cellpadding="0" cellspacing="0">
						<tr>
							<td>Est. Hour(s):</td>
							<td><b>
							<% if(estimated_hours != 0.0) { %>
								<%= estimated_hours %>
							<% } else { %>
								<i class="no_due_dt">None</i>
							<% } %>
							</b></td>
						</tr>
						<tr>
							<td>Hour(s) Spent:</td>
							<td><b>
							<% if(hours != 0.0) { %>
								<%= hours %>
							<% } else { %>
								<i class="no_due_dt">None</i>
							<% } %>
							</b></td>
						</tr>
						<tr>
							<td>Milestone:</td>
							<td><b>
							<% if(mistn != '') { %>
								<%= mistn %>
							<% } else { %>
								<i class="no_due_dt">None</i>
							<% } %>
							</b></td>
						</tr>
					</table>
				 </div>
				 <div class="col-lg-4 task_elements">
					<table cellpadding="0" cellspacing="0">
						<?php /*?><tr>
							<td>Milestone:</td>
							<td><b>
							<%= mistn?shortLength(mistn,20):'<i class="no_due_dt">None</i>' %>
							</b></td>
						</tr><?php */?>
						<tr>
							<td>Project:</td>
							<td>
								<b class="ttc"><%= shortLength(projName,16) %></b>
							</td>
						</tr>
						<tr>
							<td>Task Progress:</td>
							<td>
							<% if(csLgndRep == 5 || csLgndRep == 3) {
								completedtask = 100;
							    } 
							    var progress = 0;
							    if(completedtask){
								progress = completedtask;
							    }
							%>
							<div class="tsk_det_progrs">
							<div class="imprv_bar_fade col-lg-12">
							    <div class="cmpl_fade" style="width:<%= progress %>%"></div>
							</div>
							<center><div class="tsk_prgrss"><%= progress %>%</div></center>
							</div>
							</td>
						</tr>
					</table>
				 </div>
		  </div> 
		  <div class="cb"></div>
		  <div class="col-lg-12">
			<div class="details_task_block">
				<div class="details_task_head">
					<div class="fl">
						<% if(pstFileExst) { %>
						<img data-original="<?php echo HTTP_ROOT; ?>users/image_thumb/?type=photos&file=<%= pstPic %>&sizex=35&sizey=35&quality=100" class="lazy round_profile_img rep_bdr" title="<%= pstNm %>" width="35" height="35" />
						<% } else { %>
						<img data-original="<?php echo HTTP_ROOT; ?>users/image_thumb/?type=photos&file=user.png&sizex=35&sizey=35&quality=100" class="lazy round_profile_img rep_bdr" title="<%= pstNm %>" width="35" height="35" />
						<% } %>
					</div>
					<div class="fl">
						<span>Created by <b class="ttc"><%= shortLength(crtdBy,20) %></b></span>
						<div class="fnt999"> <%= frmtCrtdDt %></div>
					</div>
				</div>
				<% if(dispSec) { %>
				<div class="details_task_desc wrapword">
					<%= csMsgRep %>
				<% var fc = 0;
				if(csFiles) { %>
					<br/><br/>
					<div class="case_clip"><div></div></div>
					<% var images = ""; var caseFileName = "";
					for(var fileKey in filesArr) {
						var getFiles = filesArr[fileKey];
						caseFileName = getFiles.CaseFile.file;
						downloadurl = getFiles.CaseFile.downloadurl;
						if(getFiles.CaseFile.is_exist) {
							fc++; %>
							<div class="attch_file_bg fl">
								<div class="fl tsk_ficn"><div class="tsk_fl <%= easycase.imageTypeIcon(getFiles.CaseFile.format_file) %>_file"></div></div>
								<div class="fl">
									<% if(downloadurl) { %>
									<span><a href="<%= downloadurl %>" target="_blank" alt="<%= caseFileName %>" title="<%= caseFileName %>"><%= shortLength(caseFileName,37) %></a></span>
									<% } else { %>
									<span><%= shortLength(caseFileName,37) %></span></span>
									<div class="fnt999">
									<% if(getFiles.CaseFile.is_ImgFileExt){ %>
										(<%= getFiles.CaseFile.file_size %>)&nbsp;&nbsp;
										<span class="gallery">
										<a href="<%= getFiles.CaseFile.fileurl %>" target="_blank" alt="<%= caseFileName %>" title="<%= caseFileName %>" rel="prettyPhoto[]">View</a>
										</span>
										&nbsp;&nbsp;
										<a href="<?php echo HTTP_ROOT; ?>easycases/download/<%= caseFileName %>" alt="<%= caseFileName %>" title="<%= caseFileName %>">Download</a>
									<%  } else{ %>
										(<%= getFiles.CaseFile.file_size %>)&nbsp;
										<a href="<?php echo HTTP_ROOT; ?>easycases/download/<%= caseFileName %>" alt="<%= caseFileName %>" title="<%= caseFileName %>">Download</a>
										<% } %>
									</div>
								<% } %>
								</div>
							</div>
							<% if(fc%2==0) { %>
							<div class="cb"></div>
							<% } %>
					<% 	}
					} %>
				<% } %>
				<div class="cb"></div>
				</div>
				<% } %>
			</div>
		  </div>
		  <div class="cb"></div>
		  <% if(cntdta){
		  if(total > 5){ %>
		  <div class="col-lg-12">
			<div class="fr view_rem">
				<span id="morereply<%= csAtId %>" style="<% if(cntdta > 5) { %>display:none<% } %>">
					<a href="javascript:void(0);" onclick="showHideMoreReply(<%= '\''+csAtId+'\',\'more\'' %>)">
						<% remaining = total-5;
						if(remaining == 1) { %>
							View remaining <%= remaining %> thread <%
						} else { %>
							View remaining <%= remaining %> threads <%
						} %>
					</a>
				</span>
				<span id="hidereply<%= csAtId %>" <% if(cntdta <= 5) { %> style="display:none" <% } %>>
					<a href="javascript:void(0);" onclick="showHideMoreReply(<%= '\''+csAtId+'\',\'less\'' %>)">
						View latest 5
					</a>
				</span>
				<span class="rep_st_icn"></span>
				<span id="loadreply<%= csAtId %>" style="visibility: hidden;"><img src="<?php echo HTTP_IMAGES; ?>images/del.gif" width="16" height="16" alt="loading..." title="loading..."/></span>
			</div>
			<div class="fr view_rem">
				<span id="repsort_desc_<%= csAtId %>" <%= ascStyle %>> 
					<a href="javascript:void(0);" onclick="sortreply(<%= '\''+csAtId+'\'' %>,<%= '\''+csUniqId+'\'' %>)" rel="tooltip" title="View oldest thread on top">Newer</a>
				</span>
				<span id="repsort_asc_<%= csAtId %>" <%= descStyle %> > 
					<a href="javascript:void(0);" onclick="sortreply(<%= '\''+csAtId+'\'' %>,<%= '\''+csUniqId+'\'' %>)" rel="tooltip" title="View newest thread on top">Older</a>
				</span>
				<span class="rep_st_icn"></span>
				<span id="loadreply_sort_<%= csAtId %>" style="visibility: hidden;"><img src="<?php echo HTTP_IMAGES; ?>images/del.gif" width="16" height="16" alt="loading..." title="loading..."/></span>
			</div>
		  </div>
		  <div class="cb"></div>
		  <input type="hidden" value="less" id="threadview_type<%= csAtId %>" />
		  <input type="hidden" value="<%= thrdStOrd %>" id="thread_sortorder<%= csAtId %>" />
		  <input type="hidden" value="<%= remaining %>" id="remain_case<%= csAtId %>" />
		  <% } %>
		  <div id="reply_content<%= csAtId %>">
		  	<div id="showhidemorereply<%= csAtId %>">
		  		<?php echo $this->element('case_reply'); ?>
		  	</div>
		  </div>
		  <% } %>
		</div><!-- /.row --><!-- Case Detail -->
	
		<input type="hidden" name="data[Easycase][sel_myproj]" id="CS_project_id<%= csAtId %>" value="<%= projUniqId %>" readonly="true">
		<input type="hidden" name="data[Easycase][case_no]" id="CS_case_no<%= csAtId %>" value="<%= csNoRep %>" readonly="true"/>
		<input type="hidden" name="data[Easycase][type_id]" id="CS_type_id<%= csAtId %>" value="<%= csTypRep %>" readonly="true"/>
		<input type="hidden" name="data[Easycase][title]" id="CS_title<%= csAtId %>" value="" readonly="true"/>
		<input type="hidden" name="data[Easycase][priority]" id="CS_priority<%= csAtId %>" value="<%= csPriRep %>" readonly="true"/>
		<input type="hidden" name="data[Easycase][org_case_id]" id="CS_case_id<%= csAtId %>" value="<%= csAtId %>" readonly="true"/>
		<input type="hidden" name="data[Easycase][istype]" id="CS_istype<%= csAtId %>" value="2" readonly="true"/>
		<% if(is_active){ %>
		<div class="reply_task_block" id="reply_box<%= csAtId %>">
			<div class="fl">
				<% if(!usrFileExst){ var usrPhoto = 'user.png'; } %>
				<img data-original="<?php echo HTTP_ROOT; ?>users/image_thumb/?type=photos&file=<%= usrPhoto %>&sizex=60&sizey=60&quality=100" class="lazy round_profile_img asignto" width="60" height="60" title="<%= usrName %>"/>
			</div>
			<div class="fl col-lg-10">
				<i class="icon-reply-yelow"></i>
				<div class="fr">
					<div class="fl">
					<a href="javascript:void(0);" id="custom<%= csAtId %>"  onclick="changeToRte(<%= '\''+csAtId+'\'' %>,<%= '\''+csUniqId+'\'' %>)" style="display:none">HTML Editor</a>
					<a href="javascript:void(0);" id="txt<%= csAtId %>" onclick="changeToRte(<%= '\''+csAtId+'\'' %>,<%= '\''+csUniqId+'\'' %>)" style="display:block">Text Editor</a>
					</div>
					<div class="rep_st_icn"></div>
				</div>
				<div class="cb"></div>
				<div class="col-lg-12 fl">
					<div class="fl lbl-font16" id="hidstatus<%= csAtId %>" style="margin:-2px 16px 5px 0">Write your Reply:&nbsp;</div>
					<div class="col-lg-9 w80p fl">
				<span id="html<%= csAtId %>" style="display:block;">
					<span id="hidhtml<%= csAtId %>" style="display:none;">
						<textarea name="data[Easycase][message]" id="<%= 'txa_comments'+csAtId %>" rows="2" class="col-lg-12"></textarea>
						<span id="htmlloader<%= csAtId %>" style="color:#999999; display: none; float:left;">
							Loading...
						</span>
					</span>
					<span id="showhtml<%= csAtId %>" data-task="<%= csAtId %>">
						<textarea name="data[Easycase][message]" id="<%= 'txa_comments'+csAtId %>" rows="2" class="reply_txt_ipad col-lg-12" style="color:#C8C8C8"></textarea>
					</span>
				</span>
				<span id="plane<%= csAtId %>" style="display:none;">
					<textarea name="data[Easycase][message]" id="txa_plane<%= csAtId %>" rows="1" class="col-lg-12"></textarea>
				</span>
				<input type="hidden" value="1" id="editortype<%= csAtId %>"/>
					</div>	
				</div>				
				<div class="cb"></div>
				<div class="col-lg-12 m-top-20">
					<?php /*User loop */ ?>
				</div>
				<div class="cb"></div>
				


					<% if(csTypRep!=10 ){ 
					var val = ""; %>
					
					<div class="col-lg-12 fl">
						<div class="fl lbl-font16" id="hidstatus<%= csAtId %>">Status:&nbsp;</div>
						<span id="hiddrpdwnstatus<%= csAtId %>">
						<select class="select form-control fl" style="width:170px;" onchange="valforlegend(this.value,'legend<%= csAtId %>')" >
						<% if(csLgndRep == 1) { val = 2; %>
							<option value="1">New</option>
							<option value="2" selected>In Progress</option>
							<option value="3">Close</option>
							<option value="5">Resolve</option>
						<% } else if(csLgndRep == 2 || csLgndRep == 4){ val = 2; %>
							<option value="2" selected=selected >In Progress</option>
							<option value="3">Close</option>
							<option value="5">Resolve</option>
						<% } else if(csLgndRep == 5){ val = 2; %>
							<option value="2" selected=selected >In Progress</option>
							<option value="3">Close</option>
						<% } else if(csLgndRep=="3"){ val = 2; %>
							<option value="2" selected=selected >In Progress</option>
						<% } %>
						</select>
						<input type="hidden" name="legend" id="legend<%= csAtId %>" value="<%= val %>">
						</span>
					</div>
					<% } %>
								
					<div class="cb h20"></div>					
					<div class="col-lg-12 fl">
						<div class="fl lbl-font16">Assign to:</div>
						<select name="data[Easycase][assign_to]" id="CS_assign_to<%= csAtId %>" class="form-control fl" style="width:170px" onchange="select_reply_user(<%= '\''+csAtId+'\'' %>,this);">
						<% if(countJS(allMems)) {
							for(var casekey in allMems) {
								var asgnMem = allMems[casekey];
								if(SES_ID == asgnMem.User.id) {
									if(asgnMem.User.id == Assign_to_user) { %>
									<option value="<%= SES_ID %>" selected>me</option>
									<% } else if(checkAsgn == "self") { %>
									<option value="self" selected>self</option>
									<% } else if(checkAsgn == "NA") { %>
									<option value="NA" selected>NA</option>
									<% } else { %>
									<option value="<%= SES_ID %>">me</option>
									<% }		
								}else if(asgnMem.User.id==Assign_to_user) { %>
									<option value="<%= asgnMem.User.id %>" selected><%= asgnMem.User.name %></option>
								<% } else { %>
									<option value="<%= asgnMem.User.id %>" <% if(checkAsgn == "other" && csUsrAsgn == asgnMem.User.id) { %><% } %>><%= asgnMem.User.name %></option>
								<% 	}
							}
						} else { %>
						<option value="<%= SES_ID %>" selected>me</option> 				
						<% } %>
						</select>
					</div>
				<div class="cb h20"></div>
				<div class="col-lg-12 fl tskmore" id="tskmore_<%= csAtId %>">
					<div class="col-lg-12 fl">
						<div class="fl lbl-font16" style="margin:5px 16px 5px 0">Priority:</div>
						<div class="fl prio_radio y_low" onclick="edited_priority(<%= '\''+csAtId+'\'' %>,this);"><input type="radio" name="task_priority" value="2" id="priority_low" class="" <% if(csPriRep==2){ %>checked="checked" <% } %> />
                        <label tabindex=4 class="pri-label"></label></div>
                        <div class="fl pri_type">Low</div>
						<div class="fl prio_radio g_mid" onclick="edited_priority(<%= '\''+csAtId+'\'' %>,this);"><input type="radio" name="task_priority" value="1" id="priority_mid" class=""  <% if(csPriRep==1){ %>checked="checked" <% } %>  />
                        <label tabindex=4 class="pri-label"></label></div>
                        <div class="fl pri_type">Medium</div>
						<div class="fl prio_radio h_red" onclick="edited_priority(<%= '\''+csAtId+'\'' %>,this);"><input type="radio" name="task_priority" value="0" id="priority_high" class="" <% if(csPriRep==0){ %>checked="checked" <% } %> />
                        <label tabindex=4 class="pri-label"></label></div>
                        <div class="fl pri_type">High</div>
					</div>
					
				
					<div class="cb h20"></div>
					
					
					<div class="col-lg-12 fl">
						<div class="fl lbl-font16">Hour(s) Spent:</div>
						<input type="text" class="form-control hrs_box" style="font-size: 13px;width:80px" rel="tooltip"  title="You can enter time as 1.5 (that  mean 1 hour and 30 minutes)." maxlength="6" name="data[Easycase][hours]" id="hours<%= csAtId %>" onkeypress="return numericDecimal(event)"/>
					</div>
					<div class="cb h20"></div>

					<% if(csLgndRep != 0){ %>
					<div class="col-lg-12 fl">
						<div class="fl lbl-font16">Completed:</div>
						<select class="form-control fl" style="width:80px;"  id="completed<%= csAtId %>" >
							<% if(csLgndRep == 1 || csLgndRep == 2 || csLgndRep == 4 || csLgndRep == 0){ %>
							<option value="0" <% if(completedtask == 0){ %> selected <% }else{ %>""<%} %>>0</option>
							<option value="10" <% if(completedtask == 10){ %> selected <% }else{ %>""<%} %>>10</option>
							<option value="20" <% if(completedtask == 20){ %> selected <% }else{ %>""<%} %>>20</option>
							<option value="30" <% if(completedtask == 30){ %> selected <% }else{ %>""<%} %>>30</option>
							<option value="40" <% if(completedtask == 40){ %> selected <% }else{ %>""<%} %>>40</option>
							<option value="50" <% if(completedtask == 50){ %> selected <% }else{ %>""<%} %>>50</option>
							<option value="60" <% if(completedtask == 60){ %> selected <% }else{ %>""<%} %>>60</option>
							<option value="70" <% if(completedtask == 70){ %> selected <% }else{ %>""<%} %>>70</option>
							<option value="80" <% if(completedtask == 80){ %> selected <% }else{ %>""<%} %>>80</option>
							<option value="90" <% if(completedtask == 90){ %> selected <% }else{ %>""<%} %>>90</option>
							<option value="100" <% if(completedtask ==100){ %> selected <% }else{ %>""<%} %>>100</option>  
							<% }else if(csLgndRep == 5 || csLgndRep == 3){ %>
							<option value="0" >0</option>
							<option value="10">10</option>
							<option value="20">20</option>
							<option value="30">30</option>
							<option value="40">40</option>
							<option value="50">50</option>
							<option value="60">60</option>
							<option value="70">70</option>
							<option value="80">80</option>
							<option value="90">90</option>
							<option value="100" selected>100</option>
							<% } %>
						</select><div class="fl pad-6">%</div>
					</div>
					<% } %>
					
					<div class="cb" style="height:10px;"></div>
					
					<input type="hidden" name="totfiles" id="totfiles<%= csAtId %>" value="0" readonly="true">
					<form class="upload<%= csAtId %> attch_form" id="file_upload<%= csAtId %>" action="<?php echo HTTP_ROOT; ?>easycases/fileupload/" method="POST" enctype="multipart/form-data">
						<div class="fl" style="margin-top:10px;">
							<!--<span class="customfile-button" aria-hidden="true">Browse</span>-->
							<div class="fl lbl-font16 attch_ipad" style="margin:0 16px 10px 0">Attachment(s):</div>
							<div id="holder_detl" style="" class="fl">
							    <div class="customfile-button fl" style="right:0">
								    <input class="customfile-input fl" name="data[Easycase][case_files]" id="tsk_attach<%= csAtId %>" type="file" multiple=""  style="width:230px;height:66px;"/>
								    <div class="att_fl fl" style="margin-right:5px"></div><div class="fr">Select multiple files to upload...</div>
							    </div>
							    <div style="margin-left:4px;color:#F48B02;font-size:13px;" class="fnt999">Drag and Drop files to Upload</div>
							    <div class="fnt999">Max size <%= MAX_FILE_SIZE %> Mb</div>
							</div>
						</div>
                        <?php 
						if($user_subscription['btprofile_id'] || $user_subscription['is_free'] || $GLOBALS['FREE_SUBSCRIPTION'] == 0) {
							$is_basic_or_free = 0;
						} else {
							$is_basic_or_free = 1;
						}
						if($user_subscription['is_cancel']) {
							$is_basic_or_free = 0;
						}
						?>
                        <?php if(USE_DROPBOX == 1 || USE_GOOGLE == 1){?>
						<div class="fr" style="width:248px">
                        	<?php if(USE_DROPBOX == 1) { ?>
							<div class="fr btn-al-mr">
								<button type="button" class="customfile-button" onclick="connectDropbox(<%= csAtId %>,<?php echo $is_basic_or_free;?>);">
									<span class="icon-drop-box"></span>
									Dropbox
								</button>
							</div>
                            <?php } ?>
                            <?php if(USE_GOOGLE == 1) { ?>
							<div class="btn-al-mr">
								<button type="button" class="customfile-button" onclick="googleConnect(<%= csAtId %>,<?php echo $is_basic_or_free;?>);">
									<span class="icon-google-drive"></span>
									Google Drive
								</button>
								<span id="gloader" style="display: none;">
									<img src="<?php echo HTTP_IMAGES;?>images/del.gif" style="position: absolute;bottom: 95px;margin-left: 125px;"/>
								</span>
							</div>
                            <?php } ?>
						</div>
                        <?php } ?>
						<div class="cb"></div>
					</form>
					<div id="table1">
					<table class="up_files<%= csAtId %>" id="up_files<%= csAtId %>" style="font-weight:normal;margin-left:146px">
                                        </table>
                                        </div>
					<div id="drive_tr_<%= csAtId %>" style="margin-left: 146px;margin-bottom:15px;">
						<form id="cloud_storage_form_<%= csAtId %>" name="cloud_storage_form_<%= csAtId %>"  action="javascript:void(0)" method="POST">
							<div style="float: left;margin-top: 7px;" id="cloud_storage_files_<%= csAtId %>"></div>
						</form>
						<div style="clear: both;margin-bottom: 3px;"></div>
					</div>
				</div>
					<div>
						<span class="lbl-font16">Notify via Email:</span> <input type="checkbox" name="chkAllRep" style="margin-left:10px;" id="<%= csAtId %>chkAllRep" value="all" class="clsptr" onclick="checkedAllResReply('<%= csAtId %>')" <% if(allMems.length == usrArr.length) { %> checked="checked" <% } %> /> All
					</div>
					<div class="cb"></div>
					<div class="lbl-font16 fl"></div>
					<% 	var i = 0;
					if(countJS(allMems)){ %>
						<div id="mem<%= csAtId %>">
							<div  id="viewmemdtls<%= csAtId %>" class="tbl_check_name fl">
							<table cellpadding="1" cellspacing="1" border="0" width="100%">
							<% for(var memkey in allMems){
								var getAllMems = allMems[memkey];
								var j = i%3;
								if(j == 0)	{ %>
								<tr>
								<% } %>
									<td align="left" valign="top"  style="font-weight:normal;color:#4B4B4B;"> 
									<input type="checkbox" name="data[Easycase][user_emails][]" id="<%= csAtId %>chk_<%= getAllMems.User.id %>" value="<%= getAllMems.User.id %>" style="cursor:pointer;" class="chk_fl" onClick="removeAllReply('<%= csAtId %>')" <% if($.inArray(getAllMems.User.id,usrArr)!=-1){ %> checked <% } %> />
									<span class="det_nm_wd" title="<%= shortLength(getAllMems.User.name,18) %>"><%= shortLength(getAllMems.User.name,18) %></span>
									<input type="hidden" name="data[Easycase][proj_users][]" id="proj_users"  value="<%= getAllMems.User.id %>" readonly="true" />
									</td>
								<% i = i+1; var k = i%3;
								if(k == 0){ %>
								</tr>
							<% 	}
							} %>
								<tr>
									<input type="hidden" name="hidtotresreply" id="hidtotresreply<%= csAtId %>" value="<%= i %>" readonly="true" />
									<td colspan="3"></td>
								</tr>
							</table>
							</div>
						</div>
					<% } %>
				
				
				
				<div class="cb"></div>
				<div class="col-lg-12 m-top-20">
					<div class="fl lbl-font16 lbl_cs_det_125">&nbsp;</div>
					<div class="fr mor_toggle tasktoogle" style="float:left" id="mor_toggle<%= csAtId %>" data-csatid="<%= csAtId %>"><a href="javascript:jsVoid();" style="text-decoration:none"><img src="<?php echo HTTP_IMAGES;?>priority.png" title="Priority" rel="tooltip"/>&nbsp;&nbsp;<img src="<?php echo HTTP_IMAGES;?>hours.png" title="Hours Spent and % Completed" rel="tooltip"/>&nbsp;&nbsp;<img src="<?php echo HTTP_IMAGES;?>attachment.png" title="Attachments, Google Drive, Dropbox" rel="tooltip"/>&nbsp;&nbsp;More Options<b class="caret"></b></a></div>
					<div class="fr less_toggle tasktoogle" id="less_toggle<%= csAtId %>" data-csatid="<%= csAtId %>" style="display:none;float:left"><a href="javascript:jsVoid();" style="text-decoration:none">Less<b class="caret"></b></a></div>
				</div>
				
				<div class="cb"></div>
				<div class="col-lg-12 m-top-20">
					<div class="fl lbl-font16 lbl_cs_det"></div>
					<span id="postcomments<%= csAtId %>">
						<button class="btn btn_blue" type="button" name="data[Easycase][postdata]" onclick="return validateComments(<%= '\''+csAtId+'\',\''+csUniqId+'\',\''+csLgndRep+'\',\''+SES_TYPE+'\',\''+csProjIdRep+'\'' %>);"><i class="icon-big-tick"></i>Post</button>
                        <span class="or_cancel">or</span>
						<button class="task_detail_back or_cancel" type="reset" id="rset"><i class="icon-big-cross"></i>Cancel</button>
					</span>
					<span id="loadcomments<%= csAtId %>" style="display:none;">
						<img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="Loading..." title="Loading..." style="padding:5px;"/>
					</span>
					<input type="hidden" value="<%= total %>" id="hidtotrp<%= csAtId %>" />
					


				</div>			
			</div>
			<div class="cb"></div>
		</div>
		<% } %>
		<?php /*?><div class="reply_task_block">
			<div class="fl">
				<% if(asgnPic && asgnPic!=0) { %>
				<img data-src="<?php echo HTTP_ROOT; ?>users/image_thumb/?type=photos&file=<%= asgnPic %>&sizex=60&sizey=60&quality=100" src="<?php echo HTTP_ROOT; ?>users/image_thumb/?type=photos&file=user.png&sizex=60&sizey=60&quality=100" class="lazy round_profile_img asignto" width="60" height="60" title="<%= asgnTo %>"/>
				<% } else { %>
				<img src="<?php echo HTTP_ROOT; ?>users/image_thumb/?type=photos&file=user.png&sizex=60&sizey=60&quality=100" class="round_profile_img asignto" title="<%= asgnTo %>" width="60" height="60" />
				<% } %>
			</div>
			<div class="fl col-lg-10">
				<i class="icon-reply-yelow"></i>
				<textarea class="col-lg-12" placeholder="Enter your Comment"></textarea>
				<div class="cb"></div>
				<div class="col-lg-12 m-top-20">
					<div class="lbl-font16">Email this reply to: <input type="checkbox"> All User <a href="">Or, Loop-in some selective users.</a></div>
				</div>
				<div class="cb"></div>
				<div class="col-lg-12 m-top-20 fl">
					<a href="javascript:jsVoid();" class="wink">
						<button class="btn gry_btn task_con" type="button">
						<i class="icon-task-priority"></i>
						</button>
					</a>				
					<a href="javascript:jsVoid();" class="wink">
						<button class="btn gry_btn task_con" type="button">
						<i class="icon-task-asign"></i>
						</button>
					</a>
					<div class="btn gry_btn time_spent" style="margin-right:-5px;">
						<i class="icon-task-time"></i>
						<input type="text" class="form-control fr" placeholder="0.0" />
					</div>
					<div class="btn gry_btn time_spent">
						<i class="icon-reply-cmpl"></i>
						<input type="text" class="form-control fr" placeholder="50" />
					</div>
					
					<div class="col-lg-5 m-top-20 fr" style="text-align:right">
						<a href="javascript:jsVoid();" class="wink">
							<button class="btn gry_btn task_con" type="button">
							<i class="icon-attach-hide m-106"></i>
							</button>
						</a>
						<a href="javascript:jsVoid();" class="wink">
							<button class="btn gry_btn task_con" type="button">
							<i class="icon-gdrive m-10"></i>
							</button>
						</a>
						<a href="javascript:jsVoid();" class="wink">
							<button class="btn gry_btn task_con" type="button">
							<i class="icon-dbox m-8"></i>
							</button>
						</a>					
					</div>
				</div>
				<div class="cb"></div>
				<div class="col-lg-12 m-top-20">				
					<button class="btn btn_blue" type="submit"><i class="icon-big-tick"></i>Post</button>
					<button class="btn btn_grey" type="reset" id="rset" onclick="closePopup();"><i class="icon-big-cross"></i>Cancel</button>
					<a href="javascript:jsVoid();" onclick="slide_form_more();"><div class="fr mor_toggle">More Options<b class="caret"></b></div></a>
					<a href="javascript:jsVoid();" onclick="slide_form_less();"><div class="fr less_toggle" style="display:none">Less Options<b class="caret"></b></div></a>
				</div>				
			</div>
			<div class="cb"></div>
		</div><?php */?>
	</div><!-- /.page-wrapper -->
	</div>
	<div class="col-lg-3 fl col_task case_det_rt">
		<a href="javascript:void(0);" onclick="reloadTaskDetail(<%= '\''+ csUniqId+'\'' %>);">
			<div class="btn gry_btn smal30" rel="tooltip" title="Reload">
				<i class="icon-reload"></i>
			</div>
		</a>
		<% if(!is_active){ %>
		<a href="javascript:void(0);" onclick="restoreTaskDetail(<%= '\''+ csUniqId+'\',\''+csNoRep+'\'' %>);">
		    <div class="btn gry_btn smal30" rel="tooltip" title="Restore" style="padding-right:20px;">
				<i class="icon-restore"></i>
			</div>
		</a>
		<% } %>
		<% if(is_active && ((!CSrepcount) &&  csLgndRep ==1 && (SES_TYPE == 1 || SES_TYPE == 2 || (csUsrDtls== SES_ID))) ){ %>
		<a href="javascript:void(0);" onclick="editask(<%= '\''+ csUniqId+'\',\''+projUniqId+'\',\''+projName+'\'' %>);">
			<div class="btn gry_btn smal30" rel="tooltip" title="Edit">
				<i class="icon-edit"></i>
			</div>
		</a>
		<% } %>
		<?php /*?>
		<% if(csLgndRep == 1 && csTypRep!= 10) { %>
		<a href="javascript:void(0);" onclick="startCase(<%= '\'' + csAtId + '\'' %>, <%= '\'' + csNoRep + '\'' %>, <%= '\'' + csUniqId + '\'' %>);">
			<div class="btn gry_btn smal30" rel="tooltip" title="Start">
				<i class="act_icon act_start_task fl"></i>
			</div>
		</a>
		<% } %><?php */?>
		<% if(is_active && (SES_TYPE == 1 || SES_TYPE == 2 || ((csLgndRep == 1 || csLgndRep == 2 || csLgndRep == 4) && ( SES_ID == csUsrDtls)))) { %>
		<a href="javascript:void(0);" onclick="archiveCase(<%= '\'' + csAtId + '\'' %>, <%= '\'' + csNoRep + '\'' %>, <%= '\'' + csProjIdRep + '\'' %>, <%= '\'t_' + csUniqId + '\'' %>);">
			<div class="btn gry_btn smal30" rel="tooltip" title="Archive">
				<i class="icon-arch"></i>
			</div>
		</a>
		<% } if(SES_TYPE == 1 || SES_TYPE == 2 || (csLgndRep == 1  && SES_ID == csUsrDtls)) { %>
		<a href="javascript:void(0);" onclick="deleteCase(<%= '\'' + csAtId + '\'' %>, <%= '\'' + csNoRep + '\'' %>, <%= '\'' + csProjIdRep + '\'' %>, <%= '\'t_' + csUniqId + '\'' %>);">
			<div class="btn gry_btn smal30" rel="tooltip" title="Delete">
				<i class="icon-delet"></i>
			</div>
		</a>
		<% } if(is_active && ((is_active && csLgndRep == 1 || csLgndRep == 2 || csLgndRep == 4) && csTypRep!= 10)) { %>
		<a href="javascript:void(0);" onclick="caseResolve(<%= '\'' + csAtId + '\'' %>, <%= '\'' + csNoRep + '\'' %>, <%= '\'' + csUniqId + '\'' %>);">
			<div class="btn gry_btn smal30" rel="tooltip" title="Resolve">
				<i class="icon-closs"></i>
			</div>
		</a>
		<% } if(is_active && ((csLgndRep == 1 || csLgndRep == 2 || csLgndRep == 4 || csLgndRep == 5) && csTypRep != 10)) { %>
		<a href="javascript:void(0);" onclick="setCloseCase(<%= '\'' + csAtId + '\'' %>, <%= '\'' + csNoRep + '\'' %>, <%= '\'' + csUniqId + '\'' %>);">
			<div class="btn gry_btn smal30" rel="tooltip" title="Close">
				<i class="icon-resol"></i>
			</div>
		</a>
		<% } %>
		<a href="javascript:void(0);" onclick="downloadTask(<%= '\''+ csUniqId+'\'' %>,<%= '\'' + csNoRep + '\'' %>);">
			<div class="btn gry_btn smal30" rel="tooltip" title="Download">
				<i class="icon-taskdownl"></i>
			</div>
		</a>
		<div class="cb"></div>
		<hr/>
		<div>
			<div class="asign_block">
				<div class="fl icon-asign-to"></div>
				<div id="case_dtls_asgn<%= csAtId %>" class="fl asgn_actions <% if(showQuickAct==1){ %> dropdown<% } %>">
					<span <% if(showQuickAct==1){ %> class="quick_action" data-toggle="dropdown"<% } %> onclick="displayAssignToMem(<%= '\'' + csAtId + '\'' %>, <%= '\'' + projUniqId + '\'' %>,<%= '\'' + asgnUid + '\'' %>,<%= '\'' + csUniqId + '\'' %>,<%= '\'details\'' %>,<%= '\'' + csNoRep + '\'' %>)">Assigned To</span>
					<% if(showQuickAct==1){ %>
					<ul class="dropdown-menu quick_menu" id="detShowAsgnToMem<%= csAtId %>">
						<li class="text-centre"><img src="<?php echo HTTP_IMAGES; ?>images/del.gif" id="detAssgnload<%= csAtId %>" /></li>
					</ul>
					<% } %>
				</div>
				<div class="fl" id="detasgnlod<%= csAtId %>" style="display:none">
					<img src="<?php echo HTTP_IMAGES; ?>images/del.gif" alt="Loading..." title="Loading..."/>
				</div>
			</div>
			<div class="cb"></div>
			<div class="fl">
				<% if(asgnPic && asgnPic!=0) { %>
				<img src="<?php echo HTTP_ROOT; ?>users/image_thumb/?type=photos&file=<%= asgnPic %>&sizex=60&sizey=60&quality=100" class="round_profile_img asignto" title="<%= asgnTo %>" width="60" height="60" />
				<% } else { %>
				<img src="<?php echo HTTP_ROOT; ?>users/image_thumb/?type=photos&file=user.png&sizex=60&sizey=60&quality=100" class="round_profile_img asignto" title="<%= asgnTo %>" width="60" height="60" />
				<% } %>
			</div>
			<div class="fl">
				<span><b id="case_dtls_new<%= csAtId %>" class="ttc"><%= shortLength(asgnTo,15) %></b></span>
				<div class="fnt999"><%= asgnEmail %></div>
			</div>
		</div>
		<div class="cb"></div>
		<hr/>
		<div class="task_due_dt">
			<div class="cb"></div>
			<div class="fl icon-due-date"></div>
			<div id="case_dtls_due<%= csAtId %>" class="fl due_actions <% if(showQuickActDD==1){ %> dropdown<% } %>">
				<% if(csDuDtFmt) { %>
				<div title="<%= csDuDtFmtT %>" rel="tooltip" <% if(showQuickActDD==1){ %> class="fl duequick_action" data-toggle="dropdown"<% } else { %> class="fl" <% } %>><%= csDuDtFmt %></div>
				<% } else { %>
				<div <% if(showQuickActDD==1){ %> class="no_due_dt duequick_action" data-toggle="dropdown"<% } else { %> class="fl no_due_dt" <% } %>><span class="due-txt">No Due Date</span></div>
				<% } %>
				<% if(showQuickActDD==1){ %>
				<ul class="dropdown-menu quick_menu">
					<li class="pop_arrow_new"></li>
					<li><a href="javascript:void(0);" onclick="detChangeDueDate(<%= '\'' + csAtId + '\', \'00/00/0000\', \'No Due Date\', \'' + csUniqId + '\', \'' + csNoRep + '\'' %>)">No Due Date</a></li>
					<li><a href="javascript:void(0);" onclick="detChangeDueDate(<%= '\'' + csAtId + '\', \'' + mdyCurCrtd + '\', \'Today\', \'' + csUniqId + '\', \'' + csNoRep + '\'' %>)">Today</a></li>
					<li><a href="javascript:void(0);" onclick="detChangeDueDate(<%= '\'' + csAtId + '\', \'' + mdyTomorrow + '\', \'Tomorrow\', \'' + csUniqId + '\', \'' + csNoRep + '\'' %>)">Tomorrow</a></li>
					<li><a href="javascript:void(0);" onclick="detChangeDueDate(<%= '\'' + csAtId + '\', \'' + mdyMonday + '\', \'Next Monday\', \'' + csUniqId + '\', \'' + csNoRep + '\'' %>)">Next Monday</a></li>
					<li><a href="javascript:void(0);" onclick="detChangeDueDate(<%= '\'' + csAtId + '\', \'' + mdyFriday + '\', \'This Friday\', \'' + csUniqId + '\', \'' + csNoRep + '\'' %>)">This Friday</a></li>
					<li>
						<a href="javascript:void(0);" class="cstm-dt-option" data-csatid="<%= csAtId %>">
							<input value="" type="hidden" id="det_set_due_date_<%= csAtId %>" class="set_due_date" title="Custom Date" style=""/>
							<span style="position:relative;top:2px;cursor:text;">Custom&nbsp;Date</span>
						</a>
					</li>
				</ul>
				<% } %>
			</div>
			<span id="detddlod<%= csAtId %>" style="display:none">
				<img src="<?php echo HTTP_IMAGES; ?>images/del.gif" alt="Loading..." title="Loading..."/>
			</span>
		</div>
		<div class="cb"></div>
		<hr/>
		<div class="task_due_dt">
			<div class="cb"></div>
			<div class="fl icon-set-attach"></div>
		<% var fc = 0;
		if(all_files.length) { %>
			<div class="fl">
				<span>Files in this Task</span>
			</div>
		<% var imgaes = ""; var caseFileName = "";
		for(var fkey in all_files){
			var getFiles = all_files[fkey];
			caseFileName = getFiles.CaseFile.file;
			downloadurl = getFiles.CaseFile.downloadurl;
			if(getFiles.CaseFile.is_exist) {
				fc++;
				if(fc==6) {
			%>
			<div class="tsk_files_more">
			<% } %>
			<div class="cb"></div>
			<div class="fl upload_icn"><div class="tsk_fl <%= easycase.imageTypeIcon(getFiles.CaseFile.format_file) %>_file"></div></div>
			<div class="fl">
				<% if(getFiles.CaseFile.is_ImgFileExt){ %>
				<% if(downloadurl){ %>
				    <span class="gallery"><a href="<%= downloadurl %>" target="_blank" alt="<%= caseFileName %>" title="<%= caseFileName %>" rel="prettyImg[]"><%= shortLength(caseFileName,25) %></a></span>
				<% } else { %>
				    <span class="gallery"><a href="<?php echo HTTP_ROOT; ?>easycases/download/<%= caseFileName %>" alt="<%= caseFileName %>" title="<%= caseFileName %>" rel="prettyImg[]"><%= shortLength(caseFileName,25) %></a></span>
				<% 	} 
				} else{
					if(downloadurl) { %>
						<a href="<%= downloadurl %>" target="_blank" alt="<%= caseFileName %>" title="<%= caseFileName %>"><%= shortLength(caseFileName,25) %></a>
					<% } else { %>
						<a href="<?php echo HTTP_ROOT; ?>easycases/download/<%= caseFileName %>" alt="<%= caseFileName %>" title="<%= caseFileName %>"><%= shortLength(caseFileName,25) %></a>
					<% }
				} %>
				<div class="fnt999" style="line-height: 3px;"><%= getFiles.CaseFile.file_date %></div>
			</div>
			<% } %>
			<% }
			if(fc>5) { %>
			</div>
			<div class="cb"></div>
			<div class="fr ftsk_more">
				<a class="more_in_menu" href="javascript:;">More</a><b class="menu_more_arr file_more"></b>
			</div>
		<% }
		} if(fc==0) { %>
		<div class="fl">
			<span class="no_due_dt">No Files in this Task</span>
		</div>
		<% } %>
		</div>
		<div class="cb"></div>	
		<hr/>
		<div class="task_due_dt">
			<div class="cb"></div>
			<div class="fl icon-activity"></div>
			<div class="fl">
				<span>Activities</span><br/>
			</div>
			<div class="cb"></div>
			<div class="activ_listing">
				<div><span>Created:</span> <%= frmtCrtdDt %></div>
				<div><span>Last Updated:</span> <%= lupdtm %></div>
				<% if(lstRes) { %>
				<div class="col_r"><span>Resolved:</span> <%= lstRes %></div>
				<% }
				if(lstCls) { %>
				<div class="col_g"><span>Closed:</span> <%= lstCls %></div>
				<% } %>
			</div>
		</div>
		<div class="cb"></div>	
		<hr/>
		<div class="task_due_dt">
			<div class="cb"></div>
			<div class="fl icon-p-invol"></div>
			<div class="fl">
				<span>People Involved</span><br/>
			</div>
		</div>
		<div class="cb"></div>
		<% for(i in taskUsrs) { %>
		<div class="fl">
			<% var upic = 'user.png';
			if(taskUsrs[i].User.photo && taskUsrs[i].User.photo!=0) {
				var upic = taskUsrs[i].User.photo;
			} %>
			<img src="<?php echo HTTP_ROOT; ?>users/image_thumb/?type=photos&file=<%= upic %>&sizex=40&sizey=40&quality=100" class="round_profile_img ppl_invol" title="<%= ucwords(formatText(taskUsrs[i].User.name+' '+taskUsrs[i].User.last_name)) %>" width="40" height="40" rel="tooltip" />
		</div>
		<% } %>
	</div>
	<div class="cb"></div>	
</div>

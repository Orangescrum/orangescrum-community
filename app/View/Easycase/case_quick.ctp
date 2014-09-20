<!--[if IE 8]>
    <style>
        .customfile{width: 260px; font-size:12px;}
        .none_disp{width:613px;}
        .cs_txtarea{ max-width: 580px;}
    </style>	
<![endif]-->
<?php
$curdate = gmdate("Y-m-d H:i:s");
$userDate = $this->Tmzone->GetDateTime(SES_TIMEZONE,TZ_GMT,TZ_DST,TZ_CODE,$curdate,"datetime");

$curDay = date('D',strtotime($userDate));
$friday = date('Y-m-d',strtotime($userDate."next Friday"));
$monday = date('Y-m-d',strtotime($userDate."next Monday"));
$tomorrow = date('Y-m-d',strtotime($userDate."+1 day"));

$titleValue = "Daily Update - ".date("m/d");
?>
<script type="text/javascript">
	function htmlEntities(str) {
		return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
	}
	function showTemplates(id,name) {
		//$(".selected_val").html(name);
		var isDataPresent = false;
		$(".selected_val").html(htmlEntities(name));
		var GetContent = tinyMCE.activeEditor.getContent();
		//alert(GetContent);alert(id);alert(name);
		
		if(!GetContent){ //The data will be load first time
			isDataPresent = true;
		}
		
		if(GetContent && id != "New"){ //It will check whether data is present or not and Not Reseting the field
			if(confirm("Your description will be replaced by this \""+name+"\" Template")){
				isDataPresent = true;
			}else{
				isDataPresent = false;
			}
		}	
		
		if(id == 'New'){
			tinyMCE.activeEditor.setContent('');
		}
		
		if(isDataPresent == true){
			if(name == ''){
				   document.getElementById("defaultoption").style.display='none';
			}else{
				   document.getElementById("defaultoption").style.display='block';
			}
			tinyMCE.activeEditor.setContent('');
			
			if(id != "New") {
				var strURL = HTTP_ROOT+"easycases/";
				document.getElementById('CS_message_ifr').disable = true;
				$("#CS_message_ifr").hide();
				$.post(strURL+"ajax_case_template",{"tmpl_id":id},function(data) {
					$("#CS_message_ifr").show();
					if(data) {
						tinyMCE.activeEditor.setContent(data);
					}
				});
			}
			$('#openpopup_tmpl').css({display:"none"});
		}else{
			return false;
		}	
	}
	function hide_pri(val) {
		document.getElementById("CS_title").value = val;
	}
	/*$("#tinymce body").click(function() {
		$("#case_tmpl").hide();
	});*/
	$(document).ready(function() {
		
		$(".more_opt ul li a").click(function() {
			var text = $(this).html();
			var path=$(this).parent("li").parent("ul").parent("div").prev("div").attr("id");
			$("#"+path).children("a").children("span").html(text);
			
			if(path =="opt3")
			{
				var hidden_val=$("#" + path).find("a span.value").html();
				$("#date_dd").html(hidden_val);
				$("#CS_due_date").val(hidden_val);
				
			}
			else if(path =="opt2"){
				//alert("Hello");
				$("#CS_priority").val(getSelectedValue("opt2"));
			}else if(path =="opt4"){
				$("#CS_milestone").val(getSelectedValue("opt4"));
			}else if(path =="opt5"){
                    $("#CS_assign_to").val(getSelectedValue("opt5"));
               }
			else
			{
				$("#CS_type_id").val(getSelectedValue("opt1"));
				$('#task_priority_td').show();
//				$("#hd1").show();
//				$("#hd2").show();
				
				if($("#CS_type_id").val() == 10){
//					$("#hd1").hide();
//					$("#hd2").hide();
					$('#task_priority_td').hide();
					$("#CS_title").val('<?php echo $titleValue; ?>');
					document.getElementById("CS_title").style.color='#000';
				}
				else if($("#CS_type_id").val() != 10 && $("#CS_title").val() == '<?php echo $titleValue; ?>')
				{
					document.getElementById("CS_title").value ="";
				}
			}
			$("#"+path).next("div").children("ul").hide();
		});
		
		function getSelectedValue(id) {
            return $("#" + id).find("a span.value").html();
	    }
		
		$(document).bind('click', function(e) {
			var $clicked = $(e.target);
			if (! ($clicked.parents().hasClass("dropdown")) && !($('#ui-datepicker-div').is(":visible"))){
				$(".dropdown .more_opt ul").hide();
			}
		});
	});
	/*function open_more_opt(more_opt){//alert(more_opt);
		$("#"+more_opt).children("ul").toggle();
	}*/
	function open_more_opt(more_opt){
		$('.more_opt').filter(':not(#'+more_opt+')').children('ul').hide();
		$("#"+more_opt).children("ul").toggle();
	}
     

$(function() {
	$( "#due_date" ).datepicker({
		altField: "#CS_due_date",
		showOn: "button",
		buttonImage: "<?php echo HTTP_IMAGES."images/calendar.png";?>",
		buttonStyle: "background:#FFF;",
		changeMonth: false,
		changeYear: false,
		minDate: 0,
		hideIfNoPrevNext: true,
		onSelect: function(dateText, inst) {
			$("#date_dd").html(dateText);
			$("#more_opt3").children("ul").hide();	
		}
		
	});
$( "#start_date1" ).datepicker({
		altField: "#CS_start_date",
		changeMonth: false,
		changeYear: false,
		minDate: 0,
		hideIfNoPrevNext: true
		
	});
	$( "#end_date1" ).datepicker({
		altField: "#CS_end_date",
		changeMonth: false,
		changeYear: false,
		minDate: 0,
		hideIfNoPrevNext: true
		
	});
});
</script>
<script type="text/javascript" src="<?php echo JS_PATH; ?>fileupload.js"></script>
<input type="hidden" name="totfiles" id="totfiles" value="0" readonly="true">

<input type="hidden" id="CS_type_id" value="<?php if(isset($taskdetails) && $taskdetails['type_id']){echo $taskdetails['type_id'];}else{?>2 <?php }?>">
<input type="hidden" id="CS_priority" value="<?php if(isset($taskdetails) && $taskdetails['priority']){echo $taskdetails['priority'];}else{?>1 <?php }?>">
<input type="hidden" id="CS_due_date" value="<?php if(isset($taskdetails) && $taskdetails['due_date']){echo date('m/d/Y',strtotime($taskdetails['due_date']));}else{?>No Due Date<?php }?>">
<input type="hidden" id="CS_milestone" value="">

<!--<span id="ajxQuickMem" style="display:block"></span>-->
<div class="head_back"></div>
<div id="cover" class="outer"></div>
<div id="pagefade" class="pagefade" style="z-index:0"></div>
<?php //echo $this->element('popup'); ?>
<div style="position:fixed; left:0px; cursor:pointer; display:none; z-index:9" id="show_lpanel">
	<img src="<?php echo HTTP_IMAGES;?>images/hide_panel.png" />
</div>
<div class="">
<div class="case_field">
	<?php
	$today = $this->Tmzone->GetDateTime(SES_TIMEZONE,TZ_GMT,TZ_DST,TZ_CODE,GMT_DATETIME,"date");
	$curDate = date("m/d/Y",strtotime($today));
	if($user_subscription['btprofile_id'] || $user_subscription['is_free'] || ($user_subscription['subscription_id']>1 && !$user_subscription['is_cancel']) ){
			$is_basic_or_free = 0;
		}else{
			$is_basic_or_free = 1;
		}
	?>
	<table cellpadding="0" cellspacing="0" width="100%">
		 	<tr>
				<td>
					<table cellpadding="0" cellspacing="0">
						<tr>
							<td class="case_fieldprof" >
								<div class="fl lbl-m-wid">Task Type:</div>
							</td>
							<td align="left">
								<div id="sample" class="dropdown option-toggle p-6 fl">
									<div class="opt1" id="opt1">
										<a href="javascript:jsVoid()" onclick="open_more_opt('more_opt');">
											<span class="">
											<?php if(isset($taskdetails) && $taskdetails['type_id']){
												foreach($select as $k=>$v){
													if($v['Type']['id'] == $taskdetails['type_id']){?>
														<img class="flag" src="<?php echo HTTP_IMAGES.'images/types/'.$v['Type']['short_name'].'.png';?>" alt="type" style="padding-top:3px;"/>&nbsp;<?php echo $v['Type']['name'];?>
												<?php break; }
												}
											}else{?>
												<img class="flag" src="<?php echo HTTP_IMAGES.'images/types/dev.png';?>" alt="type" style="padding-top:3px;"/>&nbsp;Development
											<?php }?>
											</span> 
											<i class="caret mtop-10 fr"></i>
										</a>
								</div>
								<div class="more_opt" id="more_opt">
									<ul>
										<?php
										foreach($select as $k=>$v){
											foreach($v as $key=>$value){
												foreach($value as $key1=>$result){
													if($key1=='name'&& $key1='short_name'){
														//$im = $value['short_name'].".png";
														$im1= $this->Format->todo_typ_src($value['short_name'],$value['name']);;
														echo "<li>
																<a href='javascript:jsVoid()'>
																	<img class='flag' src='".$im1."' alt='' />
																	<span class='value'>".$value['id']."
																	</span>".$value['name']."
																</a>
															</li>";
												 }
											  }
											}
										}?>
									 </ul>
								</div>
							</div>
						</td>
					</tr>
				</table>

			</td>
		</tr>		
		<tr>
			<td>

				<table cellpadding="0" cellspacing="0" style="margin:10px 0 3px">
					<tr>
					<td class="case_fieldprof">
						<div class="fl lbl-m-wid">Assign To:</div>

					</td>
					<td align="left">
						<div id="sample1" class="dropdown option-toggle p-6" >
							<div class="opt1" id="opt5">
								<a href="javascript:jsVoid()" onclick="open_more_opt('more_opt5');">
								<span>
								<?php  
								if($taskdetails['assign_to']){
									
									if($taskdetails['assign_to']== SES_ID){
										echo "me";
									}else{
										$userNam =$this->Casequery->getusrname($taskdetails['assign_to']);
										echo $userNam['User']['name'];
									}?>
									<script type="text/javascript">$('#CS_assign_to').val('<?php echo $taskdetails['assign_to'];?>');</script>
								<?php }elseif($defaultAssign && $defaultAssign != SES_ID){
									$userNam = $this->Casequery->getusrname($defaultAssign);?>
									<script type="text/javascript">$('#CS_assign_to').val('<?php echo $defaultAssign;?>');</script>
								<?php echo $userNam['User']['name']; ?>
								<?php }else{?>&nbsp;&nbsp;me<?php } ?>
								</span>
								<i class="caret mtop-10 fr"></i>
								</a>
							</div>
							<div class="more_opt" id="more_opt5">
							<ul>
								<?php if(count($quickMem))
								   {
											foreach($quickMem as $asgnMem)
									  {
												 if(SES_ID==$asgnMem['User']['id'])
										   {
													   echo "<li>
															 <a href='javascript:jsVoid()' onclick='notified_users(".$asgnMem['User']['id'].");' >
																 <span class='value'>".$asgnMem['User']['id']."
																 </span>&nbsp;&nbsp;me
															 </a>
														 </li>";
												 }else{
													  echo "<li>
														  <a href='javascript:jsVoid()' onclick='notified_users(".$asgnMem['User']['id'].");'>
															  <span class='value'>".$asgnMem['User']['id']."
															  </span>&nbsp;&nbsp;".$this->Format->formatText($asgnMem['User']['name'])."
														  </a>
													  </li>";
	
	
												 } 
								  
										   }
									 
									   }else{ ?>
											<a href="javascript:jsVoid()" onclick="open_more_opt('more_opt5');">
												 me<span class="value"><?php echo SES_ID; ?></span></a>
									 </a>
									   <?php }
										?>
									 </ul>
								</div>
							</div>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<?php /*?><input type="text" name="data[Easycase][due_date]" id="CS_due_date" class="datepicker small" onchange="checkDate('CS_due_date','<?php echo $curDate; ?>')"/><?php */?>
	
</div>
<div class="case_field">
	<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td align="left">
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

							<div class="more_opt" id="more_opt3">
								<ul>
									<li><a href="javascript:jsVoid()">&nbsp;&nbsp;No Due Date<span class="value">No Due Date</span></a></li>

									<li><a href="javascript:jsVoid()">&nbsp;&nbsp;Today<span class="value"><?php echo date('m/d/Y',strtotime($userDate));?></span> </a></li> 	
									<li><a href="javascript:jsVoid()">&nbsp;&nbsp;Next Monday <span class="value"><?php echo date('m/d/Y',strtotime($monday));?></span></a></li> 
									<li><a href="javascript:jsVoid()">&nbsp;&nbsp;Tomorrow<span class="value"><?php echo date('m/d/Y',strtotime($tomorrow));?></span></a></li>
									<li><a href="javascript:jsVoid()">&nbsp;&nbsp;This Friday<span class="value"><?php echo date('m/d/Y',strtotime($friday));?></span></a></li> 
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
			<td id="task_priority_td" >
				<table cellpadding="0" cellspacing="0">
					<tr>
						<td class="case_fieldprof" >
							<span id="hd1">
								<div class="fl lbl-m-wid">Priority:</div>

							</span>
						</td>
						<td align="left">
							<div class="fl prio_radio y_low" onclick="check_priority(this);" ><input type="radio" name="task_priority" value="2" id="priority_low" <?php if(isset($taskdetails['priority']) && $taskdetails['priority']==2){?>checked="checked"<?php }?> />&nbsp;Low&nbsp;&nbsp;</div>
							<div class="fl prio_radio g_mid" onclick="check_priority(this);"><input type="radio" name="task_priority" value="1" id="priority_mid"  <?php if(!isset($taskdetails['priority'])){?>checked="checked"<?php }elseif($taskdetails['priority']==1){?>checked="checked"<?php }?>  />&nbsp;Medium&nbsp;&nbsp;</div>
							<div class="fl prio_radio h_red" onclick="check_priority(this);"><input type="radio" name="task_priority" value="0" id="priority_high" <?php if(isset($taskdetails['priority']) && $taskdetails['priority']==0){?>checked="checked"<?php }?> />&nbsp;High&nbsp;&nbsp;</div>
<!--							<span id="hd2">
								<div class="fl dropdown option-toggle p-6">
									<div class="opt1" id="opt2">
										<a href="javascript:jsVoid()" onclick="open_more_opt('more_opt2');">
											<span><font style='color:#28AF51;font-size:12px;'>&nbsp;MEDIUM</font></span>
											<i class="caret mtop-10 fr"></i>
										</a>
									</div>
									<div class="more_opt" id="more_opt2">
									   <ul>
										  <li>
											<a href="javascript:jsVoid()"><font style='color:#AD9227;font-size:12px;'>&nbsp;LOW</font>
													<span class="value">2</span>
											</a>
											</li>
										  <li>
											<a href="javascript:jsVoid()"><font style='color:#28AF51;font-size:12px;'>&nbsp;MEDIUM</font>
													<span class="value">1</span>
											</a>
											</li>
										  <li>
											<a href="javascript:jsVoid()"><font style='color:#AE432E;font-size:12px;'>&nbsp;HIGH</font>
													<span class="value">0</span>
											</a>
										  </li>
										</ul>
									</div>
								</div>
							</span>-->
						</td>
						<td style="display:none;" id="tmpl_open">
						
							<div class="dropdown fl" style="width:310px;text-align:right;position:absolute;margin-top: 39px;z-index:1;">
								<!--<div data-toggle="dropdown">
									<span><a href="javascript:void(0);" onclick="showpopup('openpopup_tmpl')" class="popup_link_tmpl" style="color:#5191BD;text-decoration:underline;">Choose Template</a></span>
								</div>
									<ul class="dropdown-menu sett_dropdown-caret" style="left:198px;">
										<li class="pop_arrow_new" style="margin-top:-12px;"></li>
										<li id="defaultoption" style="display:none;border-bottom:1px solid #DDDDDD;padding:0px 0px 0px 0px;text-align:left;width:100%;">
											<a href="javascript:void(0);" onclick="showTemplates('New','')"><b><i>Set to default</i></b></a>
										</li>
										
										<?php
										if(count($getTmpl)) {
											foreach($getTmpl as $tmpl) { ?>
												<li style="display:block;text-align:left;">
													<a href="javascript:void(0);" onclick="showTemplates(<?php echo $tmpl['CaseTemplate']['id']; ?>,'<?php echo $tmpl['CaseTemplate']['name']; ?>')"><?php echo ucfirst($tmpl['CaseTemplate']['name']); ?></a>
												</li>
												<li class="divider" style="margin:0;padding:0;"></li>
											<?php
											}
										}
										else { ?>
											<li style="display:block;text-align:left;">
												<a href="<?php echo HTTP_ROOT."users/add_template/"; ?>" target="_blank">yet to create</a>
											</li>	
											<li class="divider" style="margin:0;padding:0;"></li>
											<?php
										}
										?>
									</ul>-->
							</div>	
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</div>
<div class="case_field">
	<span>
		<div class="fl lbl-m-wid">Description:</div>
		<div id="divNewCase" class="col-lg-9 fl rht-con">
			<textarea name="data[Easycase][message]" id="CS_message" onfocus="openEditor()" rows="2" style="resize:none" class="form-control" placeholder="Enter Description..."><?php if(isset($taskdetails['message']) && $taskdetails['message']){echo $taskdetails['message']; }?></textarea>
		</div>
		<div id="divNewCaseLoader" style="display:none;padding:20px ;text-align: center;color:#999999;">
			Loading...
		</div>
	</span>
</div>
<div class="cb"></div>
<div class="case_field">
<table cellpadding="0" cellspacing="0" width="100%">
<tr>
<td align="left">
<table cellpadding="0" cellspacing="0">
	<tr>
		<td class="case_fieldprof" >
			<div class="fl lbl-m-wid">Estimated Hour(s):</div>
		</td>
		<td>
			<div class="col-lg-9 fl rht-con">
				<a rel="tooltip" href="javascript:;" original-title="You can enter time as 1.5 (that  mean 1 hour and 30 minutes).">
					<input type="text" onkeypress="return numericDecimal(event)" id="estimated_hours" name="data[Easycase][estimated_hours]" maxlength="6" class="form-control" style="width:80px;" value="<?php if(isset($taskdetails['estimated_hours']) && $taskdetails['estimated_hours']){echo $taskdetails['estimated_hours'];}?>"/>
				</a>
			</div>
		</td>
</tr>
</table>
</td>
</tr>
</table>
</div>
<div class="clear"></div>
<div class="case_field">
<table cellpadding="0" cellspacing="0" width="100%">
<tr>
<td align="left">
<table cellpadding="0" cellspacing="0">
	<tr>
		<td class="case_fieldprof" >
			<div class="fl lbl-m-wid">Hour(s) Spent:</div>


		</td>
		<td>
			<div id="sample" class="col-lg-9 fl rht-con">
				<a rel="tooltip" href="javascript:;" original-title="You can enter time as 1.5  (that  mean 1 hour and 30 minutes).">
				<input type="text" onkeypress="return numericDecimal(event)" id="hours" name="data[Easycase][hours]" maxlength="6" class="form-control" style="width:80px;" value="<?php if(isset($taskdetails['hours']) && $taskdetails['hours']){echo $taskdetails['hours'];}?>"/>
				</a>
			</div>
		</td>
</tr>
</table>
</td>
</tr>
</table>
</div>
<div class="clear"></div>
<div class="case_field">
	<table border="0" cellpadding="0" cellspacing="0" style="padding-left:2px;">
		<tr>
			<td class="case_fieldprof" valign="top">
				<div class="fl lbl-m-wid">Attachment(s):</div>

			</td>
			<td align="left">
				<table cellpadding="0" cellspacing="0" style="width:100%">
					<tr>
						<td>
							<form id="file_upload" action="<?php echo HTTP_ROOT."easycases/fileupload/"; ?>" method="POST" enctype="multipart/form-data">
								<div class="fl" style="margin:10px 0;">
									<!--<span class="customfile-button" aria-hidden="true">Browse</span>-->
									<div class="customfile-button" style="right:0">
										<input class="customfile-input fl" name="data[Easycase][case_files]" type="file" multiple=""  style="width:233px;"/>
										<input name="data[Easycase][usedstorage]" type="hidden" id="usedstorage" value=""/>
										<input name="data[Easycase][allowusage]" type="hidden" id="allowusage" value="<?php echo $user_subscription['storage']; ?>"/>
										<div class="att_fl fl" style="margin-right:5px"></div><div class="fr">Select multiple files to upload...</div>
									</div>
									<div style="margin-left:6px" class="fnt999">Max size <?php echo MAX_FILE_SIZE; ?> Mb</div>
								</div>
								<?php if(isset($user_subscription) && ($user_subscription['is_free'] || ($user_subscription['subscription_id']>1))){?>
								<div class="fr drive_con">
									<div class="fr btn-al-mr">
										<button type="button" class="customfile-button" onclick="connectDropbox(0,<?php echo $is_basic_or_free;?>);">
											<span class="icon-drop-box"></span>
											Dropbox
										</button>
									</div>
									<div class="btn-al-mr">
										<button type="button" class="customfile-button" onclick="googleConnect(0,<?php echo $is_basic_or_free;?>);">
											<span class="icon-google-drive"></span>
											Google Drive
										</button>
										<span id="gloader" style="display: none;">
											<img src="<?php echo HTTP_IMAGES;?>images/del.gif" style="position: absolute;bottom: 95px;margin-left: 125px;"/>
										</span>
									</div>
								</div>
								<?php }?>
								<div class="cb"></div>
							</form>     
						</td>
					</tr>
					<tr>
						<td>
							<table id="up_files" style="font-weight:normal;width: 100%;"></table>
						</td>	
					</tr>
				</table>
			</td>
		</tr>
		<?php if($user_subscription['btprofile_id'] || $user_subscription['is_free'] || ($user_subscription['subscription_id']>1 && !$user_subscription['is_cancel']) ){
			$is_basic_or_free = 0;
		    } else {
			$is_basic_or_free = 1;
		    } ?>
		<tr id="drive_tr_0" style="display: none;">
		    <td width="250px">&nbsp;</td>
		    <td>
			<form id="cloud_storage_form_0" name="cloud_storage_form_0"  action="javascript:void(0)" method="POST">
			    <div style="float: left;margin-top: 7px;" id="cloud_storage_files_0"></div>
			</form>
			<div style="clear: both;margin-bottom: 3px;"></div>
		    </td>
		</tr>
	</table>

</div>
<div class="cb"></div>
<!--<div class="clear"></div>-->
</div>

<!-- Google drive starts-->
<script type="text/javascript">
    var CLIENT_ID = "<?php echo CLIENT_ID; ?>";
    var REDIRECT = "<?php echo REDIRECT_URI; ?>";
    var API_KEY = "<?php echo API_KEY; ?>";
    var DOMAIN_COOKIE = "<?php echo DOMAIN_COOKIE; ?>";
</script>
<script type="text/javascript" src="<?php echo JS_PATH; ?>google_drive_v1.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH; ?>tinymce/jquery.tinymce.js"></script>
<script src="https://www.google.com/jsapi?key=<?php echo API_KEY; ?>"></script>
<script src="https://apis.google.com/js/client.js"></script>
<!-- Google drive ends-->
<script type="text/javascript" src="https://www.dropbox.com/static/api/1/dropins.js" id="dropboxjs" data-app-key="<?php echo DROPBOX_KEY;?>"></script>
<script type="text/javascript">
	function show_follower(){
		if(document.getElementById('ajxMem').style.display == 'none'){
			document.getElementById('ajxMem').style.display = 'block';
		}else{
			document.getElementById('ajxMem').style.display = 'none';
		}
	}
	function validate_quick_case(){
		if(document.getElementById("title_txt").value==''){
			alert("Title Field Can Not Be Left Blank");
			return false;
		}else{
			return true;
		}
	}
	
	function showpopup(id) {
		if($('#'+id).css("display") == "block"){
			$('#'+id).css({display:"none"});
		}
		else{
			$('#'+id).css({display:"block"});
		}
	}

	function notified_users(uid){
		$('#chk_'+uid).attr('checked','checked');
	}
	function numericDecimal(e) {
		var unicode = e.charCode ? e.charCode : e.keyCode;
		if( unicode != 8 ){
			if(unicode < 9 || unicode > 9 && unicode < 46 || unicode > 57 || unicode == 47) {
				if(unicode == 37 || unicode == 38) {
					return true;
				}else {
					return false;
				}
			}else {
				return true;
			}
		}else{
			return true;
		}
	}
	function closecase() {
		$("#new_case_more_div").slideUp(200);
		$("#more_tsk_opt_div").show();
		$("#less_tsk_opt_div").hide();
		//$("#wrapper").css({minHeight:"550px"});
		scrolltop();

	}
	function check_priority(obj){
		$(obj).find('input:radio').attr('checked','checked');
		var pvalue = $(obj).find('input:radio').val();
		$("#CS_priority").val(pvalue);
	}
</script>

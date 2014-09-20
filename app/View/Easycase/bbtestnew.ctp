<?php 
echo $this->Html->meta('icon');
echo $this->Html->css('style');
echo $this->Html->css('stylesheet');
?>
<script type="text/javascript">
var urlRoot = '<?php echo HTTP_ROOT?>easycases/bb_case_projectnew/';
var SES_ID = '<?php echo SES_ID?>';
</script>
<script type="text/javascript" src="<?php echo JS_PATH; ?>jquery/jquery.min.1.5.1.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH; ?>backbone/underscore.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH; ?>backbone/backbone.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH; ?>backbone/bbeasycasenew.js"></script>

<script type="text/template" id="case_proj_template">

	<li id="curRow<%= caseAutoId %>" class="ticket-data" style="border-top:1px solid <%= bgcol %>;<%= borderBottom %>height:40px;" onmouseover="displayOption('grip<%= count %>','option<%= count %>','tblswitch<%= count %>')" onmouseout="hideOption('grip<%= count %>','option<%= count %>','tblswitch<%= count %>')">
		<div class="ticket-overview" id="listing<%= count %>" style="padding-left:3px;margin-left:15px">
			<ul>
				<li class="ticket-data" style="width:2%;">
					<%= actionChk %>
					<input type="hidden" id="actionCls<%= count %>" value="<%= caseLegend %>" disabled="disabled" size="2"/>
				</li>
				<li class="ticket-data" style="width:3%;">
					<span id="grip<%= count %>">
						<img src="<?php echo HTTP_IMAGES; ?>images/grippy.png" alt="grip" style="position:relative; top:2px; right:1px;"/>
					</span>
				</li>
				<li class="ticket-data" style="width:4%;">
					#<%= caseNo %>
				</li>
				<li class="ticket-data" style="width:<%= typeWidth %>%;padding-top:2px;">
					<div class="popup_link_case_proj_parent" class="fl" align="left" style="margin:0px 5px">
						<div class="popup_link_case_proj">
							<a href="javascript:jsVoid();" style="cursor:text;text-decoration:none;" id="showUpdStatus<%= caseAutoId %>" style="text-decoration:none;">
								<%= todo_typ %>
							</a>
							<span id="typlod<%= caseAutoId %>" style="display:none">
								<img src="<?php echo HTTP_IMAGES; ?>images/del.gif" alt="Loading..." title="Loading..."/>
							</span>
						</div>
					</div>
				</li>
				<li class="ticket-data" style="width:2%;padding-top:4px;">
					<div class="popup_link_case_proj_parent" class="fl" align="left" style="margin:0px 5px">
						<div class="popup_link_case_proj">
							<a href="javascript:jsVoid();" style="cursor:text;text-decoration:none;" id="showUpdPri<%= caseAutoId %>" style="text-decoration:none;">
								<%= casePriority %>
							</a>
							<span id="prilod<%= caseAutoId %>" style="display:none">
								<img src="<?php echo HTTP_IMAGES; ?>images/del.gif" alt="Loading..." title="Loading..."/>
							</span>
						</div>
					</div>
				</li>
				<li class="ticket-data" style="width:<%= titleWidth2 %>%;text-align:left;padding-left:10px;">
					<div style="padding-right:2px;">
						<span id="spntitleblock<%= count %>">
							<table cellpadding="0" cellspacing="0"><tr><td class="wrapword">
							<a href="javascript:void(0);" class="classhover" onclick="opendivCase('ajxCse<%= count %>','hid_cs','<%= count %>','sortby<%= count %>','hr<%= count %>','0','listing<%= count %>');ajaxCaseDetails('<%= caseUniqId %>','<%= caseAutoId %>','<%= count %>','case',0,'<%= projId %>');">
						
								<span id="titlehtml<%= count %>"><%= caseTitle %></span>
							</a>
							</td>
							<td valign="top">
								<%= attach1 %>
							</td>
							</tr></table>
						</span>
						<span id="spntitlenone<%= count %>" style="display:none">
							<input type="text" id="Edt_case_title<%= count %>" value="<%= caseTitle %>" style="width:92%;color: #000;font-size: 13px;margin-bottom: 3px;outline: medium none;padding: 8px;" >
							<br/>
							<div style="float:right;padding-right:5%;">
								<a href="javascript:void(0);" style="text-decoration:underline" onclick="updateCases('<%= count %>','<%= caseAutoId %>','<%= projId %>')">Save</a>
								&nbsp;or&nbsp;
								<a href="javascript:void(0);" style="text-decoration:underline" onClick="spndisplay('<%= count %>','<%= caseAutoId %>')">Cancel</a>
							</div>
						</span>
					</div>
					<div style="font-size:11px;color:#A9A9A9;font-style:italic;float:left">
						<%= sts %> by <font original-title="<%= usrName %>" style="color:#787878;font-weight:bold"><%= usrShortName %></font>
						on
						<span id="timedis<%= count %>">
							<%= updated1 %>
						</span>
					</div>
				</li>
				<li class="ticket-data" style="width:<%= statusWidth %>%;text-align:left;font-size:12px;">
					<span id="csStsRep<%= count %>"><%= case_status %></span>
				</li>
				<li class="ticket-data" style="width:<%= asignedWidth %>%;text-align:left;font-size:12px;">			
					<div class="popup_link_case_proj_parent" class="fl" align="left" style="margin:0px 5px;">
						<div class="popup_link_case_proj" onclick="displayAssignToMem('<%= caseAutoId %>','<%= projUniq %>','<%= caseAssgnUid %>')">
							<a href="javascript:jsVoid();" onclick="open_pop(this)" title="edit Assign to" id="showUpdAssign<%= caseAutoId %>" style="text-decoration:none;color:#666666;">
								<font original-title="<%= asgnName %>"><%= asgnShortName %></font>
							</a>
							<span id="asgnlod<%= caseAutoId %>" style="display:none">
								<img src="<?php echo HTTP_IMAGES; ?>images/del.gif" alt="Loading..." title="Loading..."/>
							</span>
						</div>
						
						<div class="popup_option" style="display:none;position:absolute;z-index:0;" id="popupCloseAssign<%= caseAutoId %>">
							<div class="pop_arrow_new" style="position:absolute;  left:-25px"></div>
							<div class="popup_con_menu" align="left" style="left:-35px; min-width:60px">
								<div align="left" style="padding:5px;cursor:pointer;">
									<img src="<?php echo HTTP_IMAGES; ?>images/del.gif" id="assgnload<%= caseAutoId %>" style="display:none"/>
									<span id="showAsgnToMem<%= caseAutoId %>"></span>
								</div>
							</div>
						</div>
						
					</div>
				</li>
				<li class="ticket-data" style="width:<%= createdWidth %>%;text-align:left;font-size:12px;">
					<%= actualDt %>
				</li>
				<li class="ticket-data" style="width:<%= duedateWidth %>%;text-align:left;font-size:12px;">
					<div class="popup_link_case_proj_parent" class="fl" align="left" style="margin:0px 5px;">
						<div class="popup_link_case_proj">
							<a href="javascript:jsVoid();" style="cursor:text;text-decoration:none;color:#666666;" id="showUpdDueDate<%= caseAutoId %>" style="text-decoration:none;color:#666666;">
								<%= caseDueDate %>
							</a>
							<span id="datelod<%= caseAutoId %>" style="display:none;">
								<img src="<?php echo HTTP_IMAGES; ?>images/del.gif" alt="Loading..." title="Loading..."/>
							</span>
						</div>
					</div>
				</li>
			</ul>
		</div>
	</li>

</script>

<div id="caseViewSpan" style="display:block">

<div id="sidetab7" style="width:100%;border-bottom:0px solid #F0F0F0;">
	<ul class="tickets">
		<li class="ticket-header">
        	
			<ul style="border:1px solid #DCDCDC;padding:5px 0;padding-left:20px;" class="tophead">
				<li class="ticket-header-ticket-setting" style="width:2%; padding-left:4px;">
					<input type="checkbox" style="cursor:pointer;position:relative;top:-2px;" id="checkAll"/>
				</li>
				<li class="ticket-header-ticket-setting" style="width:3%;">&nbsp;</li>
				
				<li class="ticket-header-ticket" style="width:4%;cursor:pointer;" original-title="sort by Task#">Task&nbsp;</li>
				<li class="ticket-header-ticket" style="width:2%;">&nbsp;</li>
				<li class="ticket-header-ticket" style="width:2%;">&nbsp;</li>
				<li class="ticket-header-ticket" style="text-align:left;width:52%;padding-left:10px;cursor:pointer;">Title&nbsp;</li>
				<li class="ticket-header-ticket" style="width:6%;text-align:left;padding-left:7px;">Status</li>
				<li class="ticket-header-ticket" style="width:8%;text-align:left;">Assigned to</li>
				<li class="ticket-header-ticket" style="width:10%;text-align:left;cursor:pointer;" original-title="sort by Create Date">Created&nbsp;</li>
				<li class="ticket-header-ticket" style="width:8%;text-align:left;cursor:pointer;padding-left:5px;" original-title="sort by Due Date">Due Date&nbsp;</li>
			</ul>
		</li>
	</ul>
	<ul class="tickets" id="bbcaseViewSpan">
	</ul>
</div>
</div>

<script type="text/javascript" src="<?php echo JS_PATH; ?>common_inner.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH; ?>easycase.js"></script>
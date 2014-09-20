<?php 
echo $this->Html->meta('icon');
echo $this->Html->css('style');
echo $this->Html->css('stylesheet');
?>
<script type="text/javascript">
var urlRoot = '<?php echo HTTP_ROOT?>easycases/bb_case_project/';
var SES_ID = '<?php echo SES_ID?>';
</script>
<script type="text/javascript" src="<?php echo JS_PATH; ?>jquery/jquery.min.1.5.1.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH; ?>backbone/underscore.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH; ?>backbone/backbone.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH; ?>backbone/bbeasycase.js"></script>

<script type="text/template" id="case_proj_template">

	<li id="curRow<%= id %>" class="ticket-data" style="border-top:1px solid #f2f2f2;height:20px;">
		<div class="ticket-overview" id="listing<%= count %>">
			<ul style="padding-left:18px;">
				<li class="ticket-data" style="width:2%;">
					<input type="checkbox" style="cursor:pointer;position:relative;top:-2px;" id="actionChk<%= count %>" value="<%= id %>|<%= case_no %>" />
				</li>
				<li class="ticket-data" style="width:3%;">
					<span id="grip<%= count %>">
						<img src="<?php echo HTTP_IMAGES; ?>images/grippy.png" alt="grip" style="position:relative; top:2px; right:1px;"/>
					</span>
				</li>
				<li class="ticket-data" style="width:4%;">#<%= case_no %></li>
				<li class="ticket-data" style="width:2%;">&nbsp;</li>
				<li class="ticket-data" style="width:2%;">&nbsp;</li>
				<li class="ticket-data" style="width:53%;text-align:left;padding-left:10px;">
					<div style="padding-right:2px;">
						<span id="spntitleblock<%= count %>">
							<table cellpadding="0" cellspacing="0"><tr><td class="wrapword">
							<a href="javascript:void(0);" class="classhover">
								<span id="titlehtml<%= count %>"><%= title %></span>
							</a>
							</td>
							</tr></table>
						</span>
					</div>
				</li>
				<li class="ticket-data" style="width:6%;text-align:left;font-size:12px;">
					<span id="csStsRep<%= count %>"><%= case_status %></span>
				</li>
				<li class="ticket-data" style="width:8%;text-align:left;font-size:12px;">
					<font original-title='<%= asgn_name %>' ><%= asgn_short_name %></font>
				</li>
				<li class="ticket-data" style="width:10%;text-align:left;font-size:12px;"><%= actual_dt_created %></li>
				<li class="ticket-data" style="width:8%;text-align:left;font-size:12px;"><%= due_date %></li>
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
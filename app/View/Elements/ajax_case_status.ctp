<!--[if lte IE 9]>
    <style>
        .widget.text-only.blinkwidget{background-color:#f2f2f2!important;}
    </style>	
<![endif]-->
<style type="text/css">
	.widget{
		background: url("<?php echo HTTP_ROOT;?>img/html5/icons/right_div.png") no-repeat 0 5px; #D6D4D4;
		margin: 0px;
		border: none;
	}
</style>
<%
var case_widgets = getCookie('CLOSE_WIDGET');
var case_news = getCookie('NEW_WIDGET');
var case_opens = getCookie('OPEN_WIDGET');
var case_starts = getCookie('START_WIDGET');
var case_resolves = getCookie('RESOLVE_WIDGET');
var chart_widgets = getCookie('CHART_WIDGET');

if(al!=0  && al){
	var fill = "(" + Math.round(((cls/al)*100))+"%)";
}
else {
	var fill = "(0%)";
}

if(case_widgets) {
	var case_wid = "display:none;";
	var case_wid1 = "display:block;";
} else {
	var case_wid = "display:block;";
	var case_wid1 = "display:none;";
}
if(case_news) {
	var case_new = "display:none;";
	var case_new1 = "display:block;";
} else {
	var case_new = "display:block;";
	var case_new1 = "display:none;";
}
if(case_opens) {
	var case_open = "display:none;";
	var case_open1 = "display:block;";
} else {
	var case_open = "display:block;";
	var case_open1 = "display:none;";
}
if(case_starts) {
	var case_start = "display:none;";
	var case_start1 = "display:block;";
} else {
	var case_start = "display:block;";
	var case_start1 = "display:none;";
}

if(case_resolves) {
	var case_resolve = "display:none;";
	var case_resolve1 = "display:block;";
} else {
	var case_resolve = "display:block;";
	var case_resolve1 = "display:none;";
}
if(chart_widgets) {
	var chart_widget = "display:none;";
	var chart_widget1 = "display:block;";
} else {
	var chart_widget = "display:block;";
	var chart_widget1 = "display:none;";
}

if(case_widgets=="1" || case_news == "1" || case_resolves =="1" || case_starts =="1" || case_opens =="1" || chart_widgets =="1"){
	var widget="display:block;";
} else {
	var widget="display:none;";
}

var disabled = "";
if(getCookie('CURRENT_FILTER') == 'closecase') {
	disabled = 1;
}
%>
<div class="fl status" id="widget_new">
	<a href="javascript:void(0);"  <% if(!disabled) { %> onclick="statusTop(1);" <% } %>>
		<span class="num"><%= nw %></span>&nbsp;New
	</a>
</div>
 <div class="fl status" id="widget_open">
	<a href="javascript:void(0);"  <% if(!disabled) { %> onclick="statusTop(2);" <% } %> >
		<span class="num1"><%= opn %></span>&nbsp;In Progress
	</a>

</div>
    <div class="fl status" id="widget_resolve">
        <a href="javascript:void(0);"  <% if(!disabled) { %> onclick="statusTop(5);" <% } %> >
            <span class="num2"><%= rslv %></span>&nbsp;Resolved
        </a>

    </div>
<div class="fl status cls" id="widget_close" style="padding-left:8px;">
	<% if(upd!='0') {
		if(upd == 1) {
			var title = "Excluding " + upd + " \'Update\' task";
		} else if(upd > 1) {
			var title = "Excluding " + upd + " \'Update\' tasks";
		}
		%>

	<% } %>	
	
		<a href="javascript:void(0);"  <% if(!disabled) { %> onclick="statusTop(3);" <% } %> style="padding-left:12px;position:relative">
        <i class="help-icon f-tab" title="<%= title %>" rel="tooltip"></i>
        <span class="num3"><%= cls %>/<%= al %></span>&nbsp;Closed<%= fill %>
    </a>
    </div>
	<?php
if(strtotime("+2 months",strtotime(CMP_CREATED))>=time()){?>
<!--<div  title="Click for help"  onclick="return showhelp();" class=" fl status need-help no-select"  style="">Need help getting started?</div>-->
<?php } ?>
    <div class="cb"></div>
<!--<div class="widget text-only blinkwidget" id="widget_new" style="height:24px;min-width:40px;width:auto;<%= case_new %>; background:none; border-radius:0px">
	<a href="javascript:jsVoid();" class="close-widget tooltip_widget" title="Hide Widget" onclick="hideCloseWidget(<%= '\'widget_new\'' %>);showselect(<%= '\'widget_new\'' %>)" rel="tooltip_widget">&times;</a>
	<p>
		<div class="right">
			<a href="javascript:jsVoid();" class="new" <% if(!disabled) { %> onclick="statusTop(1);" style="text-decoration:underline" <% } else { %>style="cursor:text;text-decoration:none;"<% } %>>
			<div style="margin-top:-1px;" class="left new"><span class="fnt20" style="font-size:15px!important"><%= nw %></span></div>
			<div class="text_shadow left" style="font-size:11px;margin-top:6px;padding-right:5px;text-decoration: underline; color:#333">
			<a href="javascript:jsVoid();" class="new" <% if(!disabled) { %> onclick="statusTop(1);" style="text-decoration:underline" <% } else { %>style="cursor:text;text-decoration:none;"<% } %>>New</a>NEW
			</div>
			</a>	
		</div>
	</p>
</div>-->
<!--<div class="widget text-only blinkwidget" id="widget_open" style="height:24px;min-width:40px;width:auto;<%= case_open %>; border-right:1px solid #ccc; background:none; border-radius:0px">
	<a href="javascript:jsVoid();" class="close-widget tooltip_widget" title="Hide Widget" onclick="hideCloseWidget(<%= '\'widget_open\'' %>);showselect(<%= '\'widget_open\'' %>)">&times;</a>
	<p>
		<div class="right">
			<a href="javascript:jsVoid();" class="wip" <% if(!disabled) { %> onclick="statusTop(2);" style="text-decoration:underline" <% } else { %>style="cursor:text;text-decoration:none;"<% } %> >
			<div class="left wip" style="margin-top:-1px;">
            	<span class="fnt20" style="font-size:15px!important;"><%= opn %></span>
            </div>
	 		<div class="text_shadow left" style="font-size:11px;margin-top:6px;padding-right:5px;text-decoration: underline; color:#333">
            	<a href="javascript:jsVoid();" class="wip" <% if(!disabled) { %> onclick="statusTop(2);" style="text-decoration:underline" <% } else { %>style="cursor:text;text-decoration:none;"<% } %> >WIP</a>WIP
            </div>
		</a>   
		</div>
	</p>
</div>-->
<?php /*?><div class="widget text-only" id="widget_start"  style="height:24px;min-width:55px;width:auto;<?php echo $case_start; ?>">
	<a href="#" class="close-widget tooltip_widget" title="Hide Widget" rel="tooltip_widget" onclick="hideCloseWidget('widget_start');showselect('widget_start')">&times;</a>
	<p>
		<div class="right">
			<div class="left" style="margin-top:-1px;color:#55A0C7;">
            	<span class="fnt20"><?php echo $query_Start; ?></span>
            </div>
	 		<div class="text_shadow left" style="font-size:13px;margin-top:6px;padding-right:5px;">
            	<a href="javascript:jsVoid();" <?php if(!$disabled) { ?> onclick="statusTop(4);ajaxCaseView('case_project.php');" style="color:#55A0C7; text-decoration:underline"<?php } else { ?>style="cursor:text;text-decoration:none;color:#55A0C7;"<?php } ?> >Started</a>
           </div>
		</div>		
	</p>
</div><?php */?>
<!--<div class="widget text-only blinkwidget" id="widget_resolve" style="height:24px;min-width:75px;width:auto;<%= case_resolve %>; border-right:1px solid #ccc; background:none; border-radius:0px">
	<a href="javascript:jsVoid();" class="close-widget tooltip_widget" title="Hide Widget"  onclick="hideCloseWidget(<%= '\'widget_resolve\'' %>);showselect(<%= '\'widget_resolve\'' %>)">&times;</a>
	<p>
		<div class="right">
			<a href="javascript:jsVoid();" class="resolved" <% if(!disabled) { %> onclick="statusTop(5);" style="text-decoration:underline;font-size:13px;"<% } else { %>style="cursor:text;text-decoration:none;"<% } %> >
				<div class="left resolved" style="margin-top:-1px;"><span class="fnt20" style="font-size:15px!important"><%= rslv %></span></div>
				<div class="text_shadow left" style="margin-top:6px;padding-right:5px;text-decoration: underline; font-size:11px; color:#333">
					<a href="javascript:jsVoid();" class="resolved" <% if(!disabled) { %> onclick="statusTop(5);" style="text-decoration:underline;font-size:13px;"<% } else { %>style="cursor:text;text-decoration:none;"<% } %> >Resolved</a>RESOLVED
				</div>
			</a>	
		</div>
	</p>
</div>-->
<!--<div class="widget text-only blinkwidget" id="widget_close" style="height:24px;min-width:90px;width:auto;<%= case_wid %>; border-right:none; background:none; border-radius:0px; padding-right:15px;">
	<a href="javascript:jsVoid();" class="close-widget tooltip_widget" onclick="hideCloseWidget(<%= '\'widget_close\'' %>);showselect(<%= '\'widget_close\'' %>)" title="Hide Widget">&times;</a>
	<p>
		<div class="right">
			<a href="javascript:jsVoid();" class="closed" <% if(!disabled) { %> onclick="statusTop(3);" style="text-decoration:underline" <% } else { %>style="cursor:text;text-decoration:none;"<% } %>>
			<div style="margin-top:-2px;" class="left closed">
				<span class="fnt20" style="font-size:15px!important;"><%= cls %>/<%= al %></span>
				<span class="fnt20 fnt_normal"  style="font-size:15px!important"><%= fill %></span>
			</div>
			<div class="text_shadow left" style="font-size:11px; float:left;padding-right:5px;margin-top:6px;text-decoration: underline; color:#333">
				<a href="javascript:jsVoid();" class="closed" <% if(!disabled) { %> onclick="statusTop(3);" style="text-decoration:underline" <% } else { %>style="cursor:text;text-decoration:none;"<% } %>>Closed</a>CLOSED
			</div>
			</a> 
			<% if(upd) {
				if(upd == 1) {
					var title = "Excluding " + upd + " \'Update\' task";
				} else {
					var title = "Excluding " + upd + " \'Update\' tasks";
				}
				%>
				<img src="<?php echo HTTP_ROOT; ?>img/images/question.png" class="widget_ipad" title="<%= title %>" rel="tooltip">
			<% } %>
		</div>	
	 </p>
</div>-->
<!--div class="widget text-only" id="widget_chart" style="height:24px;min-width:75px;width:auto;background-image:url(<%= '<?php echo HTTP_ROOT.'img/images/chat.png';?>' %>);<%= chart_widget %>">
	<a href="javascript:jsVoid();" class="close-widget tooltip_widget" title="Hide Widget"  onclick="hideCloseWidget(<%= '\'widget_chart\'' %>);showselect(<%= '\'widget_chart\'' %>)">&times;</a>
	<p>
		<div class="right">
			<?php /*?><div class="left" style="margin-top:-1px;color:#EF6807;"><a href="javascript:void(0);" title="Task Trend Chat" onclick="ReportMenu('projFil')" style="cursor:pointer;border:0px solid #FF0000;width:80px;height:35px;position:relative;top:-5px;" rel="tooltip"></a></div><?php */?>
			<div class="left" style="margin-top:-1px;color:#EF6807;"><a href="javascript:void(0);" title="Bug Reports" onclick="ReportGlideMenu(<%= '\'projFil\'' %>)" style="cursor:pointer;border:0px solid #FF0000;width:80px;height:35px;position:relative;top:-5px;" rel="tooltip"></a></div>
		</div>
	</p>
</div-->
<div class="fl" align="left" style="margin:0px 5px">
    <div class="popup_link_case_proj_parent" align="left" style="<%= widget %>" id="closewidget">
<!-- 		<div class="popup_link_case_proj" id="closedwidgetchild" style="<%= widget %>">
			<a href="javascript:jsVoid();" onclick="open_pop(this)" style="font-weight:normal;">
				<span style="font-size:12px;">Show Widget</span>
			</a>
		</div>-->
		<div class="popup_option" id="popup_option" style="display:none;position:absolute;z-index:0;">
			<div class="pop_arrow_new" style="position:absolute;left:12px"></div>
            <div class="popup_con_menu" align="left" style="left:-1px; min-width:50px;padding: 2px 8px;">
                <div align="left">
    
                    <div  id="widget_close1" style="<%= case_wid1 %>">
                        <a href="javascript:void(0);" onclick="hideCloseWidget(<%= '\'widget_close\'' %>);">Closed</a>
                    </div>
    
    
                    <div  id="widget_new1" style="<%= case_new1 %>">
                        <a href="javascript:void(0);"  onclick="hideCloseWidget(<%= '\'widget_new\'' %>);" style="min-weight:10px;">New</a>
                    </div>   
    
                    <div id="widget_open1" style="<%= case_open1 %>">
                        <a href="javascript:void(0);" onclick="hideCloseWidget(<%= '\'widget_open\'' %>);">In Progress</a>
                    </div>
    
                    <div id="widget_start1" style="<%= case_start1 %>">
                        <a href="javascript:void(0);"  onclick="hideCloseWidget(<%= '\'widget_start\'' %>);" style="min-weight:10px;">Start</a>
                    </div>
    
                    <div  id="widget_resolve1" style="<%= case_resolve1 %>">
                        <a href="javascript:void(0);" onclick="hideCloseWidget(<%= '\'widget_resolve\'' %>);" style="min-weight:10px;">Resolved</a>
                    </div>
					<!--div  id="widget_chart1" style="<%= chart_widget1 %>">
                        <a href="javascript:void(0);" onclick="hideCloseWidget(<%= '\'widget_chart\'' %>);" style="min-weight:10px;">Bug Reports</a>
                    </div-->
                </div>
            </div>
		</div>
	</div>
</div>
<input type="hidden" id="closedcaseid" value="<%= cls %>">
<div class="cb"></div>

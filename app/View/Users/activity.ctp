<div id="moreloader">
    <div class="loadingdata">Loading...</div>
</div>
<div class="page-wrapper">
    <div class="col-lg-9 fl m-left-20 activity_ipad">
	<div id="activities"></div>
	<div style="display:none;" id="more_loader" class="morebar">
	    <img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="loading..." title="loading..."/> 
	</div>
    </div>
  
    <div class="col-lg-3 fl act_rt_div">
	<div class="tab tab_comon tab_task">
	    <ul class="nav-tabs activ_line mod_wide">
		<li class="active">
		    <a href="javascript:void(0);" id="myTab" onclick="myactivities('myTab', 'delegatedTab');">
			<div class="fl" >My</div>
			<div class="cbt"></div>
		    </a>
		</li>
		<li id="file_li">
		    <a href="javascript:void(0);"  id="delegatedTab" onclick="delegateactivities('myTab', 'delegatedTab');">
			<div class="fl">Delegated</div>
			<div class="cbt"></div>
		    </a>
		</li>
		<div class="cbt"></div>
	    </ul>
	</div>
	<div class="cb"></div>
	
	<div id="Upcoming"></div>		
	<div id="moreOverdueloader" class="moreOverdueloader">Loading Tasks...</div>
	<hr/>
	<div id="Overdue"></div>
	<hr/>
	<div id="PieChart" style="display: none;"></div>
    </div>
    <div class="cb"></div>
    
</div>
<div class="cb"></div>
<input type="hidden" id="displayed" value="30">
<input type="hidden" id="prjid" value="<?php echo $curProjId;?>">
<script type="text/template" id="ajax_activity_tmpl">
    <?php echo $this->element("../Users/json_activity");?>
</script>
<script type="text/javascript">
$(document).ready(function() {
    loadActivity('');
    loadOverdue('my');
    loadUpcoming('my');
});

</script>
<script type="text/javascript" src="<?php echo JS_PATH; ?>highcharts.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH; ?>exporting.js"></script>
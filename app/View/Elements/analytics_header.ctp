<div class="tab tab_comon">
	<ul class="nav-tabs mod_wide">
		<?php /*?><li <?php if(CONTROLLER == "reports" && (PAGE_NAME == "glide_chart")) { ?>class="active"<?php } ?>>
			<a href="<?php echo HTTP_ROOT."bug-report/" ?>">
            <div class="an_bug fl"></div>			
			<div class="fl">Bug Reports</div>
			<div class="cbt"></div>
			</a>
		</li><?php */?>
        <li <?php if(CONTROLLER == "reports" && (PAGE_NAME == "chart")) { ?>class="active"<?php } ?>>
			<a href="<?php echo HTTP_ROOT."task-report/" ?>">
            <div class="an_tsk fl"></div>
			<div class="fl">Task Reports</div>
			<div class="cbt"></div>
			</a>
		</li>
		<li <?php if(CONTROLLER == "reports" && (PAGE_NAME == "hours_report")) { ?>class="active"<?php } ?>>
			<a href="<?php echo HTTP_ROOT."hours-report/" ?>">
            <div class="an_hrs fl"></div>
			<div class="fl">Hour Spent</div>
			<div class="cbt"></div>
			</a>
		</li>
		<?php if(SES_TYPE == 1 || SES_TYPE == 2) { ?>
			<li <?php if(CONTROLLER == "reports" && (PAGE_NAME == "weeklyusage_report")) { ?>class="active"<?php } ?>>
				<a href="<?php echo HTTP_ROOT."reports/weeklyusage_report/" ?>">
                <div class="an_week fl"></div>
				<div class="fl">Weekly Usage</div>
				<div class="cbt"></div>
				</a>
			</li>
		<?php } ?>	
		<div class="cbt"></div>
	</ul>
</div>
<div class="fr filter_dt filter_analytics">
	<div class="task_due_dt">
		<?php if(PAGE_NAME == 'glide_chart' || PAGE_NAME == 'chart' || PAGE_NAME == 'hours_report'){ ?>
			<div class="fl icon-due-date"></div>
			<div class="fl">
			<input type="text" class="smal_txt" placeholder="From Date" style="width:115px" id="start_date" value="<?php echo $frm; ?>"/> <span>-</span>
			<input type="text" class="smal_txt" placeholder="To Date" style="width:115px" id="end_date" value="<?php echo $to; ?>"/>
			</div>
		<?php } ?>
		<div class="fl apply_button">
		<div id="apply_btn" class="fl">
		<?php if(PAGE_NAME == 'glide_chart') { ?>
		<button class="btn btn_blue aply_btn" type="button" onclick= "return validatechart('bug');" value="Update" name="submit_Profile" id="submit_Profile">Apply</button>
		<?php } elseif(PAGE_NAME == 'chart') { ?>
		<button class="btn btn_blue aply_btn" type="button" onclick= "return validatechart('task');" value="Update" name="submit_Profile" id="submit_Profile">Apply</button>
		<?php } elseif(PAGE_NAME == 'hours_report'){ ?>
		<button class="btn btn_blue aply_btn" type="button" onclick= "return validatechart('hours');" value="Update" name="submit_Profile" id="submit_Profile">Apply</button>
		<?php } ?>
		</div>
		
		<div id="apply_loader" style="display:none;margin-left:10px;" class="fl">
			 <img src="<?php echo HTTP_IMAGES; ?>images/del.gif" alt="loading..." title="loading..."/> 
		</div>
		</div>
		<div class="cb"></div>
	</div>	
</div>
<div class="cb"></div>
<script>
$(function(){
	$("#start_date").datepicker({
		dateFormat: 'M d, yy',
		changeMonth: false,
		changeYear: false,
		//minDate: 0,
		hideIfNoPrevNext: true,
		maxDate: "0D",
		onClose: function( selectedDate ) {
			$("#end_date").datepicker( "option", "minDate", selectedDate );
		},
		beforeShow: function(input, inst) {
		    $('#ui-datepicker-div').removeClass("display_to_datepicker");
	    }
	});
	
	$("#end_date").datepicker({
		dateFormat: 'M d, yy',
		changeMonth: false,
		changeYear: false,
		//minDate: 0,
		hideIfNoPrevNext: true,
		maxDate: "0D",
		onClose: function( selectedDate ) {
			$( "#start_date" ).datepicker( "option", "maxDate", selectedDate );
		},
		beforeShow: function(input, inst) {
		    $('#ui-datepicker-div').addClass("display_to_datepicker");
	    }
	});
});	
</script>
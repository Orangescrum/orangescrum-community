<div class="data-scroll">
<table cellpadding="0" cellspacing="0" class="col-lg-12 new_auto_tab">
    <?php if (isset($projects) && !empty($projects)) { ?>
    <tr>
	<td class="v-top">Project:</td>
	<td>
	    <select name="new_project" id="new_project" class="form-control" onchange="rmverrmsg();">
		<?php 
		foreach($projects as $proj){ 
		    if($proj['Project']['id'] == $project_id){
			$project_name = ucwords($this->Format->shortLength($proj['Project']['name'],30));
		    }
		    ?>
		<option value="<?php echo $proj['Project']['id'];?>" <?php if($proj['Project']['id'] == $project_id){ ?>selected="selected"<?php }?>><?php echo ucwords($this->Format->shortLength($proj['Project']['name'],30)); ?></option>
		<?php } ?>
	    </select>
	    <div id="err_msg_dv" class="exist-prj">Already in this Project.</div>
	</td>
    </tr>
    <input type="hidden" id="case" value="<?php echo $case_id?>" />
    <input type="hidden" id="case_no" value="0" />
    <input type="hidden" id="project" value="<?php echo $project_id?>" />
    <input type="hidden" id="old_project_nm" value="<?php echo $project_name;?>" />
    <input type="hidden" id="ismultiple_move" value="<?php echo $is_multiple;?>" />
    <?php } else { ?>
    <tr>
	<td class="v-top" colspan="2"><span class="fnt_clr_rd">No Projects assigned yet!</span></td>
    </tr>
    <?php } ?>
    <tr>
	<td class="v-top">&nbsp;</td>
	<td style="text-align:left;">
	    <span id="mvprjloader" class="mvprjlder" style="padding-left: 0;">
		<img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="loading..." title="loading..."/> 
	    </span>
	    <span id="mvprj_btn">
		<button class="btn btn_blue" id="mvbtn"  value="Move" type="button" onclick="moveTaskToProject();"><i class="icon-big-tick"></i>Move</button>
		<!--<button class="btn btn_grey" type="button" onclick="closePopup();"><i class="icon-big-cross"></i>Cancel</button>-->
		<span class="or_cancel">or<a onclick="closePopup();">Cancel</a></span>
	    </span>
	</td>
    </tr>
</table>
</div>
<div class="user_profile_con">
<!--Tabs section starts -->
<?php echo $this->element("company_settings");?>
</div>
<div class="impexp_div">
    <h2 class="fl">Task Type</h2>
    <div class="fr"><button class="btn btn_blue" onclick="addNewTaskType();" style="padding: 5px;margin-right: 3px !important;">+ New Task Type</button></div>
    <div class="cb"></div>
</div>

<?php if (isset($task_types) && !empty($task_types)) {?>
<div class="fl import-csv-file" style="border:1px solid #ccc;width: 95%;">
    <form name="task_types" id="task_types" method="post" action="javascript:void(0);">
    <?php 
    $cnt = 1;
    foreach ($task_types as $key => $value) {
	if ($cnt%4 == 0) {
	    $cb = '<div class="cb"></div>';
	} else {
	    $cb = "";
	}
	
	$checked = 'checked="checked"';
	if (isset($sel_types) && !empty($sel_types)) {
	    if (intval($value['Type']['is_exist'])) {
		$checked = 'checked="checked"';
	    } else {
		$checked = '';
	    }
	}
	if (intval($value['Total']['cnt'])) {
	    //$disabled = 'disabled="true"';
	    $isDelete = 0;
	} else {
	    $isDelete = 1;
	    //$disabled = '';
	}
	?>
	<div class="fl" style="width: 25%;" id="dv_tsk_<?php echo $value['Type']['id'];?>">
	    <div class="fl dv_tsktyp" style="min-width: 10%;width: auto;" data-id="<?php echo $value['Type']['id'];?>">
		<div class="fl">
		    <label style="cursor: pointer;">
			<div class="fl">
			    <input type="checkbox" class="all_tt" value="<?php echo $value['Type']['id'];?>" name="data[Type][<?php echo $value['Type']['id'];?>]" <?php echo $checked;?> <?php echo $disabled;?>/>
			</div>
			<div class="fl" style="margin:3px 0 0 10px;">
			    <span style="<?php if (intval($value['Type']['company_id'])){ ?>color: #666666;<?php } else {?>color: #999;<?php }?>"><b><?php echo $value['Type']['name'];?></b></span>
			    <span style="margin-left: 3px;font-weight: normal;">
				(<?php echo $value['Type']['short_name'];?>)
			    </span>
			</div>
			<div class="cb"></div>
		    </label>
		</div>
		<?php if (intval($value['Total']['cnt'])) {?>
		<div class="fl task-type-cnt" title="<?php echo $value['Total']['cnt']." Task(s)";?>"><?php echo $value['Total']['cnt'];?></div>
		<?php }?>
		<?php if (intval($value['Type']['company_id']) && $isDelete){ ?>
		<div class="fl" id="del_dvtsk_<?php echo $value['Type']['id'];?>" style="padding: 3px;display: none;">
		    <span id="lding_tsk_<?php echo $value['Type']['id'];?>" style="display: none;">
			<img src="<?php echo HTTP_IMAGES; ?>images/del.gif" alt="Loading..." title="Loading..." />
		    </span>
		    <span id="del_tsk_<?php echo $value['Type']['id'];?>">
			<a href="javascript:void(0);" onclick="deleteTaskType(this);" data-name="<?php echo $value['Type']['name'];?>" data-id="<?php echo $value['Type']['id'];?>">
			    <img src="<?php echo HTTP_IMAGES; ?>images/close_hover.png" alt="Delete" title="Delete" />
			</a>
		    </span>
		</div>
		<?php } ?>
	    </div>
	    <div class="cb"></div>
	</div>
	<?php echo $cb;?>
    <?php 
	$cnt++;
    }?>
    <div class="import_btn_div fl" style="width: 100%;height: 60px;">
	<img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="Loading..." title="Loading..."  id="loader_img_tt" style="display: none;position: absolute;"/>
	<button type="button" id="tt_save_btn" name="tt_save_btn" class="btn btn_blue" onclick="return saveTaskType();">
	    <i class="icon-big-tick"></i>
	    <span style="color: #fff;">Save</span>
	</button>
    </div>
    </form>
</div>
<div class="cb"></div>
<?php }?>

<script type="text/javascript">
    $(document).ready(function(){
	$('.dv_tsktyp').hover(function(){
	    var tid = $(this).attr('data-id');
	    if ($(this).find("#del_dvtsk_"+tid).length) {
		$(this).find("#del_dvtsk_"+tid).show();
	    }
	}, function(){
	    var tid = $(this).attr('data-id');
	    if ($(this).find("#del_dvtsk_"+tid).length) {
		$(this).find("#del_dvtsk_"+tid).hide();
	    }
	});
    });
</script>
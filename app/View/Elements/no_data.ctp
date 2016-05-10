<?php
if ($nodata_name == 'caselist') {
    $imageClass = 'icon-no-archive';
    $msgHead = 'No tasks have been archived yet';
    $msgDesc = 'All archived tasks of all projects will appear here';
} else if ($nodata_name == 'filelist') {
    $imageClass = 'icon-no-archive';
    $msgHead = 'No files have been archived yet ';
    $msgDesc = 'All archived files of all projects will appear here';
} else if ($nodata_name == 'activity') {
    $imageClass = 'icon-no-activity';
    $msgHead = 'No task activities on this project';
    $msgDesc = 'All activities of this project will appear here';
} else if ($nodata_name == 'files') {
    $imageClass = 'icon-no-files';
    $msgHead = 'No files have been shared or uploaded on this project';
    $msgDesc = 'All files shared on this project will appear here';
} else if ($nodata_name == 'files-search') {
    $imageClass = 'icon-no-files';
    $msgHead = 'No files found';
    $msgDesc = '';
} else if ($nodata_name == 'milestonelist') {
    $imageClass = 'icon-no-milestone';
    //$msgHead = 'No milestone have been created on this project';
    //$msgDesc = 'All milestone created on this project will appear here';
	$msgHead = 'No milestone';
    $msgDesc = '';
}else if ($nodata_name == 'tasklist') {
	$imageClass = 'icon-no-task';
	if($case_type=='overdue'){
		$msgHead = 'No Overdue Task on this project';
	}elseif($case_type=='highpriority'){
		$msgHead = 'No High Priority Task have been created on this project';
	}elseif($case_type=='assigntome'){
		$msgHead = 'No Task for me on this project';
	}elseif($case_type=='delegateto'){
		$msgHead = 'No Task delegeted on this project';
	}else{
		$msgHead = 'No Task have been created on this project';
	}
    $msgDesc = 'All Task created on this project will appear here';
}
?>
<div class="fl col-lg-12 not-fonud ml_not_found">
	<div class="icon_con <?php echo $imageClass;?>"></div>
	<h2><?php echo $msgHead; ?></h2>
	<div><?php echo $msgDesc; ?></div>
<?php if ($nodata_name == 'milestonelist') {?>
	<div style="padding-top:10px;">
		<button class="btn btn_blue" value="Add" type="button" onclick="addEditMilestone(this);" style="margin:0;">
			Create Milestone
		</button>
	</div>
<?php }?>
</div>
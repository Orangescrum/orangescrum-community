<?php if(isset($recent_activities) && !empty($recent_activities)) {
    $cnt = 0;
     foreach ($recent_activities as $key => $value) {
	 $cnt++;
    ?>
    <div class="listdv">
	<div class="fl">
	    <div class="rec_act_img">
		<?php if($value['User']['photo']){ ?>
		    <img src="<?php echo HTTP_ROOT; ?>users/image_thumb/?type=photos&file=<?php echo $value['User']['photo']; ?>&sizex=30&sizey=30&quality=100" title="<?php echo $value['User']['name']; ?>" rel="tooltip" alt="Loading"/>
		<?php }else{?>
		    <img src="<?php echo HTTP_ROOT; ?>users/image_thumb/?type=photos&file=user.png&sizex=28&sizey=28&quality=100" />
		<?php }?>
	    </div>
	</div>
	<div class="fl wd_rec_80">
	    <div>
		<div class="fl act_title_db" title="<?php echo ucfirst($value['User']['funll_name']); ?>"><?php echo ucfirst($value['User']['name']); ?></div>
		<div class="task_cre_rec_db" title="<?php echo strip_tags($value['Easycase']['msg']); ?>"><?php echo $value['Easycase']['msg']; ?></div>
	    </div>
	    <div class="cb"></div>
	    <div class="time_rec_db">
		<div class="fl" style="padding-top: 2px;padding-left:3px;"><?php echo $value['Easycase']['lastDate']." ".$value['Easycase']['updated'];?>
		
		<?php if($project == 'all'){ ?>
			<span style="font-size:18px;">.</span> <a href="<?php echo HTTP_ROOT; ?>dashboard/?project=<?php echo $value['Project']['uniq_id']; ?>" title="<?php echo ucfirst($value['Project']['name']); ?>" style="color:#5191BD"><?php echo ucfirst($value['Project']['name']); ?></a>
		<?php } ?>
		
		</div>
		
		<div class="cb"></div>
	    </div>
	</div>
	<div class="cb"></div>
    </div>
    <?php if(count($recent_activities) != $cnt) { ?>
    <div class="lstbtndv"></div>
    <?php } ?>
<?php } ?>
	<div id="recent_activities_more" data-value="<?php echo $total;?>" style="display: none;"></div>
     <?php } else { ?>
	<div class="mytask"></div>
	<div class="mytask_txt">No Recent Activities</div>
    <div id="recent_activities_more" data-value="0" style="display: none;"></div>
<?php } ?>
<select name="data[Easycase][milestone]" class="text_field form-control" onchange="milestone_export(this);">
    <option value="all">All</option>
    <?php if(isset($milestones)){
	foreach($milestones as $key =>$milestone){ ?>
	    <option value="<?php echo $key;?>" ><?php echo ucfirst($milestone);?></option>
    <?php }
    }?>
</select>
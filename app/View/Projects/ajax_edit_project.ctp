<center><div id="err_msg" style="color:#FF0000;display:none;"></div></center>
<?php  echo $this->Form->create('Project',array('url'=>'/projects/settings','name'=>'projsettings','enctype'=>'multipart/form-data'));  ?>
    <table cellpadding="0" cellspacing="0" class="col-lg-12 new_auto_tab">
	<tr>
	    <td>Project Name:</td>
	    <td>
		<?php echo $this->Form->text('name',array('value'=>html_entity_decode(stripslashes($projArr['Project']['name'])),'class'=>'form-control','id'=>'txt_proj','maxlength'=>'50')); ?>
	    </td>
	</tr>
	<tr>
	    <td>Project Short Name:</td>
	    <td>
		<?php if(strtoupper($projArr['Project']['short_name']) == 'WCOS'){
			echo $this->Form->text('short_name',array('value'=>stripslashes($projArr['Project']['short_name']),'class'=>'form-control shrt_alphbts','id'=>'txt_shortProjEdit','maxlength'=>'5','readonly'=>'readonly')); 
		    }else{
			echo $this->Form->text('short_name',array('value'=>stripslashes($projArr['Project']['short_name']),'class'=>'form-control shrt_alphbts','id'=>'txt_shortProjEdit','maxlength'=>'5'));
		    } ?>
	    </td>
	</tr>
	<tr>
	    <td>Default Assign To:</td>
	    <td>
		<select name="data[Project][default_assign]" id="sel_Typ" class="form-control">
		    <option value="" selected="selected">[Select]</option>
		    <?php foreach ($quickMem as $asgnMem) { ?>
    		    <option value="<?php echo $asgnMem['User']['id']; ?>" 
			<?php
			if ((isset($defaultAssign) && ($asgnMem['User']['id'] == $defaultAssign) && ($asgnMem['User']['id'] != SES_ID))) {
			    echo "selected='selected'";
			} else if (!$defaultAssign && ($asgnMem['User']['id'] == SES_ID)) {

			    echo "selected='selected'";
			}
			?>															
    			    ><?php if (($asgnMem['User']['id'] == SES_ID)) {
			    echo 'me';
			} else {
			    echo $this->Format->formatText($asgnMem['User']['name']);
			} ?></option>																
		<?php } ?>
		</select>
	    </td>
	</tr>
	<tr>
	    <td class="v-top">Created by:</td>
	    <td class="auto_tab_fld">
		<?php echo $this->Format->formatText($uname); ?>,
		
		<?php $locDT = $this->Tmzone->GetDateTime(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, $projArr['Project']['dt_created'], "datetime");
		$gmdate = $this->Tmzone->GetDateTime(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, GMT_DATE, "date");
		$dateTime = $this->Datetime->dateFormatOutputdateTime_day($locDT, $gmdate, 'time');
		?>
		<span class="fnt-14-gry"><?php echo $dateTime; ?></span>
	    </td>
	</tr>
	<tr>
	    <td></td>
	    <td class="btn_align">
		<span id="settingldr" style="display:none;">
    		    <img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="Loader" />
    		</span>
		
		<span id="btn" class="project_edit_button">
		    <input type="hidden" name="data[Project][validateprj]" id="validateprj" readonly="true" value="0"/>
		    <input type="hidden" name="data[Project][pg]" id="pg" readonly="true" value="0"/>
		    <input type="hidden" value="<?php echo $uniqid; ?>" name="data[Project][uniq]" id="uniqid"/>
		    <input type="hidden" value="<?php echo $projArr['Project']['id'] ?>" name="data[Project][id]"/>
		
		    <button type="button" value="Save" class="btn btn_blue" onclick= "return submitProject('txt_proj','txt_shortProjEdit')" id="savebtn"><i class="icon-big-tick"></i>Save</button>
		    <!--<button class="btn btn_grey" type="button" onclick="closePopup();"><i class="icon-big-cross"></i>Cancel</button>-->
            <span class="or_cancel">or<a onclick="closePopup();">Cancel</a></span>
    		</span>
	    </td>
	</tr>						
    </table>
<?php $this->Form->end();?>
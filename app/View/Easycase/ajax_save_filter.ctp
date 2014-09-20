<tr>
  <td style="vertical-align: top;">Filter Name:</td>
  <td style="vertical-align: top;">
	  <?php echo $this->Form->text('name',array('value'=>'','class'=>'form-control','id'=>'savefilter_name')); ?>
	  <div class="eror_txt" style="display: none;">Name cannot be left blank!</div>
		<input type="hidden" name="fdate" id="fdate" value="<?php echo $date_val; ?>"/>
		<input type="hidden" name="fduedate" id="fdate" value="<?php echo $duedate_val; ?>"/>
		<input type="hidden" name="fstatus" id="fstatus" value="<?php echo $status_val; ?>"/>
		<input type="hidden" name="ftype" id="ftype" value="<?php echo $type_val; ?>"/>
		<input type="hidden" name="fpriority" id="fpriority" value="<?php echo $priority_val; ?>"/>
		<input type="hidden" name="fmember" id="fmember" value="<?php echo $memebers_val; ?>"/>
		<input type="hidden" name="fassignto" id="fassignto" value="<?php echo $assignto_val; ?>"/>
		<input type="hidden" name="fsearch" id="fsearch" value="<?php echo $search_val; ?>"/>
  </td>
</tr>
<tr>
  <td>&nbsp;</td>
  <td align="left">  	  
  	  <input type="hidden" name="data[Project][validate]" id="validate" readonly="true" value="0"/>
	  <div id="svloader" style="display:none;">
		  <img src="<?php echo HTTP_IMAGES;?>images/del.gif" alt="Loader" width="16" height="16"/>
	  </div>
	  <div id="saveFilBtn" style="display:block;">	  
	      <button type="submit" value="Create" class="btn gry_btn btn_pop fl" onclick="submitfilter();">Save</button>
	      <button type="button" value="Cancel" class="btn gry_btn btn_pop fl" onClick="closePopup();">Cancel</button>
	  </div>
	  <div style="clear:both"></div>	  
  </td>
</tr>
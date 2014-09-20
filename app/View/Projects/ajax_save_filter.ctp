<div style="top:0px; position:absolute; z-index:1; left:50%"><img src="<?php echo HTTP_IMAGES;?>images/arrow.png" alt="arrow"/></div>
<table cellspacing="0" cellpadding="0" width="355px" class="div_pop" align="center" style="top:12px;width:200px">
	  
	  <tr class="ms_hd">
		<td style="padding-left:10px; border-bottom: 1px solid #CCCCCC;" valign="middle">
			<div style="float:left"><h1 style="margin:0;padding:0;text-shadow:0px 0px 0px #fff; font-weight:normal" class="popup_head">Save Custom Filter</font></h1></div>
			
			<div style="" class="ms_cls"><img src="<?php echo HTTP_IMAGES;?>images/popup_close.png" alt="Close" title="Close" onClick="cover_close('cover','inner_save_filter');" style="cursor:pointer"/></div>
		</td>
	</tr>
	  <tr>
		  <td align="left" width="100%">
			  <table cellpadding="2" cellspacing="2" width="100%" align="left" border="0">
				  <tr>
					  <td align="center" valign="top" >
						  <table cellpadding="10" cellspacing="10" border="0" align="center">
						  	 
							  <tr>
								  <td align="right" class="case_fieldprof" valign="top" style="width:150px; font-weight:normal; padding:3px 0px">
									  Filter&nbsp;Name:
								  </td>
								  <td align="left">
									  <?php echo $this->Form->text('name',array('value'=>'','class'=>'text_field','id'=>'txt_Proj','maxlength'=>100,'size'=>30,'style'=>'color:#000;width:150px')); ?>
									  
								  </td>
							  </tr>
                                     <tr style="display:none" id="err_msg"><td></td><td style="color:red;">Name cannot be left blank!</td></tr>
                                   <input type="hidden" name="fdate" id="fdate" value="<?php echo $date_val; ?>"/>
                                        <input type="hidden" name="fstatus" id="fstatus" value="<?php echo $status_val; ?>"/>
                                        <input type="hidden" name="fstatus" id="ftype" value="<?php echo $type_val; ?>"/>
                                        <input type="hidden" name="fstatus" id="fpriority" value="<?php echo $priority_val; ?>"/>
                                         <input type="hidden" name="fstatus" id="fmember" value="<?php echo $memebers_val; ?>"/>
							  <!--<tr>
								  <td align="right" style="padding-top:18px;" class="case_fieldprof" valign="top">
									  Date:
								  </td>
								  <td align="left" style="padding-top:15px;">
                                                  <?php print_r($date);  ?>
                                                  <input type="hidden" name="fdate" id="fdate" value="<?php echo $date_val; ?>"/>
								  </td>
							  </tr>
                                     <tr>
								  <td align="right" style="padding-top:18px;" class="case_fieldprof" valign="top">
									  Status:
								  </td>
								  <td align="left" style="padding-top:15px;">
                                             <input type="hidden" name="fstatus" id="fstatus" value="<?php echo $status_val; ?>"/>
									  <?php print_r($status);  ?>
								  </td>
                                         
							  </tr> 
                                     <tr>
								  <td align="right" style="padding-top:18px;" class="case_fieldprof" valign="top">
									  Types:
								  </td>
								  <td align="left" style="padding-top:15px;">
                                             <input type="hidden" name="fstatus" id="ftype" value="<?php echo $type_val; ?>"/>                                                  
									  <?php print_r($type);  ?>
                                             
								  </td>
							  </tr>  
                                     <tr>
								  <td align="right" style="padding-top:18px;" class="case_fieldprof" valign="top">
									  Priority:
								  </td>
								  <td align="left" style="padding-top:15px;">
                                             <input type="hidden" name="fstatus" id="fpriority" value="<?php echo $priority_val; ?>"/>     
									  <?php echo $priority; ?>
								  </td>
							  </tr>    
                                     <tr>
								  <td align="right" style="padding-top:18px;" class="case_fieldprof" valign="top">
									  Members:
								  </td>
								  <td align="left" style="padding-top:15px;">
                                             <input type="hidden" name="fstatus" id="fmember" value="<?php echo $memebers_val; ?>"/>     
									  <?php echo $memebers; ?>
								  </td>
							  </tr>     -->                                                                     
							  
							 
							  <tr>
								  <td>&nbsp;</td>
								  <td align="left">
								  	  
								  	  <input type="hidden" name="data[Project][validate]" id="validate" readonly="true" value="0"/>
									  <span id="loader" style="display:none;">
										  <img src="<?php echo HTTP_IMAGES;?>images/del.gif" alt="Loader" width="16" height="16"/>
									  </span>
									  <span id="btn">
										  <button type="submit" value="Create" name="crtProject" class="blue" onclick="submitfilter();">Confirm</button>&nbsp;&nbsp;or&nbsp;&nbsp;<a href="javascript:void(0);" onClick="cover_close('cover','inner_save_filter');">Cancel</a>
									  </span>
									  
								  </td>
							  </tr>
							   
						  </table>
					  </td>
				  </tr>
			  </table>
		  </td>
	  </tr>
  </table>

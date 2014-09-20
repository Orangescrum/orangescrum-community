<!--[if lt IE 10]>
    <style>
        .add_temp_wd{width:30%;}
    </style>	
<![endif]-->
<?php
$tid = isset($TempalteArray['CaseTemplate']['id'])?$TempalteArray['CaseTemplate']['id']:"";
$tttl = isset($TempalteArray['CaseTemplate']['name'])?$TempalteArray['CaseTemplate']['name']:"";
$tdesc = isset($TempalteArray['CaseTemplate']['description'])?$TempalteArray['CaseTemplate']['description']:"";
?>
<div class="row-fluid">
	<article class="span12 data-block nested">
		<div class="data-container">
			<section class="tab-content">
				<div class="tab-pane" id="horizontal">	
					<table width="100%" border="5" cellspacing="2" cellpadding="2" align="center">
						<tr align="right">
							<td colspan="2" align="right" style="padding-right:20px;" >Fields marked <font color="red">*</font> are mandatory Fields</td>
					   </tr>
						<tr>
							<td valign="top">
								<table class="usr_add_768" border="5" cellspacing="5" cellpadding="5" align="center" >
								<tr height="35px">
								<td valign="top" colspan="2" width="100%">
								<?php echo $this->Form->create('User',array('url'=>'/users/add_template','id'=>'addTemplste','onsubmit'=>'return validatTemplate()')); ?>
								<?php echo $this->Form->text('id',array('type'=>'hidden','name'=>'data[User][id]','value' =>$tid)); ?>
								<table cellpadding="6" cellspacing="6" align="center" border="5" width="100%"  >
									<tr><td colspan="2" class="font-headings" style="font-size:15px" align="left"></td></tr>
									<tr><td colspan="2"><div id="divide"></div></td></tr>
								
									<tr height="40px">
									<td  class="case_fieldprof add_temp_wd" align="right" valign="center"><font color="red">* </font>
										Title:
									</td>
									<td align="left" >
										<?php echo $this->Form->text('title',array('size'=>'45','class'=>'text_field','style'=>'width:425px;','id'=>'title','maxlength'=>'100','value' => $tttl)); ?>
								
									</td>
									</tr>
									<tr height="40px" valign="top">
									<td class="case_fieldprof add_temp_wd" align="right" valign="top"><font color="red">* </font>
										Description:
									</td>
									<td align="left" style="color:#000000;">				
										<?php echo $this->Form->textarea('desc',array('class'=>'text_field','style'=>'width:425px;height:100px;','id'=>'desc','value' => $tdesc)); ?>
									</td>
									</tr>
									<tr height="40px">
										<td></td>
										<td align="left">
										<span id="subprof1">
										<button type="submit" value="Update" name="submit_template" id="submit_template" class="pop_btn" onclick="document.getElementById('update_temp').value='1'"><?php if($tid == ''){echo "Save";}else{echo "Update";}?></button>
										<input type="hidden" name="data[User][update_temp]" id="update_temp" value="0">
										<span class="fnt_opensans">&nbsp;&nbsp;or&nbsp;&nbsp;
										<a href="<?php echo $referer; ?>">Cancel</a></span>
									</span>
									<span id="subprof2" style="display:none">
									 <img src="<?php echo HTTP_IMAGES; ?>images/del.gif" alt="loading..." title="loading..."/> 
									</span>
									</td>
									</tr>
									</table>
									<?php echo $this->Form->end(); ?>
								</td>
								</tr>
								</table>			
							</td>
						</tr>
					</table>
				</div>
			</section>
		</div>
	</article>
</div>	
<script>
	/*jQuery.validator.addMethod("noName", function(value, element) {
		return this.optional(element) || (value != 'username');
	}, "* Please enter a valid username.");*/
	
</script>

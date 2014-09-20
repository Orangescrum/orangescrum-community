<!--[if IE 8]>
    <style>
        .add_project_template .cs_prj_tmpl{padding-left:3px}
    </style>	
<![endif]-->
<!--[if IE 9]>
    <style>
        .add_temp_prj_ie .case_fieldprof{padding-right:4px;} 
    </style>	
<![endif]-->
<script>
$(document).ready(function(){
    
 	var clonedRow = $('.sectionpj').clone().html();

    var appendRow = '<tr class="appendtotr"><td style="padding-left:7px;">' + clonedRow + '</td><td><div align="center" width="226px" id="btnRemove" valign="top"><a style="color:red;" href="#">Remove</a></div></td></tr>';  
      
	 $('#btnAddpj').click(function()
	 { 
		//$('.appendtotr:last').after(appendRow);
		alert("ji");
	 });

     $('#btnRemove').live('click',function()
	 {
		 var rowLength = $('.appendtotr').length;
			 
		 if(rowLength > 1)
		 {
			deleteRow(this);
		 }
		
	 });
     function deleteRow(currentNode)
	 {
		$(currentNode).parent().parent().remove();
	 }
});
function add_more_temp(){
	var clonedRow = $('.sectionpj').clone().html();

   var appendRow = '<tr class="appendtotr"><td style="padding-left:5px;">' + clonedRow + '</td><td><div align="center" width="226px" id="btnRemove" valign="top"><a style="color:red;" href="#">Remove</a></div></td></tr>';
	$('.appendtotr:last').after(appendRow);
}
</script>
<div class="row-fluid">
	<article class="span12 data-block nested">
		<div class="data-container">
			<section class="tab-content">
				<div class="tab-pane" id="horizontal">
					<header style="border:0px #FFFFFF">
						<!--<h2>
							<?php
							/*if($tid) {
								echo "Project Template > Edit";
							}
							else {
								echo "Project Template > Add";
							}*/
							?>
						</h2>-->
					</header>		
					<table style="width:100%;margin-top:-40px" align="center">
						<tr align="right">
							<td colspan="2" align="right" style="padding-right:20px;">Fields marked <font color="red">*</font> are mandatory Fields</td>
					   </tr>
						<tr>
							<td valign="top">
								<table width="100%" align="center" >
								<tr height="35px">
								<td valign="top" colspan="2" width="100%">
								<?php
									echo $this->Form->create('ProjectTemplateCase',array('url'=>'/projects/add_template','id'=>'addTemplste','onsubmit'=>'return validateProjTemplate()'));?>
								<table  align="left" class="add_temp_tbl add_temp_prj_ie">
									<tr><td colspan="2" class="font-headings" style="font-size:15px" align="left"></td></tr>
									<tr><td colspan="2"><div id="divide"></div></td></tr>
									<tr height="40px">									
									<td style="padding: 3px 7px;" colspan="2">
									<div  class="fl case_fieldprof"><font color="red">* </font>
										Template Name:
									</div>
									<div id="temp_mod_div" class="fl">
										<select name="data[ProjectTemplateCase][template_id]" id="temp_id_sel" onchange="open_template(this.value)">
									<?php
									if(count($template_mod))
									{ ?>
										<option value="0" selected>[Select]</option>
										<?php foreach($template_mod as $template_mod)
										{ ?>
											<option <?php if(@$template_mod_id == $template_mod['ProjectTemplate']['id']){ echo "selected ";}?> value="<?php echo $template_mod['ProjectTemplate']['id'];?>"><?php echo $this->Format->formatText($template_mod['ProjectTemplate']['module_name']); ?></option>
											<?php
										} ?>
										<option value="">...New Template</option>
									<?php
									}
									else
									{
									?>
										<option value="">...New Template</option>
										<option value="0" selected>[Select]</option>
									<?php
									}
									?>
									</select>
									</div>
									<div id="subprof5" style="display:none;float:left;margin-top:3px;">
									 <img src="<?php echo HTTP_IMAGES; ?>images/del.gif" alt="loading..." title="loading..."/> 
									</div>
								</td>
								<td></td>
									</tr>
									<tr class="appendtotr">
										<td  class="case_fieldprof sectionpj" align="left">
											<div class="add_project_template">
												<table>
													<tr>
														<td>
															<div class="fl case_fieldprof cs_prj_tmpl">
																<font color="red">* </font>Task Title:
															</div>
															<div class="fl">
																<?php echo $this->Form->text('title',array('size'=>'45','name'=>'data[ProjectTemplateCase][title][]','class'=>'txt','id'=>'title','maxlength'=>'100')); ?>
															</div>
														</td>
													</tr>
													<tr height="40px" valign="top">
														
														<td align="left" style="color:#000000;">
															<div class="fl case_fieldprof">Task Description:</div>
															<div class="fl">				
															<?php echo $this->Form->textarea('description',array('class'=>'text_field','name'=>'data[ProjectTemplateCase][description][]','class'=>'txt_area','id'=>'description')); ?>
															</div>
														</td>
													</tr>
												</table>
											</div>
									</td>
									<td><div onclick="add_more_temp();"><a style="color:#5895B4; cursor:pointer"  >Add new Row</a></div></td>
									</tr>
									<tr height="40px">
										<td align="left" style="padding-left:143px">
										<span id="subprof1">
										<button  value="Save" name="submit_template" id="submit_template" class="pop_btn">Save</button>
										<span class="fnt_opensans">&nbsp;&nbsp;or&nbsp;&nbsp;
										<a href="<?php echo $referer; ?>">Cancel</a></span>
									</span>
									<span id="subprof2" style="display:none;">
									 <img src="<?php echo HTTP_IMAGES; ?>images/del.gif" alt="loading..." title="loading..."/> 
									</span>
									</td>
									<td></td>
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

<div id="add_temp_mod" class="inner" style="position:fixed;left:0;top:0px;width:100%;">
<table cellspacing="0" cellpadding="0" width="700px" class="div_pop" align="center" style="margin:40px auto;">
	<tr class="ms_hd">
		<td style="padding-left:10px;" valign="middle">
			<div style="float:left"><h1 style="margin:0;padding:0;" class="popup_head">New Template</font></h1></div>
			<div style="float:right;padding-right:5px;"><img src="<?php echo HTTP_IMAGES;?>images/popup_close.png" alt="Close" title="Close" onClick="cover_close('cover','add_temp_mod');unsel_temp();" style="cursor:pointer" /></div>
		</td>
	</tr>
	  <tr>
		  <td align="left" width="100%">
			  <table cellpadding="10" cellspacing="10" border="0" align="center" width="100%">

				  <tr>
					  <td align="center" valign="top" >
						  <table cellpadding="4" cellspacing="4" border="0" align="center">
						  	  <tr height="18px">
								  <td></td>
								  <td align="left" valign="top" style="color:#FF0000;">
									 <div id="err_msg2" style="color:#FF0000;display:none;padding-top:10px;"></div>
								  </td>
							  </tr>
							  <tr>
								  <td align="right" class="case_fieldprof">
									  Template Name:
								  </td>
								  <td align="left">
									  <?php echo $this->Form->text('name',array('value'=>'','class'=>'text_field','id'=>'txt_template','maxlength'=>100,'size'=>40)); ?>
								  </td>
							  </tr>
							<tr>
								<td>&nbsp;</td>
								<td align="left" style="padding-top:5px;">
								  	  <input type="hidden" name="data[ProjectTemplate][validate]" id="validate" readonly="true" value="0"/>
								  <span id="loader" style="display:none;">
									  <img src="<?php echo HTTP_IMAGES;?>images/del.gif" alt="Loader" width="16" height="16"/>
								  </span>
								  <span id="btn">
									  <button type="button" value="Create Template" name="crtTempmod" style="margin-left:3px;" id="add_new_template" class="pop_btn" onclick="template_module_add()">Create</button>&nbsp;&nbsp;or&nbsp;&nbsp;<a href="javascript:void(0);" onClick="cover_close('cover','add_temp_mod');unsel_temp();">Cancel</a>
								  </span>
							  </td>
							  </tr>
						  </table>
					  </td>
				  </tr>
				  <tr><td colspan="2">&nbsp;</td></tr>
			  </table>
		  </td>
	  </tr>
  </table>
  </form>
</div>
 

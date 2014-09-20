<table width="100%" border="5" cellspacing="2" cellpadding="2" align="center" >
	<tr>
		<td align="left">
			<h1 class="toplink">Project > Set Preference For Case</h1>
		</td>
	</tr>
	<tr>
		<td align="center">
			<table border="0" cellspacing="0" cellpadding="0" style="border:1px solid #FF0000;" align="center">
				<tr height="40px">
					<td class="case_fieldprof">
						Project: 
					</td>
					<td>
						<select id="project_id" name="project_id" class="text_field" onChange="loaddiv('project_id')" style="width:350px;-moz-border-radius:3px 3px 3px 3px;">
							<option value="0">Select</option>
							<?php
							foreach($prj as $pr){ 
							?>
							<option value="<?php echo $pr['Project']['id']; ?>" > 
							<?php echo $pr['Project']['name']; ?>
							</option>
							<?php } ?>
						</select>
					</td>
					<td>
						<span id="loader_id1" style="display:none;">
							<img src="<?php echo HTTP_IMAGES;?>images/del.gif" width="16" height="16" alt="loading..." title="loading..."/>
						</span>
					</td>
				</tr>
				<tr>
					<td colspan="3">
						<div id="allcasesetting">
							
						</div>
					</td>
				</tr>
				<tr>
					<td>
					</td>
					<td colspan="2">
						<div id="but" align="left" style="display:none;"  class="blue small">
							<span id="savspn">
								<button type="button" value="Post" name="data" style="margin-left:3px;margin:5px 0px;" class="" onclick="postcasedata('loader_id','but');">Save</button>&nbsp;&nbsp;or&nbsp;&nbsp;<a href="<?php echo HTTP_ROOT; ?>dashboard">Cancel</a>
							</span>
							<span id="loader_id" style="display:none;">
								<img src="<?php echo HTTP_IMAGES;?>images/del.gif" width="16" height="16" alt="loading..." title="loading..."/>
							</span>	
						</div>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>


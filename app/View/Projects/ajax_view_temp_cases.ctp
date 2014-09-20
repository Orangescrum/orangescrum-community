<div class="popup_title">
    <span><i class="icon-create-proj"></i><?php echo $mod_name; ?></span>
    <a href="javascript:jsVoid();"><div class="fr close_popup">X</div></a>
</div>
<div class="popup_form">
    <table cellpadding="0" cellspacing="0" class="col-lg-12">
	<tr>
	    <td align="left" width="100%">	
		<div style="height:400px;overflow:auto">
		    <table cellpadding="2" cellspacing="2" width="100%" align="left" style="padding-left:0px;" border="0">
			<tr>
			    <td align="center" valign="top" >
				<div style="max-height:350px; overflow:auto">
				    <table border="0" style="border:1px solid #DCDCDC" width=100%>
					<tr height="28px" class="tophead">
					    <td style="width:30px;" align="center">Sl#</td>
					    <td style="padding-left:10px;">Title</td>
					    <td style="padding-left:10px;">Description</td>
					    <!--<td width="35px" style="padding-left:10px;">Created</td>-->
					</tr>
					<?php
					$count = 0;
					$class = "";
					if ($temp_dtls_cases) {
					    foreach ($temp_dtls_cases as $tmp_dtls) {
						$count++;
						if ($count % 2 == 0) {
						    $class = "row_col";
						} else {
						    $class = "row_col_alt";
						}
						?>
						<tr class="<?php echo $class; ?>" height="18px" id="listing<?php echo $count; ?>">
						    <td style="padding-right:5px;text-align:right">
							<?php echo $count; ?>
						    </td>
						    <td style="padding-left:10px;<?php echo $class; ?>">
							<?php echo $this->Format->shortlength(($this->Format->formatText($tmp_dtls['ProjectTemplateCase']['title'])), 35); ?>
						    </td>
						    <td style="padding-left:10px;<?php echo $class; ?>">
							<?php echo $this->Format->formatCms($tmp_dtls['ProjectTemplateCase']['description']); ?>
						    </td>
						</tr>
					    <?php } ?>
				    </table>
				</div>
			    </td>
			</tr>
			<?php } else { ?>
			<tr>
			    <td align="center" colspan="3">
			<center style="font-weight:normal;color:#FF0000;padding:10px;">No tasks(s) available.</center>
			</td>

			</tr>
			<?php } ?>					  
		    </table>
		</div>
	    </td>
	</tr>
    </table>
</div>





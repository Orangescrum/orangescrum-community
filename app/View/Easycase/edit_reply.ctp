<?php $caseinfo = $case_info['easycases']; ?>
<table cellpadding="0" cellspacing="0" class="edit_rep_768 col-lg-12">
	<tr>
		<td>
			<textarea name="edit_reply_txtbox<?php echo $caseinfo['id'];?>" id="edit_reply_txtbox<?php echo $caseinfo['id'];?>" rows="3" class="reply_txt_ipad col-lg-12">
				<?php // echo trim($caseinfo['message']); ?>
                    <?php echo htmlentities(trim($caseinfo['message'])); ?>
			</textarea>
		</td>
	</tr>
	<tr>
		<td align="right">
			<div id="edit_btn<?php echo $caseinfo['id'];?>" class="fr">
				<button type="button" value="Save" style="margin:5px;padding:3px 32px 3px 32px;" class="btn btn_blue" onclick="<?php if($reply_flag){?>save_editedvalue_reply<?php }else{?>save_editedvalue<?php }?>(<?php echo $caseinfo['case_no'];?>,<?php echo $caseinfo['id'];?>,<?php echo $proj_id;?>,'<?php echo $caseinfo['uniq_id'];?>');" ><i class="icon-big-tick"></i>Save</button>
				<button type="reset" style="margin:5px;padding:3px 32px 3px 32px;" class="btn btn_grey" onclick="<?php if($reply_flag){?>cancel_editor_reply<?php }else{?>cancel_editor<?php }?>(<?php echo $caseinfo['id'];?>);"><i class="icon-big-cross"></i>Cancel</button>
			</div>
			<div id="edit_loader<?php echo $caseinfo['id'];?>" class="fr loading" style="display:none;margin:6px;" title="Loading"></div>
		</td>
	</tr>
</table>

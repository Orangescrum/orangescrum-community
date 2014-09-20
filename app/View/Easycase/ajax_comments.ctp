<?php 
$comments = $this->Casequery->getAllComments($replyid);
if(count($comments))
{
?>
<div style="width:580px;padding:0px;background:#FFFFFF">
	<?php
	$cmntId = "";
	foreach($comments as $cmnt) 
	{
		$cmntId = $cmnt['CaseComment']['id'];
		?>
		<div class="comentsdiv" id="comntdiv<?php echo $cmntId; ?>">
		<table width="100%" onmouseover="displayEdit('hover<?php echo $cmntId; ?>')" onmouseout="hideEdit('hover<?php echo $cmntId; ?>')">
			<tr height="20px" >
				<td width="84%" align="left">
					<span id="cmntxt<?php echo $cmntId; ?>">
						<?php 
						echo $caseComnt = nl2br($this->Format->formatText($cmnt['CaseComment']['comments'])); 
						//echo $this->Format->html_wordwrap($caseComnt,80);
						?>
					</span>
					<span id="cmntspan<?php echo $cmntId; ?>" style="display:none;">
						<textarea id="cmntxa<?php echo $cmntId; ?>" rows="2" style="padding:2px;width:98%" class="txtarea" onKeyUp="this.style.boxShadow='0 0 4px #D9D9D9 inset';resizeTextarea('cmntxa<?php echo $cmntId; ?>','50','50','2');"><?php echo $this->Format->formatText($cmnt['CaseComment']['comments']); ?></textarea>
					</span>
				</td>
				<td align="right" valign="middle">
					<?php
					if($cmnt['CaseComment']['user_id'] == SES_ID) 
					{
					?>
					<span style="display:none"id="hover<?php echo $cmntId; ?>">
					<span id="hoverEdit<?php echo $cmntId; ?>">
						<img src="<?php echo HTTP_IMAGES; ?>images/comments.gif" border="0" style="text-decoration:none;"/><a href="javascript:void(0);" onclick="openCommentEdit('cmntxt<?php echo $cmntId; ?>','cmntspan<?php echo $cmntId; ?>','cmntxa<?php echo $cmntId; ?>','hoverEdit<?php echo $cmntId; ?>','hoverPost<?php echo $cmntId; ?>')">Edit</a>
					</span>
					<span id="hoverPost<?php echo $cmntId; ?>" style="display:none;">
						<a href="javascript:void(0);" onclick="postCommentsEdit('<?php echo $cmntId; ?>','cmntxt<?php echo $cmntId; ?>','cmntspan<?php echo $cmntId; ?>','cmntxa<?php echo $cmntId; ?>','hoverEdit<?php echo $cmntId; ?>','hoverPost<?php echo $cmntId; ?>','','')">Save</a>
						<font size="1">or</font>
						<a href="javascript:void(0);" onclick="openCommentEdit('cmntxt<?php echo $cmntId; ?>','cmntspan<?php echo $cmntId; ?>','cmntxa<?php echo $cmntId; ?>','hoverEdit<?php echo $cmntId; ?>','hoverPost<?php echo $cmntId; ?>')">Cancel</a>
						</span>
					</span>
					<?php
					}
					?>
				</td>
			</tr>
			<?php
			$filesArr = $this->Casequery->getCommentFiles($cmntId);
			if(count($filesArr))
			{
			?>
			<tr>
				<td align="left" style="padding:5px 0px;">
					<table cellpadding="2" cellspacing="2" align="left" border="0" >
					<?php
					$fc = 0;
					$images = ""; $caseFileName = "";
					foreach($filesArr as $getFiles)
					{
						$caseFileName = $getFiles['CaseFile']['file'];
						if(file_exists(DIR_CASE_FILES.$caseFileName))
						{
							if($fc%3 == 0)
							{
							?>
							<tr height="35px">
							<?php
							}
							?>
								<td valign="top" width="28px"><?php echo $this->Format->imageType($caseFileName,32,32,1); ?></td>
								<td valign="top" style="padding-left:5px;font-weight:normal;" align="left" >
									<a href='<?php echo HTTP_ROOT; ?>easycases/download/<?php echo $caseFileName; ?>' style='text-decoration:underline;color:#0571B5'>
										<?php echo $this->Format->shortLength($caseFileName,25); ?>
									</a>
									<br/>
									<?php echo $this->Format->getFileSize($getFiles['CaseFile']['file_size']); ?>
								</td>
							<?php
							$fc = $fc+1;
							if($fc%3 == 0)
							{
							?>
							</tr>
							<?php
							}
							$images.= $this->Format->displayImages($caseFileName);
						}
					}
					?>
					</table>
					<?php
					if($images)
					{
					?>
					<div style="clear:both"></div>
					<span class="exp_menu" style="cursor:pointer;" onclick="hideShowImg('showimgdtls<?php echo $cmntId; ?>','shTxt<?php echo $cmntId; ?>')">
						&nbsp;<font style="font-size:12px;color:#03F;cursor:pointer;text-decoration:underline;font-weight:normal;" id="shTxt<?php echo $cmntId; ?>">Show Images</font>
					</span>
					<div id="showimgdtls<?php echo $cmntId; ?>" style="display:none;margin-top:5px">
						<?php echo $images; ?>
					</div>
					<?php
					}
					?>
				</td>
			</tr>
			<?php
			}
			?>
			<tr>
				<td colspan="2" align="left" style="color:#7B94C3;">
					<?php
					$curDate = $this->Tmzone->GetDateTime(SES_TIMEZONE,TZ_GMT,TZ_DST,TZ_CODE,GMT_DATETIME,"date");
					$comntDt = $this->Tmzone->GetDateTime(SES_TIMEZONE,TZ_GMT,TZ_DST,TZ_CODE,$cmnt['CaseComment']['dt_created'],"datetime");
					echo $this->Datetime->dateFormatOutputdateTime_details($comntDt,$curDate);
					if($cmnt['CaseComment']['user_id'] != SES_ID) {
						$userShrtName = $this->Casequery->getUserDtls($cmnt['CaseComment']['user_id']);
						$shortName = $userShrtName['User']['short_name'];
					}
					else {
						$shortName = "me";
					}
					?> by <?php echo $shortName; ?>
				</td>
			</tr>
		</table>
		</div>
	<?php
	}
	?>
</div>
<?php
}
?>

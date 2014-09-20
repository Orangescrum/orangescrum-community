	<?php
		if(count($file)){
		$count = $lastCountFiles;
		foreach($file as $fil)
		{
			$count++;
			$caseDtUploaded = $fil['Archive']['dt_created'];
			$updated = $this->Tmzone->GetDateTime(SES_TIMEZONE,TZ_GMT,TZ_DST,TZ_CODE,$caseDtUploaded,"datetime");
			$updatedCur = $this->Tmzone->GetDateTime(SES_TIMEZONE,TZ_GMT,TZ_DST,TZ_CODE,GMT_DATETIME,"date");
			$displayTime = $this->Datetime->dateFormatOutputdateTime_day($updated,$updatedCur);
	?>
	<tr class="tr_all all_first_rows_files" id="fllisting<?php echo $count; ?>" data-value="<?php echo $count; ?>">
		<td>
			<input id="file<?php echo $count; ?>" value="<?php echo $fil['CaseFile']['id'];?>" type="checkbox" style="cursor: pointer;" class="mglt chkOneArcFile">
		</td>
		<td align="right"><a href="<?php echo HTTP_ROOT; ?>dashboard#details/<?php echo $fil['Easycase']['uniq_id']?>">#<?php echo $fil['Easycase']['case_no']?></a></td>
		<td></td>
		<td>
			<?php 
				$file_type = $fil['CaseFile']['file'];
				echo $this->Format->getFileType($file_type);
			?>
			<div title="<?php echo $fil['CaseFile']['file']; ?>" class="fl">
				<div class="file_name">
				<a href='<?php echo HTTP_ROOT; ?>easycases/download/<?php echo $fil['CaseFile']['file']; ?>' style='color:#000;text-decoration:underline;'>
					<?php echo $fil['CaseFile']['file']; ?>
				</a>
				</div>
				<div class="fnt999">Archived: <font><?php echo $displayTime; ?></font></div>
			</div>
			<div class="cb"></div>
		</td>
		<td align="right">
			<div>
				<?php echo $this->Format->getFileSize($fil['CaseFile']['file_size']);  ?>
			</div>
		</td>
		<td></td>
		<td>
			<?php 
				if($fil['Easycase']['project_id']) {
				$projectname = $this->Casequery->getpjname($fil['Easycase']['project_id']);
				echo $projectname;
				}
			 ?>
		</td>
	</tr>
	<?php
		} }else{
	?>
	<tr>
		<td colspan="7">
			<?php echo $this->element('no_data', array('nodata_name' => 'filelist')); ?>
		</td>
	</tr>
	<?php } ?>

	<?php if($lastCountFiles < 10){ ?>
		<?php /*?><input type="hidden" id="all" class="all_count" value="<?php echo $count;?>"><?php */?>
		<input type="hidden" id="filepjid" class="filepjid" value="<?php echo $pjid;?>">
		<input type="hidden" id="totalFiles" class="total_file_count" value="<?php echo $caseCountt;?>">
		<input type="hidden" id="displayedFiles" value="<?php echo ARC_FILE_PAGE_LIMIT; ?>">
	<?php } ?>
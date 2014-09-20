<select name="data[Easycase][assign_to]" id="assign" class="case_fields">
	<?php
	if(isset($quickMemAsgn) && count($quickMemAsgn))
	{
		?>
		<option value="">me</option>
		<?php
		foreach($quickMemAsgn as $getqkcsmem)
		{
			if($getqkcsmem['User']['id'] != SES_ID)
			{
				?>
				<option 
				<?php
				if($getqkcsmem['User']['id'] == $assign)
				{
					echo "selected";
				}
				?>
				value="<?php echo $getqkcsmem['User']['id']; ?>"><?php echo $this->Format->formatText($getqkcsmem['User']['name']); ?></option>
				<?php
			}
		}
	}
	else
	{
	?>
		<option value="<?php echo SES_ID; ?>">me</option>
	<?php
	}
	?>
</select>

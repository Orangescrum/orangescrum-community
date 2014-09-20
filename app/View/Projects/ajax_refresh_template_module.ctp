<select name="data[ProjectTemplateCase][template_id]" id="temp_id_sel" style="padding:4px;margin-left:5px;width:430px;border-radius: 3px;" onchange="open_template(this.value)">
					<?php
					if(count($template_mod))
					{ ?>
						<option value="0">[Select]</option>
						<?php foreach($template_mod as $template_mod)
						{ ?>
							<option <?php if($tmp_id ==$template_mod['ProjectTemplate']['id'] ){echo "selected";}?> value="<?php echo $template_mod['ProjectTemplate']['id'];?>"><?php echo $this->Format->formatText($template_mod['ProjectTemplate']['module_name']); ?></option>
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

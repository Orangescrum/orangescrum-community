<link rel="stylesheet" type="text/css" href="<?php echo CSS_PATH.'wick_new.css?v='.RELEASE;?>" />
<script type="text/javascript" src="<?php echo JS_PATH.'wiki.js?v='.RELEASE;?>"></script>
<script type="text/javascript" src="<?php echo JS_PATH.'jquery.textarea-expander.js';?>"></script>
<center><div id="err_msg" style="color:#FF0000;display:none;"></div></center>
<?php  echo $this->Form->create('Project',array('url'=>'/projects/add_project','name'=>'projectadd','onsubmit'=>'return projectAdd(\'txt_Proj\',\'txt_shortProj\',\'loader\',\'btn\')')); ?>
    <div class="data-scroll">
    <table cellpadding="0" cellspacing="0" class="col-lg-12">
	<tr>
	    <td class="popup_label">Project Name:</td>
	    <td>
		<?php echo $this->Form->text('name',array('value'=>'','class'=>'form-control','id'=>'txt_Proj','placeholder'=>"My Project",'maxlength'=>'50')); ?>
		
	    </td>
	</tr>
	<tr>
	    <td>Short Name:</td>
	    <td>
		<?php echo $this->Form->text('short_name', array('value' => '', 'class' => 'form-control ttu', 'id' => 'txt_shortProj','placeholder'=>"MP",'maxlength'=>'5')); ?>
		<span id="ajxShort" style="display:none">
		    <img src="<?php echo HTTP_IMAGES; ?>images/del.gif" width="16" height="16"/>
		</span>
		<span id="ajxShortPage"></span>
	    </td>
	</tr>
	<?php if(!isset($is_active_proj) || $is_active_proj){?>
	<tr>
	    <td class="v-top">
		<div style="text-align:right">
    		    <span id="add_new_member_txt">
			    <?php if (count($userArr) < 2) { ?>
				Add new Users:
			    <?php } else { ?>	
				Add Users:
			    <?php } ?>	
    		    </span>
    		    <div class="opt_field">(optional)</div>
    		</div>
	    </td>
	    <td style="text-align:left">
		<div class="fl check_user">
		    <?php foreach ($userArr AS $k => $usr) { ?>
			<label class="checkbox-inline" style="margin:0 10px 5px 0;">
			<input type="checkbox" checked="checked" name="data[Project][members][]"  onclick="addremoveadmin(this)"  value="<?php echo $usr['User']['id']; ?>"/>
			&nbsp;<span id="puser<?php echo $usr['User']['id']; ?>"><?php echo $usr['User']['name']; ?></span>
			<?php if ($usr['CompanyUser']['user_type'] == 1) { ?>
			    <span class="user_green">(owner)</span>
			<?php } else { ?>
			    <span class="user_red">(admin)</span>
			<?php } ?>
			</label>
		    <?php } ?>								
		</div>
		<textarea id="members_list"  class="wickEnabled form-control expand" rows="2" wrap="virtual" name="data[Project][members_list]"></textarea>
		<div class="user_inst">(Use comma to separate multiple email ids)</div>
		<div id="err_mem_email" style="display: none;color: #FF0000;"></div>
		<div id="autopopup"></div>
	    </td>
	</tr>
	<!--<tr id="default_assignto_tr" <?php if(count($userArr)<2){?>style="display: none;" <?php }?>>
	    <td>Default Assign To:</td>
	    <td>
		<select id="select_default_assign" class="form-control" name="data[Project][default_assign]">
    		    <option value="">-Select-</option>	
			<?php foreach ($userArr AS $k => $usr) { ?>
			    <option value="<?php echo $usr['User']['id']; ?>" <?php if (!$k) { ?>selected<?php } ?>><?php echo $usr['User']['name']; ?></option>	
			<?php } ?>
    		</select>
	    </td>
	</tr>-->
	<?php } ?>
	<?php /*?><?php if(count($templates_modules)>1){?>
	<tr id="default_projtemp_tr"  >
	    <td>Template:<div class="opt_field">(optional)</div></td>
	    <td class="v-top">
		<select name="data[Project][module_id]" id="sel_Typ" class="form-control" onchange="view_btn_case(this.value);">
    		    <option value="0" selected>[Select]</option>
			<?php foreach ($templates_modules as $templates_modules => $val) { ?>
			    <option value="<?php echo $val['ProjectTemplate']['id'] ?>"><?php echo $val['ProjectTemplate']['module_name'] ?></option>
			<?php } ?>
    		</select>
		<span id="btn_cse" style="display:none;margin-top:10px;">
    		    <a href="javascript:jsVoid();" style="margin-left:3px;width:100px;font-size:12px;" class="blue small" onclick="viewTemplateCases();">View Task</a>
    		</span>
    		<span id="btn_load" style="display:none;margin-top:10px;">
    		    <a href="javascript:jsVoid()" style="text-decoration:none;cursor:wait;margin-left:3px;width:100px;">
    			Loading...<img src="<?php echo HTTP_IMAGES; ?>images/del.gif" width="16" height="16" alt="loading..." title="loading..."/>
    		    </a>
    		</span>
	    </td>
	</tr>
	 <?php }?><?php */?>
    </table>    
    </div>
    <div style="padding-left:145px;">
    <?php
		$totProj = "";
		if ((!$user_subscription['is_free']) && ($user_subscription['project_limit'] != "Unlimited")) {
		    $totProj = $this->Format->checkProjLimit($user_subscription['project_limit']);
		}
		if ($totProj && $totProj >= $user_subscription['project_limit']) {
		    ?>
    		<font color="#FF0000">Sorry, Project Limit Exceeded!</font>
    		<br/>
    		<font color="#F05A14"><a href="<?php echo HTTP_ROOT; ?>pricing">Upgrade</a> you account to create more projects</font>
		    <?php
		} else {
		    ?>
    		<input type="hidden" name="data[Project][validate]" id="validate" readonly="true" value="0"/>
    		<span id="loader" style="display:none;">
    		    <img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="Loader"/>
    		</span>
    		<span id="btn">
    		    <button type="button" value="Create" name="crtProject" class="btn btn_blue" onclick="return projectAdd('txt_Proj','txt_shortProj','loader','btn');"><i class="icon-big-tick"></i>Create</button>
		    <button class="btn btn_grey" type="button" onclick="closePopup();"><i class="icon-big-cross"></i>Cancel</button>
    		</span>
		    <?php
		}
		?>
        </div>
<?php $this->Form->end();?>
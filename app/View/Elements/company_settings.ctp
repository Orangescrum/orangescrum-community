<div class="tab tab_comon mycompany_ipad">
    <ul class="nav-tabs">
	<li <?php if(PAGE_NAME == 'mycompany') {?>class="active" <?php }?>>
	    <a href="<?php echo HTTP_ROOT.'my-company';?>" id="sett_mail_noti_prof">
                <div class="fl grp_comp"></div>
                <div class="fl">My Company</div>
                <div class="cbt"></div> 
	    </a>
	</li>
	<li <?php if(PAGE_NAME == 'groupupdatealerts') {?>class="active" <?php }?> style="width:205px">
	    <a href="<?php echo HTTP_ROOT.'reminder-settings';?>" id="sett_mail_repo_prof">
                <div class="fl grp_alt"></div>
                <div class="fl">Daily Catch-Up</div>
                <div class="cbt"></div>
	    </a>
	</li>
	<li <?php if(PAGE_NAME == 'importexport'|| PAGE_NAME=='csv_dataimport' || PAGE_NAME=='confirm_import') {?>class="active" <?php }?>>
	    <a href="<?php echo HTTP_ROOT.'import-export';?>" id="sett_imp_exp_prof">
                <div class="fl grp_impx"></div>
                <div class="fl">Import & Export</div>
                <div class="cbt"></div>
	    </a>
	</li>
	<li <?php if(PAGE_NAME == 'task_type') {?>class="active" <?php }?>>
	    <a href="<?php echo HTTP_ROOT.'task-type';?>" id="sett_task_type">
                <div class="fl" style="height: 18px;width: 18px;margin-right: 6px;">
		    <img src="<?php echo HTTP_ROOT."img/tasktype.png";?>"  width="16px" height="16px"/>
		</div>
                <div class="fl">Task Type</div>
                <div class="cbt"></div>
	    </a>
	</li>
	<?php /*?><li <?php if(PAGE_NAME == 'cancelact') {?>class="active" <?php }?>>
	    <a href="<?php echo HTTP_ROOT.'users/cancelact';?>" id="sett_my_comp_prof">
                <div class="fl">Cancel Account</div>
                <div class="cbt"></div>
	    </a>
	</li><?php */?>
	<div class="cbt"></div>
    </ul>
</div>

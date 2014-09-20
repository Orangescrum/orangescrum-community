<div class="tab tab_comon">
    <ul class="nav-tabs">
	<li <?php if(PAGE_NAME == 'profile') {?>class="active" <?php }?>>
	    <a href="<?php echo HTTP_ROOT.'users/profile';?>" id="sett_my_profile">
                <div class="fl sett_my_prof"></div>
                <div class="fl">My Profile</div>
                <div class="cbt"></div>
	    </a>
	</li>
	<li <?php if(PAGE_NAME == 'changepassword') {?>class="active" <?php }?>>
	    <a href="<?php echo HTTP_ROOT.'users/changepassword';?>" id="sett_cpw_prof">
                <div class="fl sett_cpw"></div>
                <div class="fl">Change Password</div>
                <div class="cbt"></div>
	    </a>
	</li>
	<li>
	    <a href="#delegated" data-toggle="tab" id="sett_mail_noti_prof">
                <div class="fl sett_mail_noti"></div>
                <div class="fl">Notifications</div>
                <div class="cbt"></div>
	    </a>
	</li>
	<li>
	    <a href="#highpr" data-toggle="tab" id="sett_mail_repo_prof">
                <div class="fl sett_mail_repo"></div>
                <div class="fl">Email Reports</div>
                <div class="cbt"></div>
	    </a>
	</li>
	<li>
	    <a href="#bug" data-toggle="tab" id="sett_imp_exp_prof">
                <div class="fl sett_imp_exp"></div>
                <div class="fl">Import & Export</div>
                <div class="cbt"></div>
	    </a>
	</li>
	<li>
	    <a href="#bug" data-toggle="tab" id="sett_my_comp_prof">
                <div class="fl sett_my_comp"></div>
                <div class="fl">My Company</div>
                <div class="cbt"></div>
	    </a>
	</li>
	<div class="cbt"></div>
    </ul>
</div>
<input type="hidden" id="role" value="<?php echo $role;?>">
<input type="hidden" id="type" value="<?php echo $type;?>">
<input type="hidden" id="user_srch" value="<?php echo $user_srch;?>">
<div class="proj_grids">
	<?php 
	$srch_res = '';
	if(isset($_GET['user']) && trim($_GET['user']) && isset($userArr['0']['User']) && !empty($userArr['0']['User'])){
	    if($userArr['0']['User']['name']) {
		$srch_res = ucfirst($userArr['0']['User']['name'])." ".ucfirst($userArr['0']['User']['last_name']);
	    } else {
		$srch_res = $userArr['0']['User']['email'];
	    }
	}
	
	if(isset($user_srch) && trim($user_srch)) {
	    $srch_res = $user_srch;
	}
	?>
    <?php if(trim($srch_res)){ ?>
    <div class="global-srch-res fl">Search Results for: <span><?php echo $srch_res;?></span></div>
    <div class="fl global-srch-rst"><a href="<?php echo HTTP_ROOT.'users/manage';?>">Reset</a></div>
	<div class="cb"></div>
    <?php } ?>
<div class="tab tab_comon tab_task">
        <ul class="nav-tabs mod_wide">
	    <li id="task_li" <?php if($role == '' || $role == 'all'){?>class="active" <?php }?>>
               <a href="javascript:void(0);" onclick="filterUserRole('all','<?php echo $user_srch;?>');">
                <div class="usrr_actv fl"></div>
                <div class="fl">Active<span class="counter">(<?php echo $active_user_cnt;?>)</span></div>
                <div class="cbt"></div>
                </a>
            </li>
            <li id="file_li" <?php if($role == 'invited'){?>class="active" <?php }?>>
                <a href="javascript:void(0);" onclick="filterUserRole('invited','<?php echo $user_srch;?>');">
                <div class="usrr_invt fl"></div>
                <div class="fl">Invited<span class="counter">(<?php echo $invited_user_cnt;?>)</span></div>
                <div class="cbt"></div>
                </a>
            </li>
            <li id="task_li" <?php if($role == 'disable'){?>class="active" <?php }?>>
                <a href="javascript:void(0);" onclick="filterUserRole('disable','<?php echo $user_srch;?>');">
                <div class="usrr_disbl fl"></div>
                <div class="fl">Disabled<span class="counter">(<?php echo $disabled_user_cnt;?>)</span></div>
                <div class="cbt"></div>
                </a>
            </li>
            <div class="cbt"></div>
        </ul>
    </div>
    
    
<div class="col-lg-12 user_div m-left-20">
	
	<div class="col-lg-4">
		<div class="col-lg-12 contain new_prjct user_inv text-centre">
		    <a href="javascript:void(0);" onClick="newUser()" style="display: block !important;padding: 55px;">
				<div class="icon-projct-gridvw"></div>
				Invite Users
			</a>
		</div>
	</div>
	
    <?php //if(!empty($userArr) && isset($userArr)){
	$count = 1;
	$is_invited_user = 0;
	if ($role == 'invited') {
	    $is_invited_user = 1;
	}
	
	foreach($userArr as $user) { 
		if ($user['CompanyUser']['user_type'] == 1) {
		    $colors = 'user-owner';
		    $usr_typ_name = 'Owner';
		} else if ($user['CompanyUser']['user_type'] == 2) {
		    $colors = 'user-admin';
		    $usr_typ_name = 'Admin';
		} else if ($user['CompanyUser']['user_type'] == 3 && $role != 3) {
		    $colors = 'user-usr';
		    $usr_typ_name = 'User';
		}
		
		if ($role == 'invited') {
		    $colors = 'user-usr';
		    $usr_typ_name = 'User';
		}
		?>
    <div class="col-lg-4 proj_mng_div">
        <div class="col-lg-12 contain usr_mng_div" style="position:relative;">
        	<div class="usr_block">
                <div class="label <?php echo $colors;?> fl" style="padding: 3px;text-align: center !important;width: 96px;"><?php echo $usr_typ_name;?></div>
                <div class="fl mgl14">
		    <div class="nm"><?php if(isset($user['User']['name']) && trim($user['User']['name'])) {echo ucfirst($user['User']['name']); } else { echo "&nbsp;";} ?></div>
                    <div class="usr_email" title="<?php echo $user['User']['email']; ?>">
			<?php $email = $this->Format->shortLength($user['User']['email'],25);
			echo $email; ?>
		    </div>
                </div>
                <div class="cbt"></div>
             </div>
             <div class="user-details">
             	<div class="fl usr_lt">
                    <div class="user-image">
					<?php if(trim($user['User']['photo'])) { ?>
						<img class="lazy" data-original="<?php echo HTTP_ROOT; ?>users/image_thumb/?type=photos&file=<?php echo $user['User']['photo']; ?>&sizex=94&sizey=94&quality=100" width="94" height="94" />
					<?php     
					} else { ?>
						<img class="lazy" data-original="<?php echo HTTP_ROOT; ?>users/image_thumb/?type=photos&file=user.png&sizex=94&sizey=94&quality=100" width="94" height="94" />
					<?php } ?>
					</div>
                    <div class="user-nm"><?php echo $user['User']['short_name']; ?></div>
           		</div>
                <div class="fl usr_rt">
		    <div class="border_usr usr_email">Last Activity:</div>
		    <?php
		    if ($user['CompanyUser']['is_active'] == 0 && $_GET['role'] == 'invited') {
			$activity = "<span class='fnt_clr_rd'>Invited</span>";
		    } else {
			if ($user['User']['dt_last_login']) {
			    $activity = $user['User']['latest_activity'];
			} elseif ($user['CompanyUser']['is_active']) {
			    //$activity = "<span class='fnt_clr_rd'>Invited</span>";
			}
		    } ?>
		    
                    <div class="usr_email"><?php echo $activity;?></div>
                    <div class="border_usr usr_email">Created: </div>
		    <?php
		    if ($role == "invited") {
			$crdt = $user['UserInvitation']['created'];
		    } else {
			$crdt = $user['CompanyUser']['created'];
		    }
		    if ($crdt != "0000-00-00 00:00:00") { ?>
			<div class="usr_email"><?php echo $user['User']['created_on'];?></div>
		   <?php } ?>
                </div>
                <div class="cbt"></div>
              </div>
	    <div class="nm_prj" id="remain_prj_<?php echo $user['User']['id'];?>">Projects: <?php if(isset($user['User']['all_project']) && trim($user['User']['all_project'])) { ?><span class="fnt13"><?php echo $user['User']['all_project'];?></span><?php } else {?><span class="fnt13 fnt_clr_gry">N/A</span><?php }?></div>
	     <div class="proj_mng">
		<?php if ($user['CompanyUser']['user_type'] == 1) { ?>
		    <div class="fl">
			<a class="icon-assign-usr" href="javascript:void(0);" data-usr-id="<?php echo $user['User']['id'];?>" data-usr-name="<?php echo $user['User']['name'];?>">Assign Project</a>
		    </div>
		    <div class="fr">
				<input id="rmv_allprj_<?php echo $user['User']['id'];?>" type="hidden" value="<?php echo $user['User']['all_projects'];?>"/>
				<a id="rmv_prj_<?php echo $user['User']['id'];?>" class="icon-remprj-usr" href="javascript:void(0);" data-usr-id="<?php echo $user['User']['id'];?>" data-usr-name="<?php echo $user['User']['name'];?>" data-total-project="<?php echo $user['User']['total_project'];?>" <?php if($user['User']['all_project'] == ''){ ?> style="display:none;" <?php } ?>>Remove Project</a>
			</div>
		<?php } else {
		    if($role == 'invited') { ?>
			<div class="invite_user_cls" data-usr-id="<?php echo $user['User']['id'];?>" data-usr-name="<?php echo $user['User']['email'];?>" style="display: none;"></div>
			<div class="fl">
			    <a class="icon-assign-usr" href="javascript:void(0);" data-usr-id="<?php echo $user['User']['id'];?>" data-usr-name="<?php echo $user['User']['email'];?>">Assign Project</a>
			    <input id="rmv_allprj_<?php echo $user['User']['id'];?>" type="hidden" value="<?php echo $user['User']['all_projects'];?>"/>
			    <span id="rmv_prj_<?php echo $user['User']['id'];?>" <?php if($user['User']['all_project'] == ''){ ?> style="display:none;"<?php } ?>>
				    <br />
				<a class="icon-remprj-usr" href="javascript:void(0);" data-usr-id="<?php echo $user['User']['id'];?>" data-usr-name="<?php echo $user['User']['email'];?>" data-total-project="<?php echo $user['User']['total_project'];?>">Remove Project</a>
			    </span>	
			    
			</div>
			<div class="fr">
			    <a class="icon-delete-usr fl" href="<?php echo HTTP_ROOT; ?>users/manage/?del=<?php echo urlencode($user['User']['uniq_id']); ?>&role=<?php echo $_GET['role']; ?>" Onclick="return confirm('Are you sure you want to delete \'<?php echo $user['User']['email']; ?>\' ?')">Delete</a> <br/>
			    <a class="icon-resend-usr fl" href="javascript:void(0);" onclick="return resend_invitation('<?php echo $user['User']['qstr']; ?>','<?php echo $user['User']['email']; ?>');">Resend</a>
			</div>
		   <?php } elseif($role == 'disable'){ ?>
			<div class="fl">
			    <a class="icon-enable-usr fl" href="<?php echo HTTP_ROOT; ?>users/manage/?act=<?php echo urlencode($user['User']['uniq_id']); ?>&role=<?php echo $_GET['role']; ?>" Onclick="return confirm('Are you sure you want to enable \'<?php echo $user['User']['name']; ?>\' ?')">Enable</a>
			</div>
			<div class="fr">
				<input id="rmv_allprj_<?php echo $user['User']['id'];?>" type="hidden" value="<?php echo $user['User']['all_projects'];?>"/>
				<a id="rmv_prj_<?php echo $user['User']['id'];?>" class="icon-remprj-usr" href="javascript:void(0);" data-usr-id="<?php echo $user['User']['id'];?>" data-usr-name="<?php echo $user['User']['name'];?>" data-total-project="<?php echo $user['User']['total_project'];?>" <?php if($user['User']['all_project'] == ''){ ?> style="display:none;" <?php } ?>>Remove Project</a>
			</div>
		  <?php } else { ?>
			<div class="fl">
			    <a class="icon-assign-usr" href="javascript:void(0);" data-usr-id="<?php echo $user['User']['id'];?>" data-usr-name="<?php echo $user['User']['name'];?>">Assign Project</a>
			    <input id="rmv_allprj_<?php echo $user['User']['id'];?>" type="hidden" value="<?php echo $user['User']['all_projects'];?>"/>
				<span id="rmv_prj_<?php echo $user['User']['id'];?>" <?php if($user['User']['all_project'] == ''){ ?> style="display:none;"<?php } ?>>
					<br />
				    <a class="icon-remprj-usr" href="javascript:void(0);" data-usr-id="<?php echo $user['User']['id'];?>" data-usr-name="<?php echo $user['User']['name'];?>" data-total-project="<?php echo $user['User']['total_project'];?>">Remove Project</a>
				</span>
			</div>
			<div class="fr">
			    <a class="icon-disable-usr" href="<?php echo HTTP_ROOT; ?>users/manage/?deact=<?php echo urlencode($user['User']['uniq_id']); ?>" Onclick="return confirm('Are you sure you want to disable \'<?php echo $user['User']['name']; ?>\' ?')">Disable</a><br />
			    <?php  if($istype == 1) {
				if ($user['CompanyUser']['user_type'] == 2) {?>
			    <a class="icon-revadmin-usr" href="<?php echo HTTP_ROOT; ?>users/manage/?revoke_admin=<?php echo urlencode($user['User']['uniq_id']); ?>" Onclick="return confirm('Are you sure you want to revoke Admin privilege from \'<?php echo $user['User']['name']; ?>\' ?')">Revoke Admin</a>
			    <?php } else {?>
			    <a class="icon-admin-usr" href="<?php echo HTTP_ROOT; ?>users/manage/?grant_admin=<?php echo urlencode($user['User']['uniq_id']); ?>" Onclick="return confirm('Are you sure you want to grant Admin privilege to \'<?php echo $user['User']['name']; ?>\' ?')">Grant Admin</a>
			    <?php } }?>
			</div>
		    <?php }
		    } ?>
	    </div>
        </div>
    </div>
    <?php $count++;
		} ?>
    <input type="hidden" id="is_invited_user" value="<?php echo $is_invited_user;?>" />
    
   <?php //} 
   if(!isset($userArr) || empty($userArr)){ ?>
	<div class="col-lg-4">
		<div class="col-lg-12 text-centre">
		    <div style="display: block !important;margin-top: 123px;width:94%;">
			<div class="fnt_clr_rd">No users found.</div>
			</div>
		</div>
	</div>
    <?php } ?>
</div>
    
<div class="cbt"></div>
<input type="hidden" id="getcasecount" value="<?php echo $caseCount; ?>" readonly="true"/>
<?php if ($caseCount) { ?>
<div class="tot-cs fr">
    <div class="sh-tot-cs">
	<?php echo $this->Format->pagingShowRecords($caseCount, $page_limit, $casePage); ?>
    </div>
    <div class="pg-ntn">
	<ul class="pagination">
	    <?php
	    $page = $casePage;
	    if ($page_limit < $caseCount) {
		$numofpages = $caseCount / $page_limit;
		if (($caseCount % $page_limit) != 0) {
		    $numofpages = $numofpages + 1;
		}
		$lastPage = $numofpages;
		$k = 1;
		$data1 = "";
		$data2 = "";
		if ($numofpages > 5) {
		    $newmaxpage = $page + 2;
		    if ($page >= 3) {
			$k = $page - 2;
			$data1 = "...";
		    }
		    if (($numofpages - $newmaxpage) >= 2) {
			if ($data1) {
			    $data2 = "...";
			    $numofpages = $page + 2;
			} else {
			    if ($numofpages >= 5) {
				$data2 = "...";
				$numofpages = 5;
			    }
			}
		    }
		}
		if ($data1) {
		    echo "<li><a href='" . HTTP_ROOT . "users/manage/?role=" . $this->params['url']['role'] . "&type=" . $this->params['url']['type'] . "&user_srch=" . $this->params['url']['user_srch'] . "&page=1' class=\"button_act\" >&laquo; First</a></li>";
		    echo "<li class='hellip'>&hellip;</li>";
		}
		if ($page != 1) {
		    $pageprev = $page - 1;
		    echo "<li><a href='" . HTTP_ROOT . "users/manage/?role=" . $this->params['url']['role'] . "&type=" . $this->params['url']['type'] . "&user_srch=" . $this->params['url']['user_srch'] . "&page=" . $pageprev . "' class=\"button_act\">&lt;&nbsp;Prev</a></li>";
		} else {
		    echo "<li><a href='javascript:jsVoid();' class=\"button_prev\" style=\"cursor:text\">&lt;&nbsp;Prev</a></li>";
		}
		for ($i = $k; $i <= $numofpages; $i++) {
		    if ($i == $page) {
			echo "<li><a href='javascript:jsVoid();' class=\"button_page\" style=\"cursor:text\">" . $i . "</a></li>";
		    } else {
			echo "<li><a href='" . HTTP_ROOT . "users/manage/?role=" . $this->params['url']['role'] . "&type=" . $this->params['url']['type'] . "&user_srch=" . $this->params['url']['user_srch'] . "&page=" . $i . "' class=\"button_act\" >" . $i . "</a></li>";
		    }
		}
		if (($caseCount - ($page_limit * $page)) > 0) {
		    $pagenext = $page + 1;
		    echo "<li><a href='" . HTTP_ROOT . "users/manage/?role=" . $this->params['url']['role'] . "&type=" . $this->params['url']['type'] . "&user_srch=" . $this->params['url']['user_srch'] . "&page=" . $pagenext . "' class=\"button_act\" >Next&nbsp;&gt;</a></li>";
		} else {
		    echo "<li><a href='" . HTTP_ROOT . "users/manage/?role=" . $this->params['url']['role'] . "&type=" . $this->params['url']['type'] . "&user_srch=" . $this->params['url']['user_srch'] . "&page=" . $pagenext . "' class=\"button_prev\">Next&nbsp;&gt;</a></li>";
		}
		if ($data2) {
		    echo "<li class='hellip'>&hellip;</li>";
		    echo "<li><a href='" . HTTP_ROOT . "users/manage/?role=" . $this->params['url']['role'] . "&type=" . $this->params['url']['type'] . "&user_srch=" . $this->params['url']['user_srch'] . "&page=" . floor($lastPage) . "' class=\"button_act\" >Last &raquo;</a></li>";
		}
	    }
	    ?>
	    </ul>
	</div>
    </div>
<?php } ?>
<input type="hidden" id="totalcount" name="totalcount" value="<?php echo $count; ?>"/>
</div>
<div id="projectLoader">
    <div class="loadingdata">Sending invitation again...</div>
</div>
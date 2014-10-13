<div class="proj_grids">
    <?php 
	$srch_res = '';
	if(isset($_GET['project']) && trim($_GET['project']) && isset($prjAllArr[0]['Project']) && !empty($prjAllArr[0]['Project'])){
	    if($prjAllArr[0]['Project']['name']) {
		$srch_res = ucfirst($prjAllArr[0]['Project']['name']);
	    } else {
		$srch_res = $prjAllArr[0]['Project']['short_name'];
	    }
	}
	
	$active_url = HTTP_ROOT.'projects/manage';
	$inactive_url = $active_url.'/inactive';
	if(isset($_GET['proj_srch']) && trim($_GET['proj_srch'])) {
	    $srch_res = $proj_srch = $_GET['proj_srch'];
	    $active_url .="?proj_srch=".$proj_srch;
	    $inactive_url .="?proj_srch=".$proj_srch;
	}
	?>
    <?php if(trim($srch_res)){ ?>
    <div class="global-srch-res fl">Search Results for: <span><?php echo $srch_res;?></span></div>
    <div class="fl global-srch-rst"><a href="<?php echo HTTP_ROOT.'projects/manage';?>">Reset</a></div>
	<div class="cb"></div>
    <?php } ?>

<!--Tabs section starts -->
    <div class="tab tab_comon">
        <ul class="nav-tabs mod_wide">
            <li <?php if($projtype == '') { ?> class="active" <?php }?>>
                <a href="<?php echo $active_url; ?>">
				<div class="pro_actv fl"></div>
                <div class="fl">Active<span id="active_proj_cnt" class="counter">(<?php echo $active_project_cnt;?>)</span></div>
                <div class="cbt"></div>
                </a>
            </li>
            <li <?php if($projtype == 'inactive') { ?> class="active" <?php }?>>
                <a href="<?php echo $inactive_url; ?>" >
				<div class="pro_inactv fl"></div>
                <div class="fl">Inactive<span id="inactive_proj_cnt" class="counter">(<?php echo $inactive_project_cnt;?>)</span></div>
                <div class="cbt"></div>
                </a>
            </li>
            <div class="cbt"></div>
        </ul>
    </div>
<!--Tabs section ends -->
<?php $count=0; $clas = "";
	$space = 0;
	$spacepercent=0;
	$totCase = 0;
	$totHours = '0.0';
?>
<div class="cb"></div>
<div class="col-lg-12 m-left-42 prj_div">
	<?php //if($projtype == '') { ?>
	    <div class="col-lg-4">
		<div class="col-lg-12 contain new_prjct text-centre">
		    <a href="javascript:void(0);" onClick="newProject()" style="display: block !important;padding: 50px 55px;">
		    <div class="icon-projct-gridvw"></div>
		    Create New Project
		    </a>
		</div>
	    </div>
	<?php //}?>
	<?php //if($projtype == '') { ?>
	<?php if(!empty($prjAllArr[0]) && isset($prjAllArr[0])){} else { ?>
    <div class="col-lg-4">
		<div class="col-lg-12 text-centre">
		    <div style="display: block !important;margin-top: 123px;width:103%;">
			<div class="fnt_clr_rd"><?php if (@SES_TYPE ==3){ ?>You have not created any project yet.<?php } else { ?>No projects found.<?php } ?></div>
			</div>
		</div>
	</div>
    <?php } ?>

	<?php //}
	if(count($prjAllArr)){
	foreach($prjAllArr as $k=>$prjArr){ 
	$totUser = !empty($prjArr[0]['totusers']) ? $prjArr[0]['totusers']: '0';
	$totCase = (!empty($prjArr[0]['totalcase'])) ? $prjArr[0]['totalcase']: '0';	
	$totHours = (!empty($prjArr[0]['totalhours'])) ? $prjArr[0]['totalhours']: '0.0';
	if($k==2){ // && $projtype == '' ?>
	<div class="cb"></div>
	<?php } ?>
	<div class="col-lg-4 proj_mng_div">
		<div class="col-lg-12 contain usr_mng_div <?php if($projtype == 'inactive') { ?>inactv <?php } ?>">
		    <?php $prj_name = ucwords(trim($prjArr['Project']['name']));
			$len = 23;
			$prj_name_shrt = $this->Format->shortLength($prj_name,$len);
			$value_format = $this->Format->formatText($prj_name);
			$value_raw = html_entity_decode($value_format, ENT_QUOTES);
			$tooltip = '';
			if(strlen($value_raw) > $len){
			    $tooltip = $prj_name;
			}
		    ?>
		     <h3 class="prj_name"><a href="<?php echo HTTP_ROOT."dashboard/?project=".$prjArr['Project']['uniq_id'];?>" title="<?php echo $tooltip;?>"><?php echo $prj_name_shrt;?>&nbsp;</a></h3>
			<div class="user-details prj_details">
			    <div class="fl usr_lt">
				<div class="user-image">
				    <a href="<?php echo HTTP_ROOT."dashboard/?project=".$prjArr['Project']['uniq_id'];?>"><img src="<?php echo HTTP_ROOT; ?>img/prj_icon.png" /></a>
				</div>
				<div class="user-nm"><?php echo strtoupper($prjArr['Project']['short_name']); ?></div>
			    </div>
			    <div class="fl usr_rt">
				<div class="border_usr"><b><span id="tot_prjusers<?php echo $prjArr['Project']['id']; ?>"><?php echo!empty($prjArr[0]['totusers']) ? $prjArr[0]['totusers'] : '0'; ?></span></b> User(s)</div>
				<div><b><?php echo $prjArr[0]['totalcase']; ?></b> Task(s)</div>
				<div><b><?php echo (!empty($prjArr[0]['totalhours']) && ($prjArr[0]['totalhours'] != '0.0' || $prjArr[0]['totalhours'] != '0')) ? $prjArr[0]['totalhours'] : '0'; ?></b> Hour(s) Spent</div>  
				<div>
				    <b>
					<?php
					$filesize = 0;
					if ($totCase && isset($prjArr[0]['storage_used']) && $prjArr[0]['storage_used']) {
					    $filesize = number_format(($prjArr[0]['storage_used'] / 1024), 2);
					    if($filesize != '0.0' || $filesize != '0') {
						$filesize = $filesize;
					    }
					    $space = $space + $filesize;
					}
					echo $filesize;
					?>
				    </b> Mb Storage
				</div>
			    </div>
			    <div class="cb"></div>
			</div>
			<div class="last_updt prj_last_updt">
			<?php $getactivity=$this->Casequery->getlatestactivitypid($prjArr['Project']['id'],1);
			if($getactivity==""){
				echo 'No activity';
			}else{
				$curCreated = $this->Tmzone->GetDateTime(SES_TIMEZONE,TZ_GMT,TZ_DST,TZ_CODE,GMT_DATETIME,"datetime");
				$updated = $this->Tmzone->GetDateTime(SES_TIMEZONE,TZ_GMT,TZ_DST,TZ_CODE,$getactivity,"datetime");
				$locDT = $this->Datetime->dateFormatOutputdateTime_day($updated, $curCreated);
				echo 'Last Activity: '.$locDT;
			}
			?>
			</div>
			<?php if($projtype == '') { ?>
			    <div class="proj_mng">
				<div class="fl">
				    <a href="javascript:void(0);" class="icon-add-usr fl" data-prj-id="<?php echo $prjArr['Project']['id'];?>" data-prj-name="<?php echo $prj_name;?>">Add User</a><br />
					<span id="remove<?php echo $prjArr['Project']['id'];?>">
						<?php if(!empty($prjArr[0]['totusers'])){ ?>
							<a href="javascript:void(0);" class="icon-remove-usr" data-prj-id="<?php echo $prjArr['Project']['id'];?>" data-prj-name="<?php echo $prj_name;?>">Remove User</a>
						<?php } ?>
					</span>
					<span id="ajax_remove<?php echo $prjArr['Project']['id'];?>" style="display:none;">
						<a href="javascript:void(0);" class="icon-remove-usr" data-prj-id="<?php echo $prjArr['Project']['id'];?>" data-prj-name="<?php echo $prj_name;?>">Remove User</a>
					</span>
				</div>
				<div class="fr">
				    <a href="javascript:void(0);" class="icon-edit-usr fl" data-prj-id="<?php echo $prjArr['Project']['uniq_id'];?>" data-prj-name="<?php echo $prj_name;?>">Edit</a><br />
				    
				    <?php if($prjArr[0]['totalcase']) { ?>
				<a href="javascript:void(0);" class="icon-disable-usr fl disbl_prj" data-prj-id="<?php echo $prjArr['Project']['id'];?>" data-prj-name="<?php echo $prj_name;?>">Disable</a>
				<?php } else { ?>
				    <a href="javascript:void(0);" class="icon-del-prj del_prj" data-prj-id="<?php echo $prjArr['Project']['uniq_id'];?>" data-prj-name="<?php echo $prj_name;?>">Delete</a>
				<?php } ?>
				</div>
			    </div>
			<?php } else { ?>
				<div class="proj_mng">
				    <div class="fl"><a href="javascript:void(0);" class="icon-enable-prj enbl_prj" data-prj-id="<?php echo $prjArr['Project']['id'];?>" data-prj-name="<?php echo $prj_name;?>">Enable</a></div>
				    <div class="fr"><a href="javascript:void(0);" class="icon-del-prj del_prj" data-prj-id="<?php echo $prjArr['Project']['uniq_id'];?>" data-prj-name="<?php echo $prj_name;?>">Delete</a></div>
				    <div class="cb"></div>
				</div>
			<?php }  ?>
		    </div>
	</div>
	<?php } ?>
	<?php } ?>
</div>
<div class="cb"></div>
<?php if($caseCount){?>
<table cellpadding="0" cellspacing="0" border="0" align="right">
	<tr>
		<td align="center" style="padding-top:5px;padding-right:35px;">
			<div class="show_total_case" style="font-weight:normal;color:#000;font-size:12px;">
				<?php echo  $this->Format->pagingShowRecords($caseCount,$page_limit,$casePage); ?>
			</div>
		</td>
	</tr>
	<tr>
		<td align="center">
		<ul class="pagination" style="padding-right:35px;">
		<?php $page = $casePage;
			if($page_limit < $caseCount){
				$numofpages = $caseCount / $page_limit;
				if(($caseCount % $page_limit) != 0){
					$numofpages = $numofpages+1;
				}
				$lastPage = $numofpages;
				$k = 1;
				$data1 = "";
				$data2 = "";
				if($numofpages > 5){
					$newmaxpage = $page+2;
					if($page >= 3){
						$k = $page-2;
						$data1 = "...";
					}
					if(($numofpages - $newmaxpage) >= 2){
						if($data1){
							$data2 = "...";
							$numofpages = $page+2;
						}else{
							if($numofpages >= 5){
								$data2 = "...";
								$numofpages = 5;
							}
						}
					}
				}
				if($data1){
                 if($projtype == 'inactive'){
                     echo "<li><a href='".HTTP_ROOT."projects/manage/inactive?page=1' class=\"button_act\">&laquo; First</a></li>";
		}else{
                     echo "<li><a href='".HTTP_ROOT."projects/manage?page=1' class=\"button_act\">&laquo; First</a></li>";
                }
					echo "<li class='hellip'>&hellip;</li>";
		    }
				if($page != 1){
					$pageprev = $page-1;
                if($projtype == 'inactive'){
                     echo "<li><a href='".HTTP_ROOT."projects/manage/inactive?page=".$pageprev."' class=\"button_act\">&lt;&nbsp;Prev</a></li>";
                }else{
                     echo "<li><a href='".HTTP_ROOT."projects/manage?page=".$pageprev."' class=\"button_act\">&lt;&nbsp;Prev</a></li>";
                }
				}else{
					echo "<li><a href='javascript:jsVoid();' class=\"button_prev\" style=\"cursor:text\">&lt;&nbsp;Prev</a></li>";
				}
				for($i = $k; $i <= $numofpages; $i++){
					if($i == $page) {
						echo "<li><a href='javascript:jsVoid();' class=\"button_page\" style=\"cursor:text\">".$i."</a></li>";
					}else {
                     if($projtype == 'inactive'){
                          echo "<li><a href='".HTTP_ROOT."projects/manage/inactive?page=".$i."' class=\"button_act\" >".$i."</a></li>";
                     }else{
                          echo "<li><a href='".HTTP_ROOT."projects/manage?page=".$i."' class=\"button_act\" >".$i."</a></li>";
                     }
					}
				}
				if(($caseCount - ($page_limit * $page)) > 0){
					$pagenext = $page+1;
                if($projtype == 'inactive'){
                     echo "<li><a href='".HTTP_ROOT."projects/manage/inactive?page=".$pagenext."' class=\"button_act\" >Next&nbsp;&gt;</a></li>";
                }else{
                     echo "<li><a href='".HTTP_ROOT."projects/manage?page=".$pagenext."' class=\"button_act\" >Next&nbsp;&gt;</a></li>";
                }                                             
				}else{
                if($projtype == 'inactive'){
                     echo "<li><a href='".HTTP_ROOT."projects/manage/inactive?page=".$pagenext."' class=\"button_prev\">Next&nbsp;&gt;</a></li>";
                }else{
                     echo "<li><a href='".HTTP_ROOT."projects/manage?page=".$pagenext."' class=\"button_prev\">Next&nbsp;&gt;</a></li>";
                }
				}
				if($data2){
					echo "<li class='hellip'>&hellip;</li>";
                if($projtype == 'inactive'){
                     echo "<li><a href='".HTTP_ROOT."projects/manage/inactive?page=".floor($lastPage)."' class=\"button_act\" >Last &raquo;</a></li>";
                }else{
                     echo "<li><a href='".HTTP_ROOT."projects/manage?page=".floor($lastPage)."' class=\"button_act\" >Last &raquo;</a></li>";
                }
				}
			} ?>
		</ul>
	</td>
</tr>
</table>
<?php }	?>
</div>
<div class="cb"></div>
<input type="hidden" id="getcasecount" value="<?php echo $caseCount; ?>" readonly="true"/>
<input type="hidden" id="totalcount" name="totalcount" value="<?php echo $count; ?>"/>


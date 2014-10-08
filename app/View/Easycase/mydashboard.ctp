<style>
 .slide_rht_con{padding-left:12px;}
</style>
<ul class="sortable" id="ul_mydashboard">
    <?php 
    foreach ($dashboard_order as $key => $value) {
	?>
	<?php if($value['name']=="To Dos") { ?>
    <li class="sortable-li" id="list_<?php echo $value['id'];?>">
	<div class="sort_li_inner">
	    <div class="dshbd-hed">
		<div class="fl"><?php echo $value['name'];?></div>
		<div class="fr active_icn portlet-header">
		    <img width="16px" src="<?php echo HTTP_IMAGES; ?>images/active_dboard.png" rel="tooltip" title="Move"/>
		</div>
		<div class="fr active_icn">
			<a href="javascript:void(0);" onclick="creatask()" class="" style="color:#5191BD; font-size:13px;" rel="tooltip" title="Create Task">
				<i id="ctask_icons" class="icon-create-tsk"></i>
			</a>
		</div>
		<div class="cb"></div>
	    </div>
	    <div class="htdb custom_scroll">
			<div class="loader_dv_db" id="to_dos_ldr" style="display: none;margin-top: 90px;"><center><img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="Loading..." title="Loading..." /></center></div>
			<div class="dboard_cont" id="to_dos"></div>
		</div>
	    <div class="fr moredb" id="more_to_dos"><a href="javascript:void(0);" onclick="showTasks('my');">View All <span id="todos_cnt" style="display:none;">(0)</span></a></div>
	</div>
    </li>
    <?php } elseif($value['name']=="Recent Projects") { ?>
    <li class="sortable-li" id="list_<?php echo $value['id'];?>" style="<?php if(PROJ_UNIQ_ID!='all'){ ?>display:none<?php } ?>">
	<div class="sort_li_inner">
	    <div class="dshbd-hed">
		<div class="fl"><?php echo $value['name'];?></div>
		<div class="fr active_icn portlet-header">
		    <img width="16px" src="<?php echo HTTP_IMAGES; ?>images/active_dboard.png" rel="tooltip" title="Move"/>
		</div>
		<div class="fr active_icn">
			<a href="javascript:void(0);" onclick="newProject()" class="" style="color:#5191BD; font-size:13px;" rel="tooltip" title="Create Project">
				<i class="icon-create-proj"></i>
			</a>
		</div>
		<div class="cb"></div>
	    </div>
	    <div class="htdb custom_scroll">
			<div class="loader_dv_db" id="recent_projects_ldr" style="display: none;margin-top: 90px;"><center><img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="Loading..." title="Loading..." /></center></div>
			<div class="dboard_cont" id="recent_projects"></div>
		</div>
	    <div class="fr moredb" id="more_recent_projects"><a href="javascript:void(0);" onclick="showTasks('all');">View All <span id="todos_cnt" style="display:none;">(0)</span></a></div>
	</div>
    </li>
    <?php } elseif($value['name']=="Recent Activities") { ?>
    <li class="sortable-li" id="list_<?php echo $value['id'];?>">
        <div class="sort_li_inner">
            <div class="dshbd-hed">
                <div class="fl"><?php echo $value['name'];?></div>
                <div class="fr active_icn portlet-header">
                    <img width="16px" src="<?php echo HTTP_IMAGES; ?>images/active_dboard.png" rel="tooltip" title="Move"/>
                </div>
				<div class="fr active_icn">
					<a href="javascript:void(0);" onclick="newUser()" class="" style="color:#5191BD; font-size:13px;" rel="tooltip" title="Invite User">
						<i class="icon-create-proj"></i>
					</a>
				</div>
                <div class="cb"></div>
            </div>
	    	<div class="htdb custom_scroll">
				<div class="loader_dv_db" id="recent_activities_ldr" style="display: none;margin-top: 90px;"><center><img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="Loading..." title="Loading..." /></center></div>
				<div class="dboard_cont" id="recent_activities"></div>
			</div>
		<div class="fr moredb" id="more_recent_activities"><a href="javascript:void(0);" onclick="showTasks('activities');">View All <span id="todos_cnt" style="display:none;">(0)</span></a></div>
        </div>
    </li>
    <?php } elseif($value['name']=="Recent Milestones") { ?>
    <li class="sortable-li " id="list_<?php echo $value['id'];?>">
        <div class="sort_li_inner">
            <div class="dshbd-hed">
                <div class="fl"><?php echo $value['name'];?></div>
                <div class="fr active_icn portlet-header">
                    <img width="16px" src="<?php echo HTTP_IMAGES; ?>images/active_dboard.png" rel="tooltip" title="Move"/>
                </div>
                <div class="cb"></div>
            </div>
	    	<div class="htdb custom_scroll">
				<div class="loader_dv_db" id="recent_milestones_ldr" style="display: none;margin-top: 90px;"><center><img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="Loading..." title="Loading..." /></center></div>
				<div class="dboard_cont" id="recent_milestones"></div>
			</div>
            
        </div>
    </li>
    <?php } elseif($value['name']=="Statistics") { ?>
    <li class="sortable-li" id="list_<?php echo $value['id'];?>">
        <div class="sort_li_inner">
	<div class="dshbd-hed">
                <div class="fl">Summary</div>
                <div class="fr active_icn portlet-header">
                    <img width="16px" src="<?php echo HTTP_IMAGES; ?>images/active_dboard.png" rel="tooltip" title="Move"/>
                </div>
                <div class="cb"></div>
            </div> 
            <div id="statistics" class="dboard_cont"></div>
            <div class="loader_dv_db" id="statistics_ldr" style="display: none;margin-top: 90px;"><center><img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="Loading..." title="Loading..." /></center></div>
        </div>
    </li>
    <?php } elseif($value['name']=="Usage Details") { ?>    
    <li class="sortable-li" id="list_<?php echo $value['id'];?>">
        <div class="sort_li_inner">
            <div class="dshbd-hed">
                <div class="fl"><?php echo $value['name'];?></div>
                <div class="fr active_icn portlet-header">
                    <img width="16px" src="<?php echo HTTP_IMAGES; ?>images/active_dboard.png" rel="tooltip" title="Move"/>
                </div>
                <div class="cb"></div>
            </div> 
            <div id="usage_details" class="dboard_cont"></div>
            <div class="loader_dv_db" id="usage_details_ldr" style="display: none;margin-top: 90px;"><center><img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="Loading..." title="Loading..." /></center></div>
        </div>
    </li>
    <?php } elseif($value['name']=="Task Progress") { ?>    
    <li class="sortable-li" id="list_<?php echo $value['id'];?>">
        <div class="sort_li_inner">
            <div class="dshbd-hed">
                <div class="fl"><?php echo $value['name'];?></div>
				<div class="fl pichart_msg" id="task_progress_msg"></div>
                <div class="fr active_icn portlet-header">
                    <img width="16px" src="<?php echo HTTP_IMAGES; ?>images/active_dboard.png" rel="tooltip" title="Move"/>
                </div>
                <div class="cb"></div>
            </div> 
			<div id="task_progress" class="dboard_cont"></div>
            <div class="loader_dv_db" id="task_progress_ldr" style="display: none;margin-top: 90px;"><center><img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="Loading..." title="Loading..." /></center></div>
        </div>
    </li>
    <?php } elseif($value['name']=="Task Type") { ?>    
    <li class="sortable-li" id="list_<?php echo $value['id']; ?>">
	<div class="sort_li_inner">
	    <div class="dshbd-hed">
		<div class="fl">
		    <?php //echo $value['name']; ?>
		    <select id="sel_task_type" style="color: #5191BD;background: #FFF;width: 140px;border: 1px solid #999;" onchange="showTaskStatus(this, '<?php echo PROJ_UNIQ_ID;?>');">
		    <?php foreach ($task_type as $key => $value) { ?>
			<option value="<?php echo $value['Type']['id']; ?>" <?php if (isset($_COOKIE['TASK_TYPE_IN_DASHBOARD']) && $_COOKIE['TASK_TYPE_IN_DASHBOARD']==$value['Type']['id']){echo "selected='selected'";}?>><?php echo $value['Type']['name']; ?></option>
		    <?php }?>
		    </select>
		</div>
		<div class="fl pichart_msg" id="task_type_msg" style="font-size: 15px;"></div>
		<div class="fr active_icn portlet-header">
		    <img width="16px" src="<?php echo HTTP_IMAGES; ?>images/active_dboard.png" rel="tooltip" title="Move"/>
		</div>
		<div class="cb"></div>
	    </div> 
	    <div id="task_type" class="dboard_cont"></div>
	    <div class="loader_dv_db" id="task_type_ldr" style="display: none;margin-top: 90px;"><center><img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="Loading..." title="Loading..." /></center></div>
	</div>
    </li>
    <?php } elseif($value['name']=="Task Status") { ?>    
    <li class="sortable-li" id="list_<?php echo $value['id'];?>">
        <div class="sort_li_inner">
            <div class="dshbd-hed">
                <div class="fl"><?php echo $value['name'];?></div>
				<div class="fl pichart_msg" id="task_status_msg"></div>
                <div class="fr active_icn portlet-header">
                    <img width="16px" src="<?php echo HTTP_IMAGES; ?>images/active_dboard.png" rel="tooltip" title="Move"/>
                </div>
                <div class="cb"></div>
            </div>
			<div id="task_status" class="dboard_cont"></div>
            <div class="loader_dv_db" id="task_status_ldr" style="display: none;margin-top: 90px;"><center><img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="Loading..." title="Loading..." /></center></div>
        </div>
    </li>
    <?php }
    }
    ?>
</ul>
<div class="cb"></div>

<script type="text/javascript">
    var DASHBOARD_ORDER = <?php echo json_encode($GLOBALS['DASHBOARD_ORDER']); ?>;
    $(document).ready(function() {
	loadDashboardPage('<?php echo PROJ_UNIQ_ID;?>');
    });
</script>
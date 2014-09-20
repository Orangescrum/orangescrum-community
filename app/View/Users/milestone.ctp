<div class="user_profile_con">
<!--Tabs section starts -->
    <div class="tab tab_comon">
        <ul class="nav-tabs mod_wide">
            <li class="active">
                <a href="#all" data-toggle="tab" id="sett_my_profile">
                <div class="fl act_milestone"></div>
                <div class="fl">Active</div>
                <div class="cbt"></div>
                </a>
            </li>
            <li class="">
                <a href="#mytask" data-toggle="tab" id="sett_cpw_prof">
                <div class="fl mt_completed"></div>
                <div class="fl">Completed</div>
                <div class="cbt"></div>
                </a>
            </li>
            <div class="cbt"></div>
        </ul>
    </div>
<!--Tabs section ends -->
	<div class="col-lg-12 m-left-20">
		<a href="#">
		<div class="col-lg-4">
			<div class="col-lg-12 contain crt_mileston">
			<div class="icon-crt-mileston"></div>
			Create Milestone
			</div>
		</div>
		</a>
		<div class="col-lg-4 proj_mng_div">
			<div class="col-lg-12 contain">
				<h3>Integrate Clickdesk withorangescrum.com</h3>
				<div class="tsk_updts">139 Tasks&nbsp; . &nbsp;20 Closed</div>
				<div class="imprv_bar col-lg-12">
					<div class="cmpl_green col-lg-4"></div>
				</div>
				<div class="last_updt">Last activity on 19th Nov 2013</div>
				<div class="cb"></div>
				<div class="proj_mng">
					<a href="javascript:void(0);" class="icon-add-usr fl" data-prj-id="<?php echo $prjArr['Project']['id'];?>" data-prj-name="<?php echo $prj_name;?>">Add User</a>
					<a href="javascript:void(0);" class="icon-edit-usr fl">Edit</a>
					<a href="javacript:void(0);" class="icon-delete-usr fl" >Delete</a>
				</div>
			</div>
		</div>
		<div class="col-lg-4">
			<div class="col-lg-12 contain">
				<h3>Integrate Clickdesk withorangescrum.com</h3>
				<div class="tsk_updts">139 Tasks&nbsp; . &nbsp;20 Closed</div>
				<div class="imprv_bar col-lg-12">
					<div class="cmpl_green col-lg-1"></div>
				</div>		
				<div class="last_updt">Last activity on 19th Nov 2013</div>
			</div>
		</div>
	</div>
	<div class="cb"></div>
	<div class="col-lg-12 m-left-20">
		<div class="col-lg-4">
			<div class="col-lg-12 contain">
				<h3>Integrate Clickdesk withorangescrum.com</h3>
				<div class="tsk_updts">139 Tasks&nbsp;  .&nbsp;  20 Closed</div>	
				<div class="imprv_bar col-lg-12">
					<div class="cmpl_green col-lg-7"></div>
				</div>	
				<div class="last_updt">Last activity on 19th Nov 2013</div>
			</div>
		</div>
		<div class="col-lg-4">
			<div class="col-lg-12 contain">
				<h3>Integrate Clickdesk withorangescrum.com</h3>
				<div class="tsk_updts">139 Tasks&nbsp; .&nbsp; 20 Closed</div>
				<div class="imprv_bar col-lg-12">
					<div class="cmpl_green col-lg-12"></div>
				</div>	
				<div class="last_updt">Last activity on 19th Nov 2013</div>
			</div>	
		</div>
		<div class="col-lg-4">
			<div class="col-lg-12 contain">
				<h3>Integrate Clickdesk withorangescrum.com</h3>
				<div class="tsk_updts">139 Tasks&nbsp;  .&nbsp; 20 Closed</div>
				<div class="imprv_bar col-lg-12">
					<div class="cmpl_green col-lg-3"></div>
				</div>	
				<div class="last_updt">Last activity on 19th Nov 2013</div>
			</div>
		</div>
	</div>
</div>
<div class="cb"></div>
<script type="text/javascript">
$(".proj_mng_div .contain").hover(function(){
			$(this).find(".proj_mng").stop(true,true).animate({bottom:"0px",opacity:1},400);
		},function(){
			$(this).find(".proj_mng").stop(true,true).animate({bottom:"-42px",opacity:0},400);
		});

</script>
<div class="proj_grids">
	<div class="tab tab_comon">
        <ul class="nav-tabs mod_wide">
	    <li id="task_li" class="active">
               <a href="javascript:void(0);">
                <div class="an_bug fl"></div>
                <div class="fl">Bug Reports</div>
                <div class="cbt"></div>
                </a>
            </li>
            <li id="file_li">
                <a href="javascript:void(0);">
                <div class="an_hrs fl"></div>
                <div class="fl">Hour Spent</div>
                <div class="cbt"></div>
                </a>
            </li>
            <li id="task_li">
                <a href="javascript:void(0);" onclick="filterUserRole('disable');">
                <div class="an_tsk fl"></div>
                <div class="fl">Task Reports</div>
                <div class="cbt"></div>
                </a>
            </li>
            <li id="task_li">
                <a href="javascript:void(0);" onclick="filterUserRole('disable');">
                <div class="an_week fl"></div>
                <div class="fl">Weekly Usage</div>
                <div class="cbt"></div>
                </a>
            </li>			
            <div class="cbt"></div>
        </ul>
    </div>
	<div class="fr filter_dt">
		<div class="task_due_dt">
			<div class="fl icon-due-date"></div>
			<div class="fl">
			<input type="text" class="smal_txt" placeholder="From Date"/> <span>-</span>
			 <input type="text" class="smal_txt" placeholder="To Date"/>
			 <button class="btn btn_blue aply_btn" type="submit">Apply</button>
			</div>
		</div>	
	</div>
    <div class="cb"></div>
	<div class="col-lg-12 full_con_al">
		<div class="col-lg-6 m-con fl">
			<h3>Bug Status Pie Chart</h3>
			<div><img src="../img/bug_status.jpg"></div>
		</div>
		<div class="col-lg-6 m-con fl">
			<h3>Bug Statistics</h3>
			<div class="fl"> Total 146 Bugs created.</div>
			<div class="cb"></div>
			<div>
				<ul>
					<li>Avg. days to Resolve a Bug: 13</li>
					<li> Avg. days to Close a Bug: 58</li>
					<li>Hours spent on these Bugs: 0.5</li>
				</ul>
			</div>
		</div>
		<div class="cb"></div>
		<div class="col-lg-12 con-100">
			<h3>Bugs Life Cycle - Line Chart</h3>
			<div><img src="../img/bug_cycle.jpg"></div>
		</div>
		<div class="cb"></div>
		<div class="col-lg-12 con-100">
			<h3>Bug Glide Path Chart</h3>
			<div><img src="../img/bug_glide.jpg"></div>			
		</div>
	</div>
</div>
<div class="cb"></div>
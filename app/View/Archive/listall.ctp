<table width="96%" cellspacing="0" cellpadding="0" class="task_section dashbod_tbl_m10" style="display: table;">
	<tr><td>
	<div id="caseLoader" style="position:fixed;left:55%;top:25%;display:none;">
		<div class="loadingdata">Loading...</div>
	</div>
	<div class="arc_grids">
	<div class="tab tab_comon tab_task">
			<ul class="nav-tabs mod_wide">
				<li id="task_li" class="active">
					<a href="javascript:void(0);" onclick="task()">
					<div class="tb_tsk fl"></div>
					<div class="fl">Tasks</div>
					<div class="cbt"></div>
					</a>
				</li>
				<li id="file_li">
					<a href="javascript:void(0);" onclick="file()">
					<div class="ar_file fl"></div>
					<div class="fl">Files</div>
					<div class="cbt"></div>
					</a>
				</li>
				<div class="cbt"></div>
			</ul>
		</div>
	</div>
	<div id="caselistDiv" style="display:none;">
		<table width="98%" class="tsk_tbl arc_tabs caselistall" id="caselist">
			<tr style="" class="tab_tr">
				<td width="1%" class="all_td">
					<div class="dropdown fl">
						<input id="allcase" type="checkbox" style="cursor: pointer;" class="fl chkAllTsk">
						<div class="all_chk"></div>
						<ul id="dropdown_menu_chk" class="dropdown-menu">
							<li><a onclick="restoreall()" href="javascript:void(0);"><div class="act_icon act_restore_task fl" title="Restore"></div>Restore</a></li>
							<li><a onclick="removeall()" href="javascript:void(0);"><div class="act_icon act_del_task fl" title="Remove"></div>Remove</a></li>
						</ul>
					</div>
				</td>
				<td width="1%">
					<div class="fr">Task</div>
				</td>
				<td width="1%">
					<div class="fl"></div>
				</td>
				<td width="33%">
					<div class="fl">Title</div>
				</td>        
				<td width="3%">
					<div class="fl">Status</div>
				</td>        
				<td width="2%">
					<div class="fl">Project</div>
				</td>
			</tr>
		</table>	
	</div>
	<div id="filelistDiv" style="display:none">
		<table width="98%" class="tsk_tbl arc_tbl arc_tabs filelistall" id="filelist">
			<tr class="tab_tr">
				<td width="1%" class="all_td">
					<div class="dropdown fl">
						<input id="allfile" type="checkbox" style="cursor: pointer;" class="fl chkAllTsk">
						<div class="all_chk"></div>
						<ul id="dropdown_menu_chk" class="dropdown-menu">
							<li><a onclick="restorefile()" href="javascript:void(0);"><div class="act_icon act_restore_task fl" title="Restore"></div>Restore</a></li>
							<li><a onclick="removefile()" href="javascript:void(0);"><div class="act_icon act_del_task fl" title="Remove"></div>Remove</a></li>
						</ul>
					</div>
					
				</td>
				<td width="1%">
				   <div class="fr">Task</div>
				</td>
				<td width="1%">
				   <div class="fl"></div>
				</td>
				<td width="37%" class="flnm_td">
				   <div class="fl">File Name</div>
				</td>       
				<td class="fl_sz">
				   <div class="fr">Size</div>
				</td>
				<td class="fl_n">
				   <div class="fl"></div>
				</td>
				<td width="6%">
				   <div class="fl">Project</div>
				</td>        
			</tr>
		</table>	
	</div>
	</td></tr>
</table>
<script type="text/javascript">
function file(){
	document.location.hash = 'filelist';
}
function task(){
	document.location.hash = 'caselist';
}
</script>
<div class="col-lg-12 fl m-left-20">
<div id="activities"></div>
<div style="display:none;" id="more_loader_arc_case" class="morebar_arc_case">
	<img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="loading..." title="loading..."/> 
</div>
</div>
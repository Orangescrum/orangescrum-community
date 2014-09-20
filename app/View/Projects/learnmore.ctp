<style type="text/css">
body{font-family:'PT Sans',Arial,sans-serif;color:#666}
.content{min-height:391px;height:auto;margin:0px auto 90px;padding:5px 10px}
h4.chk_head{margin:12px;font-size: 14px;}
.chk_lst_bfr{margin:-4px 15px 12px;font-size:11px;font-weight:normal}
.chk_lst_bfr a{text-decoration:none;cursor:pointer;color:#066D99;}
.chk_lst_bfr a:hover{text-decoration:underline}
.fl{float:left}
.fr{float:right}
.cb{clear:both}
ul.chk_desc{font-size: 13px;line-height: 27px;margin-left:4px;margin-top: -7px;}
ul.chk_desc li a{text-decoration:none;cursor:pointer}
ul.chk_desc li a:hover{text-decoration:underline}
ul.chk_desc.ln20{line-height:20px;margin-left:25px;}
ul.chk_desc.ln20 li b{color:#003165}
</style>
<body>
<div class="content">
    <div>
    	<h4 class="chk_head">Checklist Before Import</h4>
		<div class="chk_lst_bfr">For Milestone and Task lists, the following are imported:</div>
        <ul class="chk_desc ln20">
            <!--<li><b>Milestone Title - </b> Name of the Milestone</li>
            <li><b>Milestone Description - </b> Description for milestone. </li>
			<li><b>Start Date - </b> Milestone start date. </li>
			<li><b>End Date - </b> Milestone end date. </li>-->
			<li><b>Title - </b> Task Title </li>
			<li><b>Description - </b> Description for the task</li>
			<li><b>Due Date - </b> Due date of Task </li>
			<li><b>Status - </b> Current status of the Task</li>
			<li><b>Type - </b> Task type(Development, Bug,Enhancement etc.). </li>
			<li><b>Assign to  - </b> Valid email of the assign  </li>
        </ul>
		<!--<div class="chk_lst_bfr">Before importing your milestone list, you can <a href="<?php echo HTTP_ROOT;?>projects/download_sample_csvfile">download a sample  file</a> to use as a template</div>-->
		<div class="chk_lst_bfr">Before importing your milestone list, you can <a href="<?php echo HTTP_ROOT;?>projects/download_sample_csvfile">download a sample  file</a> to use as a template</div>
    </div>
    <div class="cb"></div>
    <div>
    	<h4 class="chk_head">Tips</h4>
        <ul class="chk_desc ln20">
            <li>All text columns has to be enclosed with double quotes</li>
            <li>Task title must be a mandatory field</li>
			<li>Date must be a valid date with format mm/dd/yyyy,Otherwise treated as current date</li>
			<li>In case input milestone title has the same name as the existing milestone under a project then task will append to that milestone </li>
			<li>Status must be anyone out of (<font style="color:#003165">New,In Progress,resolve,Close</font>) ,Otherwise default treated as <font style="color:#003165">New</font></li>
			<li>Type must be anyone out of (<font style="color:#003165">Bug,Development,Enhancement,rnd,qa,'Unit Testing',Maintenance,Others,Release,Update</font>) ,Otherwise default treated as <font style="color:#003165">Development</font></li>
        </ul>
    </div>	
</div>
</body>
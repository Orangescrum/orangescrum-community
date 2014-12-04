<style>
    .main_overlay{position:fixed;top:70px;bottom:0; left:0;right:0; background:#000;opacity: 0.8; z-index:1040;}
    .oly_bdr_crtask{background:url(img/os_circle.png) no-repeat;height:53px;left:17px;position:absolute;top:37px;width:143px;}
    .oly_lcnt_mdiv{position:absolute; left:175px; top:100px;}
    .oly_container{height:31px; border-left:1px solid #fff;}
    .oly_cont_fbdr{height:1px; background:#fff; width:230px; margin-top:15px;}
    .oly_cont_txt{color:#fff; margin-left:8px; margin-top:4px;}
    .oly_cont_mt1{margin-top:6px;}
    .oly_cont_mt2{margin-top:13px;}
    .oly_cont_mt3{margin-top:10px;}
    .oly_cont_mt4{margin-top:12px;}
    .oly_cont_mt5{margin-top:10px;}
    .oly_cont_mt6{margin-top:10px;}
    .oly_cont_mt7{margin-top:10px;}
    .oly_usr_sett{position:absolute; right:10px; top:38px; border-top:1px solid #fff; width:130px;}
    .oly_usr_sett_bdr{height:60px; background:#fff; width:1px; margin-left:65px;}
    .oly_usr_sett_txt{color:#fff; margin-left:0px; margin-top:5px;}
    .oly_view_cnt{position:absolute; left:51%; top:37px;}
    .oly_view_cnt .oly_cont_fbdr{width:85px;}
    .oly_view_cnt .oly_cont_txt{width:180px; margin-top:-7px;}
    .close_oly{
        background: none repeat scroll 0 0 #fff;
        left: 0;
        position:fixed;
        top: 0;
        width: 100%;
        z-index: 9999;
        padding: 6px 0 8px;
    }
    .oly_text{text-align:center;color:#333;font-size:18px;}
    .oly_text a{color:#609bc1; cursor:pointer}
    .oly_wc{color:#333; font-size:28px;margin-top: 3px;}
    .close_txt{width:800px; margin:7px auto 0}
    .navbar.navbar-inverse.navbar-fixed-top{top:71px;}
    .side-nav,.breadcrumb_fixed{top:103px}
    .fix-status-widget{top:112px}
</style>
<div id="" <?php echo $styleClass; ?>>
<div class="close_oly">
    <div class="close_txt">
        <div class="fl oly_wc">Welcome to <img src="img/images/logo_outer_sml.png" style="margin-top: -4px; margin-left: 4px;" /> !</div>
        <div class="oly_text fr">
            <div>Take a moment to note the main action links.</div>
            <button class="btn btn_blue" type="button" onClick="close_test();">Ok, I got it.</button>
         </div>
        <div class="cb"></div>
    </div>
</div>
<div class="main_overlay">
    <div class="oly_bdr_crtask"></div>
    <div class="oly_view_cnt"></div>
    <div class="oly_usr_sett">
        <div class="oly_usr_sett_bdr"></div>
        <div class="oly_usr_sett_txt">Personal, Company & Account Settings</div>
    </div>
    <div class="oly_lcnt_mdiv">
        <div class="oly_container oly_cont_mt1">
            <div class="fl oly_cont_fbdr"></div>
            <div class="fl oly_cont_txt">ToDos, Summary and Statistics</div>
            <div class="cb"></div>
        </div>
        <div class="oly_container oly_cont_mt2">
            <div class="fl oly_cont_fbdr"></div>
            <div class="fl oly_cont_txt">Search, Filter, Order and Group Tasks</div>
            <div class="cb"></div>
        </div>
        <div class="oly_container oly_cont_mt3">
            <div class="fl oly_cont_fbdr"></div>
            <div class="fl oly_cont_txt">List of files attached to the tasks</div>
            <div class="cb"></div>
        </div>
        <div class="oly_container oly_cont_mt4">
            <div class="fl oly_cont_fbdr"></div>
            <div class="fl oly_cont_txt">Create milestone, set a due date and add task to the milestones</div>
            <div class="cb"></div>
        </div>
        <div class="oly_container oly_cont_mt5">
            <div class="fl oly_cont_fbdr"></div>
            <div class="fl oly_cont_txt">Create and manage Projects. Assign projects to Team</div>
            <div class="cb"></div>
        </div>
        <div class="oly_container oly_cont_mt6">
            <div class="fl oly_cont_fbdr"></div>
            <div class="fl oly_cont_txt">Add and manage Team members. Add users to projects.</div>
            <div class="cb"></div>
        </div>
        <div class="oly_container oly_cont_mt7">
            <div class="fl oly_cont_fbdr"></div>
            <div class="fl oly_cont_txt">Just schedule it and get Daily Progress Update from your Team</div>
            <div class="cb"></div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function close_test(){
	var strURL = "<?php echo HTTP_ROOT;?>";
        $.ajax(strURL+'/projects/hide_default_inner',{},function(res){
        });
        $('.main_overlay').hide();
        $('.close_oly').slideUp(600);
        $('.navbar.navbar-inverse.navbar-fixed-top').css({'top':'0'});
        $('.side-nav').css({'top':'32px'});
        $('.breadcrumb_fixed').css({'top':'32px'});
        $('.fix-status-widget').css({'top':'41px'});
        $('.slide_rht_con').css({'padding-top':'25px'});

    }
    function reload(){
        var url="<?php echo $this->Html->url('onbording');?>";
        window.location.href=url;
    }
</script>
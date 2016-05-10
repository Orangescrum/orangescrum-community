<div class="due_task_list up_task_list">UPCOMING TASKS</div>
<?php if(!empty($nextdue)){ ?>
    <?php foreach($nextdue as $k=>$v){?>
	<div class="up_task_data">
	    <b><?php $b=explode(" ",$v['Easycase']['due_date']);$a=explode("-",$b[0]);echo "".date("M ", mktime(0, 0, 0, $a[1], $a[2],$a[0]))."";?> <?php $b=explode(" ",$v['Easycase']['due_date']);$a=explode("-",$b[0]);echo date("d ", mktime(0, 0, 0, $a[1], $a[2],$a[0]));?></b><br/>
	    <?php $projectId = $this->Casequery->getProjUniqId($v['Easycase']['project_id']); ?>
	    <a href="<?php echo HTTP_ROOT; ?>dashboard#details/<?php echo $v['Easycase']['uniq_id']; ?>" ><?php echo $this->Format->shortLength($v['Easycase']['title'],18); ?></a> 
	    <span>
		Coming up in <?php	
                $upcoming_dates = "";
		$date1 = $v['Easycase']['due_date'];
		$date2 = $today;
               
		$diff = abs(strtotime($date2) - strtotime($date1));
                
		$years = floor($diff / (365*60*60*24));
		$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
		$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
                if($years >0){
                    $upcoming_dates .= $years .' years ';
                }
                if($months > 0){
                    $upcoming_dates .= $months .' months ';
                }
                if($days >0){
                     $upcoming_dates .= $days ;
                }
             //    echo $date1."--------------".$date2."==========".$diff.'<br>'.$years."----".$months."-------===---".$days ;
        //     printf("%d years, %d months, %d days\n", $years, $months, $days);
		echo $upcoming_dates; ?> day(s)
	    </span>
	</div>	
    <?php } ?>
<?php }else{ ?>
    <div class="fnt_clr_gry">No tasks</div>
<?php } ?>

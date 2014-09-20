<?php
class DatetimeHelper extends AppHelper {
	
	function nextDate($givenDateTime,$value,$type)
	{
		if($givenDateTime)
		{
			$dat = explode(" ",$givenDateTime);
			$dat1 = explode("-",$dat[0]);
			$dat2 = explode(":",$dat[1]);
			if($type == "day")
			{
				$next_dt = mktime($dat2[0], $dat2[1], $dat2[2], $dat1[1], $dat1[2]+$value, $dat1[0]);
			}
			if($type == "month")
			{
				$next_dt = mktime($dat2[0], $dat2[1], $dat2[2], $dat1[1]+$value, $dat1[2], $dat1[0]);
			}
			$datetime = date("Y-m-d H:i:s", $next_dt);
			return $datetime;
		}
		else
		{
			return "";
		}
	}
	function dateDiff($date1,$date2)
	{
		if(strtotime($date2) > strtotime($date1)) {
			return round(abs(strtotime($date2)-strtotime($date1))/86400);
		}
		else {
			return round(abs(strtotime($date1)-strtotime($date2))/86400);
		}
	}
	function caseDetailsFormat($datetime,$curdate)
	{
		$output = explode(" ",$datetime);
		$dateExp = explode("-",$output[0]);
		$dateformated = $dateExp[1]."/".$dateExp[2]."/".$dateExp[0];
			
		$yesterday = date("Y-m-d",strtotime($curdate."-1 days"));
		if($dateformated == $this->dateFormatReverse($curdate))
		{
			return "Today at ".date("g:i a", strtotime($datetime));
		}
		elseif($dateformated == $this->dateFormatReverse($yesterday))
		{
			return "Y'day at ".date("g:i a", strtotime($datetime));
		}
		else
		{
			return date("M jS Y, g:i a", strtotime($datetime));
		}
	}
	function dueDateFormat($duedate,$curdate)
	{
		$yesterday = date("Y-m-d",strtotime($curdate."-1 days"));
		$tomorrow = date("Y-m-d",strtotime($curdate."+1 days"));
		
		if($duedate == $curdate) {
			return "Today";
		}
		elseif($duedate == $yesterday) {
			return "Y'day";
		}
		elseif($duedate == $tomorrow) {
			return "Tomorrow";
		}
		else {
			return date("m/d/Y", strtotime($duedate));
		}
	}
	function dateFormatReverse($output_date)
	{
		if($output_date != "")
		{
			if(strstr($output_date," "))
			{
				$exp = explode(" ",$output_date);
				$od = $exp[0];
			}
			else
			{
				$od = $output_date;
			}
			$date_ex2 = explode("-",$od);
			$dateformated_input = $date_ex2[1]."/".$date_ex2[2]."/".$date_ex2[0];
			if($date_ex2[2] != "00")
			{
				return $dateformated_input;
			}
		}
	}
	function dateFormatOutputdateTime_day($date_time,$curdate = NULL,$type=NULL,$is_month_last=0,$viewtype=''){
		if($date_time != ""){
			$date_time = date("Y-m-d H:i:s",strtotime($date_time));
			$output = explode(" ",$date_time);
			$date_ex2 = explode("-",$output[0]);
			
			$dateformated = $date_ex2[1]."/".$date_ex2[2]."/".$date_ex2[0];
			if($date_ex2[2] != "00")
			{
				$displayWeek = 0;
				$timeformat = date('g:i a',strtotime($date_time));
				
				$week1 = date("l", mktime(0, 0, 0, $date_ex2[1], $date_ex2[2],$date_ex2[0]));
				$week_sub1 = substr($week1,"0","3");
				
				$yesterday = date("Y-m-d",strtotime($curdate."-1 days"));
				
				if($dateformated == $this->dateFormatReverse($curdate))
				{
					$dateTime_Format = "Today";
				}
				elseif($dateformated == $this->dateFormatReverse($yesterday))
				{
					$dateTime_Format = "Y'day";
				}
				else
				{
					$CurYr = date("Y",strtotime($curdate));
					$DateYr = date("Y",strtotime($dateformated));
					if($viewtype=='kanban'){
						$dateformated = date("m/d",strtotime($dateformated));
					}elseif($CurYr == $DateYr) {
						$dateformated = date("M d",strtotime($dateformated));
						$dtformated = date("M d",strtotime($dateformated)).", ".date("D",strtotime($dateformated));
						$displayWeek = 1;
					} else {
						$dateformated = date("M d, Y",strtotime($dateformated));
						$dtformated = date("M d, Y",strtotime($dateformated));
					}
					$dateTime_Format = $dateformated;
				}		
				if($type == 'date') {
					return $dateTime_Format;
				}
				elseif($type == 'time') {
					return $dateTime_Format." ".$timeformat;
				}
				elseif($type == 'week') { 
					if($dateTime_Format == "Today" || $dateTime_Format == "Y'day" || !$displayWeek) {
						return $dateTime_Format;
					}else {
						//return $dateTime_Format.", ".date("D",strtotime($dateformated));
						return $dtformated;
						//return $dateTime_Format;
					}
				}
				else {
					if($dateTime_Format == "Today" || $dateTime_Format == "Y'day") {
					    if($is_month_last) {	
						return $dateTime_Format;
					    } else {
						return $dateTime_Format." ".$timeformat;
					    }
					}
					else {
					    if($is_month_last) {
						return date("D",strtotime($dateformated)).", ".$dateTime_Format;
					    }elseif($viewtype=='kanban'){
							return $dateTime_Format.", "." ".$timeformat;
					    }else {
						//return $dateTime_Format.", ".date("D",strtotime($dateformated))." ".$timeformat;
						return $dtformated." ".$timeformat;
					    }
					}
				}
			}
		}
	}
     function dateFormatOutputdateTime($date_time,$curdate = NULL,$type=NULL)
	{
		//echo $date_time."------".$curdate."<br/>";
		$curr = strtotime($curdate);
		$crted = strtotime($date_time);
		$diff_in_sec = ($curr-$crted);
		$diff_in_min = round(($curr-$crted)/60);
		$diff_in_hr =  round(($curr-$crted)/(60*60));
		if($diff_in_sec < 60){
			if($diff_in_sec !=1){
				//return $diff_in_sec." secs ago";
				return "just now";
			}else{
				//return $diff_in_sec." sec ago";
				return "just now";
			}
			
		}else if($diff_in_min < 60){
			if($diff_in_min !=1){
				return $diff_in_min." mins ago";
			}else{
				return $diff_in_min." min ago";
			}
			
		}else if($diff_in_hr < 24){
			if($diff_in_hr !=1){
				return $diff_in_hr." hours ago";
			}else{
				return $diff_in_hr." hour ago";
			}
			
		}
	}
     function facebook_style($date,$curdate = NULL,$type = NULL){
	
		$checkDate = date("Y-m-d",strtotime($date));
		$checkCur = date("Y-m-d",strtotime($curdate));
		if($checkDate == $checkCur) {
			if($type == 'date') {
				return $this->dateFormatOutputdateTime($date,$curdate,'date');
			}
			else {
				return $this->dateFormatOutputdateTime($date,$curdate,'time');
			}
		}

		$timestamp = strtotime($date);
		$difference = strtotime($curdate) - $timestamp;
		
		//return $date." - ".$curdate;
		
		$periods = array("sec", "min", "hour", "day", "week", "month", "year", "decade");
		$lengths = array("60","60","24","7","4.35","12","10");
		
		if ($difference > 0) { // this was in the past time
		$ending = "ago";
		} else { // this was in the future time
		$difference = -$difference;
		$ending = "to go";
		}
		for($j = 0; ($difference >= $lengths[$j] && $j<=6); $j++) $difference /= $lengths[$j];
		$difference = round($difference);
		if($difference != 1) $periods[$j].= "s";
		$text = "$difference $periods[$j] $ending";
		return $text;
	}
	/* Added by Smruti on 08092013 */
	function facebook_datetimestyle($date){
		return $checkDate = date('l, F d, Y  \a\t h:i a',strtotime($date));
		//$checkTime = date('h:i a',strtotime($date));
		//return $checkDate." at ". $checkTime;
	}
	function facebook_datestyle($date){
		$checkDate = date('l, F d, Y',strtotime($date));
		return $checkDate;
	}
	function facebook_style_date_time($date,$curdate = NULL,$type = NULL,$restype=''){
	
		$checkDate = date("Y-m-d",strtotime($date));
		$checkCur = date("Y-m-d",strtotime($curdate));
		if($checkDate == $checkCur) {
			if($restype=='days') {/*This is added only for days type results and for current date it will return 0 days,Used in osadmin manage company page */
				return 0;
			}elseif($type == 'date') {
				return $this->dateFormatOutputdateTime_day($date,$curdate,'date');
			}else{
				return $this->dateFormatOutputdateTime_day($date,$curdate,'time');
			}
		}

		$timestamp = strtotime($date);
		$difference = strtotime($curdate) - $timestamp;
		
		//return $date." - ".$curdate;
		
		$periods = array("sec", "min", "hour", "day", "week", "month", "year", "decade");
		$lengths = array("60","60","24","7","4.35","12","10");
		
		if ($difference > 0) { // this was in the past time
		$ending = "ago";
		} else { // this was in the future time
		$difference = -$difference;
		$ending = "to go";
		}
		if($restype=='days'){
			$periods = array("sec", "min", "hour", "day");
			$lengths = array("60","60","24");
			for($j = 0; ($difference >= $lengths[$j] && $j<3) ; $j++) $difference /= $lengths[$j];
			if($j<3){
				return 0;// As we are calculating everything in terms of days so we will skip the Hr , mins ,Secs
			}
			return round($difference);
		}else{
			for($j = 0; $difference >= $lengths[$j]; $j++) $difference /= $lengths[$j];
		}
		
		$difference = round($difference);
		if($difference != 1) $periods[$j].= "s";
		$text = "$difference $periods[$j] $ending";
		return $text;
	}
	function caseDateTime_noTime($dateTime,$curdate)
	{
		$dt = explode(" ",$dateTime);
		$date = explode("-",$dt[0]);
		
		$date_week = $date[1]."/".$date[2]."/".$date[0];
		
		$date = $date[1]."/".$date[2]."/".substr($date[0],2,2);
		$date_week_exp = explode("/",$date_week);
		$time = explode(":",$dt[1]);
		if($time[0] > "12")
		{
			$hour  = $time[0] - 12;
			$timeformat = $hour.":".$time[1]." pm";
		}
		elseif($time[0] == "12")
		{
			$timeformat = $time[0].":".$time[1]." pm";
		}
		elseif($time[0] < "12")
		{
			$timeformat = $time[0].":".$time[1]." am";
		}
		$week1 = date("l", mktime(0, 0, 0, $date_week_exp[0], $date_week_exp[1],$date_week_exp[2]));
		$week_sub1 = substr($week1,"0","3");
	
		$yesterday = date("Y-m-d",strtotime($curdate."-1 days"));
		if($date_week == $this->dateFormatReverse($curdate))
		{
			return "Today";
		}
		elseif($date_week == $this->dateFormatReverse($yesterday))
		{
			return "Y'day";
		}
		else
		{
			return $date.", ".date("D",strtotime($date));
		}
	}
	function dateFormatOutputdateTime_details($date_time,$curdate)
	{
		if($date_time != "")
		{
			$output = explode(" ",$date_time);
			$date_ex2 = explode("-",$output[0]);
			$dateformated = $date_ex2[1]."/".$date_ex2[2]."/".$date_ex2[0];
			if($date_ex2[2] != "00")
			{	
				$time = explode(":",$output[1]);
				if($time[0] > "12")
				{
					$hour  = $time[0] - 12;
					$timeformat = $hour.":".$time[1]." pm";
				}
				elseif($time[0] == "12")
				{
					$timeformat = $time[0].":".$time[1]." pm";
				}
				elseif($time[0] < "12")
				{
					$timeformat = $time[0].":".$time[1]." am";
				}
				
				$week1 = date("l", mktime(0, 0, 0, $date_ex2[1], $date_ex2[2],$date_ex2[0]));
				$week_sub1 = substr($week1,"0","3");
				
				$yesterday = date("Y-m-d",strtotime($curdate."-1 days"));
				if($dateformated == $this->dateFormatReverse($yesterday))
				{
					return "Y'day at ".$timeformat;
				}
				if($dateformated == $this->dateFormatReverse($curdate))
				{
					return "Today at ".$timeformat;
				}
				else
				{
					$dateTime_Format = $dateformated." at ".$timeformat;
					return $dateTime_Format;
				}
				
			}
		}
	}
}
?>

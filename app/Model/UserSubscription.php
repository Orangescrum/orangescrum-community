<?php
class UserSubscription extends AppModel{
	var $name = 'UserSubscription';


	function getstatistics($today=0){
                
		$cond =" 1 " ;
		if($today){
			$cond = " DATE(c.created)='".GMT_DATE."' ";
		}
		$sql1 = "SELECT MAX(u.id) as maxid , MIN(u.id) minid, us.isactive AS status FROM companies c ,user_subscriptions u,users us WHERE u.company_id = c.id AND u.user_id=us.id AND (us.isactive=1 OR us.isactive=2) AND c.is_active=1 AND c.name NOT LIKE '%AndolaTest%' AND u.is_free!=1 AND ".$cond." GROUP BY u.company_id ORDER BY `maxid`  DESC";
		$data1 = $this->query($sql1);
		//echo "<pre>";print_r($data1);exit;
		
		//arranging subscription.id
		$sidArr = array();
		foreach($data1 as $key=>$v) {
			$sidArr[] = $v[0]['maxid'];
			$sidArr[] = $v[0]['minid'];
		}
		
		//getting subscription_id
		$sql2 = "SELECT UserSubscription.id,UserSubscription.subscription_id FROM user_subscriptions UserSubscription WHERE UserSubscription.id IN (".implode(',',$sidArr).")";
		$res_sid = $this->query($sql2);
		$subArr = array();
		foreach($res_sid as $v) {
			$subArr[$v['UserSubscription']['id']] = $v['UserSubscription']['subscription_id'];
		}
		
		$comp_st['pending'] =0;
		$comp_st[1]=0;$comp_st[2]=0;
		$minconvs[1]=0;$minconvs[2]=0;
		$comp_st['conv_per'] =0;$comp_st['total_conv']=0;
		foreach($data1 as $key=>$v) {
			if ($v['us']['status']==1) {
				if ($subArr[$v[0]['maxid']]==1 || $subArr[$v[0]['maxid']]==9) {
					$comp_st[1] = $comp_st[1]?($comp_st[1]+1):1;
				} else {
					$comp_st[2] = $comp_st[2]?($comp_st[2]+1):1;
				}
				
				$minconvs[$subArr[$v[0]['minid']]] = $minconvs[$subArr[$v[0]['minid']]]+1;
			} else {
				$comp_st['pending'] +=1; 
			}
		}
		if ($minconvs[1]>0 || $minconvs[9]>0) {
			$conv_basic_to_paid = round(((($minconvs[1]+$minconvs[9]-$comp_st[1])/($minconvs[1]+$minconvs[9]))*100),2);
			$comp_st['conv_per']=$conv_basic_to_paid;
			$comp_st['total_conv'] = ($minconvs[1]+$minconvs[9]-$comp_st[1]);
		}
		return $comp_st;
	}
	function getydata($dt_arr){
		foreach($dt_arr as $key =>$date){
			$ydata = $this->query("SELECT COUNT(u.id) as cnt , u.subscription_id FROM user_subscriptions u, companies c WHERE u.company_id = c.id AND DATE(u.created) ='".$date."' AND c.is_active=1 AND u.is_free!=1 GROUP BY u.subscription_id");
			$ydata_list = Set::combine($ydata, '{n}.u.subscription_id', array('{0} {1}', '{n}.0.cnt'));
			
			$ydata_list[1] = $ydata_list[1]?(int)$ydata_list[1]:0;
			$ydata_list[9] = $ydata_list[9]?(int)$ydata_list[9]:0;
			
			$data['free'][] = $ydata_list[1]+$ydata_list[9];
			$data['basic'][] = $ydata_list[5]?(int)$ydata_list[5]:0;
			$data['team'][] = $ydata_list[6]?(int)$ydata_list[6]:0;
			$data['business'][] = $ydata_list[7]?(int)$ydata_list[7]:0;
			$data['premium'][] = $ydata_list[8]?(int)$ydata_list[8]:0;
		}
		return $data;
	}
}


<?php
error_reporting(1);
ini_set('error_reporting', E_ALL);
		print "jjjjjjjjjj".date('Y-m-d H:i:s',strtotime('-24 hours', time())).'==='.date('Y-m-d H:i:s',strtotime('now'));
		    $date1 = new DateTime(date('Y-m-d H:i:s',strtotime('-24 hours', time())));
		    $date2 = new DateTime(date('Y-m-d H:i:s',strtotime('now')));

		    $dat_remain = $date2->diff($date1)->format("%a");

		      print '----'.$dat_remain;exit;

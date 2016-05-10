<?php
class Easycase extends AppModel {
    var $name = 'Easycase';
    function formatCases($caseAll, $caseCount, $caseMenuFilters, $closed_cases, $milestones, $projUniq, $usrDtlsArr, $frmt, $dt, $tz, $cq) {
        $curCreated = $tz->GetDateTime(SES_TIMEZONE,TZ_GMT,TZ_DST,TZ_CODE,GMT_DATETIME,"datetime");
        $curdtT = $tz->GetDateTime(SES_TIMEZONE,TZ_GMT,TZ_DST,TZ_CODE,GMT_DATETIME,"date");
        $curTime = date('H:i:s',strtotime($curCreated));

        if($caseCount) {
            $chkDateTime = $chkDateTime1 = $projIdcnt = $newpjcnt = $repeatcaseTypeId = $repeatLastUid = $repeatAssgnUid = "";
            $pjname='';
	    $sql = "SELECT Type.* FROM types AS Type WHERE Type.company_id = 0 OR Type.company_id =".SES_COMP;
	    $typeArr = $this->query($sql);
            foreach($caseAll as $caseKey => $getdata) {
                $projId = $getdata['Easycase']['project_id'];
                $newpjcnt=$projId;

                $actuallyCreated = $tz->GetDateTime(SES_TIMEZONE,TZ_GMT,TZ_DST,TZ_CODE,$getdata['Easycase']['actual_dt_created'],"datetime");
                $newdate_actualdate = explode(" ",$actuallyCreated);
                $updated = $tz->GetDateTime(SES_TIMEZONE,TZ_GMT,TZ_DST,TZ_CODE,$getdata['Easycase']['dt_created'],"datetime");
                $newdate = explode(" ",$updated);

                if($caseMenuFilters == "milestone" && count($milestones)) {
                    $mdata[] =$getdata['Easycase']['Mid'];
                    if($chkMstone != $getdata['Easycase']['Mid']) {

                        $endDate = $getdata['Easycase']['end_date']." ".$curTime;
                        $days = $dt->dateDiff($endDate,$curCreated);

                        $mlstDT = $dt->dateFormatOutputdateTime_day($getdata['Easycase']['end_date'],GMT_DATETIME,'week');

                        $totalCs = $milestones[$getdata['Easycase']['Mid']]['totalcases'];
                        $totalClosedCs = 0;
                        if(isset($closed_cases[$getdata['Easycase']['Mid']])) {
                            $totalClosedCs = $closed_cases[$getdata['Easycase']['Mid']]['totalclosed'];
                        }
                        $fill = 0;
                        if($totalClosedCs != 0) {
                            $fill = round((($totalClosedCs/$totalCs)*100));
                        }

                        $caseAll[$caseKey]['Easycase']['intEndDate'] = strtotime($endDate);
                        $caseAll[$caseKey]['Easycase']['days_diff'] = $days;
                        $caseAll[$caseKey]['Easycase']['mlstDT'] = $mlstDT;
                        $caseAll[$caseKey]['Easycase']['mlstFill'] = $fill;
                        $caseAll[$caseKey]['Easycase']['totalClosedCs'] = $totalClosedCs;
                        $caseAll[$caseKey]['Easycase']['totalCs'] = $totalCs;
                    }
                    if($projIdcnt != $newpjcnt && $projUniq == 'all' ) {
                        $pjname = $cq->getProjectName($projId);
                        $caseAll[$caseKey]['Easycase']['pjname'] = $pjname['Project']['name'];
                        $caseAll[$caseKey]['Easycase']['pjUniqid'] = $pjname['Project']['uniq_id'];
                    }elseif($projUniq !='all') {
                        if(!$pjname) {
                            $pjname = $cq->getProjectName($projId);
                        }
                        $caseAll[$caseKey]['Easycase']['pjname'] = $pjname['Project']['name'];
                        $caseAll[$caseKey]['Easycase']['pjUniqid'] = $pjname['Project']['uniq_id'];
                    }

                    //$getdata['Easycase']['Mid'];
                } else {
                    if($projIdcnt != $newpjcnt && $projUniq == 'all' ) {
                        $pjname = $cq->getProjectName($projId);
                        $caseAll[$caseKey]['Easycase']['pjname'] = $pjname['Project']['name'];
                        $caseAll[$caseKey]['Easycase']['pjUniqid'] = $pjname['Project']['uniq_id'];
                    }elseif($projUniq !='all') {
                        if(!$pjname) {
                            $pjname = $cq->getProjectName($projId);
                        }
                        $caseAll[$caseKey]['Easycase']['pjname'] = $pjname['Project']['name'];
                        $caseAll[$caseKey]['Easycase']['pjUniqid'] = $pjname['Project']['uniq_id'];
                    }

                    if($caseCreateDate) {
                        if(($chkDateTime1 != $newdate_actualdate[0]) ) {
                            $caseAll[$caseKey]['Easycase']['newActuldt'] = $dt->dateFormatOutputdateTime_day($actuallyCreated,$curCreated,'date');
                        }
                    } else {
                        if(($chkDateTime != $newdate[0]) || ($projIdcnt != $newpjcnt && $projUniq == 'all') ) {
                            $caseAll[$caseKey]['Easycase']['newActuldt'] = $dt->dateFormatOutputdateTime_day($updated,$curCreated,'date');
                        }
                    }

                }

                //case type start
                $caseTypeId = $getdata['Easycase']['type_id'];
                if($repeatcaseTypeId != $caseTypeId) {
		    
                    //$types = $cq->getTypeArr($caseTypeId,$GLOBALS['TYPE']);
                    $types = $cq->getTypeArr($caseTypeId,$typeArr);
                    if(count($types)) {
                        $typeShortName = $types['Type']['short_name'];
                        $typeName = $types['Type']['name'];
                    }
                    else {
                        $typeShortName = "";
                        $typeName = "";
                    }
                }
		$iconExist = 0;
		if (trim($typeShortName) && file_exists(WWW_ROOT."img/images/types/".$typeShortName.".png")) {
		    $iconExist = 1;
		}
                //$caseAll[$caseKey]['Easycase']['csTdTyp'] = $frmt->todo_typ($typeShortName,$typeName);
                $caseAll[$caseKey]['Easycase']['csTdTyp'] = array($typeShortName,$typeName,$iconExist);
                //case type end

                //Updated column start
                $caseAll[$caseKey]['Easycase']['fbActualDt'] = $dt->facebook_datetimestyle($updated);
                $caseAll[$caseKey]['Easycase']['updted'] = $dt->dateFormatOutputdateTime_day($updated,$curCreated,'week');
                //Updated column end

                //Title Caption start
                if($getdata['Easycase']['case_count']) {
                    $getlastUid = $getdata['Easycase']['updated_by'];
                } else {
                    $getlastUid = $getdata['Easycase']['user_id'];
                }

                if($repeatLastUid != $getlastUid) {
                    if($getlastUid && $getlastUid != SES_ID) {
                        $usrDtls = $cq->getUserDtlsArr($getlastUid,$usrDtlsArr);
                        $usrName = $frmt->formatText($usrDtls['User']['name']);
                        //$usrShortName = strtoupper($usrDtls['User']['short_name']);
                        $usrShortName = ucfirst($usrDtls['User']['name']);
                    } else {
                        $usrName = "";
                        $usrShortName = "me";
                    }
                }
                $caseAll[$caseKey]['Easycase']['usrName'] = $usrName; //case status title caption name
                $caseAll[$caseKey]['Easycase']['usrShortName'] = $usrShortName; //case status title caption sh_name
                $caseAll[$caseKey]['Easycase']['updtedCapDt'] = $dt->dateFormatOutputdateTime_day($updated,$curCreated); //case status title caption date
                $caseAll[$caseKey]['Easycase']['fbstyle'] = $dt->facebook_style($updated,$curCreated,'time'); //case status title caption date
                if($caseMenuFilters=='milestone') {
                    $caseAll[$caseKey]['Easycase']['proImage'] = $frmt->formatprofileimage($usrDtlsArr[$getlastUid]['User']['photo']); //case status title caption sh_name
                }
                //Title Caption end

                //case status start
                $caseLegend = $getdata['Easycase']['legend'];
                //$caseAll[$caseKey]['Easycase']['csSts'] = $frmt->getStatus($caseTypeId,$caseLegend);
                //case status end

                //assign info start
                $caseUserId = $getdata['Easycase']['user_id'];
                $caseAssgnUid = $getdata['Easycase']['assign_to'];
                if($caseAssgnUid && $repeatAssgnUid != $caseAssgnUid) {
                    if($caseAssgnUid != SES_ID) {
                        $usrAsgn = $cq->getUserDtlsArr($caseAssgnUid,$usrDtlsArr);
                        $asgnName = $frmt->formatText($usrAsgn['User']['name']);
                        //$asgnShortName = strtoupper($usrAsgn['User']['short_name']);
                        $asgnShortName = $frmt->shortLength(ucfirst($usrAsgn['User']['name']),10);
                    } else {
                        $asgnShortName = '<span style="color:#E0814E">me</span>';
                        $asgnName = "";
                    }
                }
                if(!$caseAssgnUid && $caseUserId == SES_ID) {
                    $asgnShortName = '<span style="color:#E0814E">me</span>';
                    $asgnName = "";
                }
                elseif(!$caseAssgnUid) {
                    $usrAsgn = $cq->getUserDtlsArr($caseUserId,$usrDtlsArr);
                    $asgnName = $frmt->formatText($usrAsgn['User']['name']);
                    //$asgnShortName = strtoupper($usrAsgn['User']['short_name']);
                    $asgnShortName = $frmt->shortLength(ucfirst($usrAsgn['User']['name']),10);
                }
                $caseAll[$caseKey]['Easycase']['asgnName'] = $asgnName;
                $caseAll[$caseKey]['Easycase']['asgnShortName'] = $asgnShortName;
                //assign info end

                //Created date start
                //$actualDt1 = $tz->GetDateTime(SES_TIMEZONE,TZ_GMT,TZ_DST,TZ_CODE,$getdata['Easycase']['actual_dt_created'],"datetime");
                //$caseAll[$caseKey]['Easycase']['actualDt1FbDtT'] = $dt->facebook_datetimestyle($actualDt1);
                //$caseAll[$caseKey]['Easycase']['actualDt1FbDt'] = $dt->facebook_style($actualDt1,$curCreated,'date');
                //Created date end

                if($caseTypeId == 10 || $caseLegend == 3 || $caseLegend == 5) {
                    $caseDueDate = $getdata['Easycase']['due_date'];
                    if($caseDueDate != "NULL" && $caseDueDate != "0000-00-00" && $caseDueDate != "" && $caseDueDate != "1970-01-01") {
                        $csDuDtFmtT = $dt->facebook_datestyle($caseDueDate);
                        $csDuDtFmt = $dt->dateFormatOutputdateTime_day($caseDueDate,$curCreated,'week');
                    }
                    else {
                        $csDuDtFmtT = '';
                        $csDuDtFmt = '';
                    }
                    $csDueDate = $csDuDtFmt;
                }else {
                    $caseDueDate = $getdata['Easycase']['due_date'];
                    if($caseDueDate != "NULL" && $caseDueDate != "0000-00-00" && $caseDueDate != "" && $caseDueDate != "1970-01-01") {
                        if($caseDueDate < $curdtT) {
                            $csDuDtFmtT = $dt->facebook_datestyle($caseDueDate);
                            $csDuDtFmt = '<span class="over-due">Overdue</span>';
                            $csDueDate = $dt->dateFormatOutputdateTime_day($caseDueDate,$curCreated,'week');
                        }else {
                            $csDuDtFmtT = $dt->facebook_datestyle($caseDueDate);
                            $csDuDtFmt = $dt->dateFormatOutputdateTime_day($caseDueDate,$curCreated,'week');
                            $csDueDate = $csDuDtFmt;
                        }
                    }
                    else {
                        $csDuDtFmtT = '';
                        $csDuDtFmt = '<span class="set-due-dt">Set Due Dt</span>';
                        $csDueDate = '';
                    }
                }
                $caseAll[$caseKey]['Easycase']['csDuDtFmtT'] = $csDuDtFmtT;
                $caseAll[$caseKey]['Easycase']['csDuDtFmt'] = $csDuDtFmt;
                $caseAll[$caseKey]['Easycase']['csDueDate'] = $csDueDate;

                $caseAll[$caseKey]['Easycase']['title'] = htmlentities($frmt->convert_ascii($frmt->longstringwrap($getdata['Easycase']['title'])),ENT_QUOTES,'UTF-8');

                $repeatLastUid = $getlastUid;
                $repeatAssgnUid = $caseAssgnUid;
                $repeatcaseTypeId = $caseTypeId;
                $chkDateTime = $newdate[0];
                $chkDateTime1 = $newdate_actualdate[0];
                $projIdcnt=$newpjcnt;
                if(intval($caseAll[$caseKey]['Easycase']['case_count'])) {
                    $caseAll[$caseKey]['Easycase']['reply_cnt'] = $this->getReplyCount($caseAll[$caseKey]['Easycase']['project_id'], $caseAll[$caseKey]['Easycase']['case_no']);
                } else {
                    $caseAll[$caseKey]['Easycase']['reply_cnt'] = 0;
                }
                unset(
                        $caseAll[$caseKey]['Easycase']['updated_by'],
                        $caseAll[$caseKey]['Easycase']['message'],
                        $caseAll[$caseKey]['Easycase']['hours'],
                        $caseAll[$caseKey]['Easycase']['completed_task'],
                        $caseAll[$caseKey]['Easycase']['due_date'],
                        $caseAll[$caseKey]['Easycase']['istype'],
                        $caseAll[$caseKey]['Easycase']['status'],
                        $caseAll[$caseKey]['Easycase']['dt_created'],
                        $caseAll[$caseKey]['Easycase']['actual_dt_created'],
                        $caseAll[$caseKey]['Easycase']['reply_type'],
                        $caseAll[$caseKey]['Easycase']['id_seq'],
                        $caseAll[$caseKey]['Easycase']['end_date'],
                        $caseAll[$caseKey]['Easycase']['Mproject_id'],
                        $caseAll[$caseKey][0],
                        $caseAll[$caseKey]['User']
                );
            }
        }

        if($caseMenuFilters == "milestone" && count($milestones)) {
            foreach($milestones AS $key =>$ms) {
                if(!$ms['totalcases']) {
                    $endDate = $ms['end_date']." ".$curTime;
                    $days = $dt->dateDiff($endDate,$curCreated);

                    $milestones[$key]['days_diff'] = $days;

                    $mlstDT = $dt->dateFormatOutputdateTime_day($ms['end_date'],GMT_DATETIME,'week');
                    $milestones[$key]['mlstDT'] = $mlstDT;
                    $milestones[$key]['intEndDate'] = strtotime($ms['end_date']);
                } /*else {
					unset(
						$milestones[$key]['title'],
						$milestones[$key]['uinq_id'],
						$milestones[$key]['isactive'],
						$milestones[$key]['user_id']
					);
				}*/

//				unset(
//					$milestones[$key]['end_date']
//				);
            }
        }

        return array('caseAll' => $caseAll, 'milestones' => $milestones);
    }

    function getReplyCount($projectId = NULL, $caseNo = NULL) {
        if(isset($projectId) && isset($caseNo)) {
            $sql = "SELECT COUNT(case_no) AS reply_cnt FROM easycases WHERE project_id=".$projectId." AND case_no=".$caseNo." AND message !='' AND istype=2 GROUP BY case_no";
            $reply = $this->query($sql);
            if(isset($reply) && !empty($reply))
                return $reply['0']['0']['reply_cnt'];
            else
                return 0;
        } else {
            return 0;
        }
    }

    function formatKanbanTask($statusTasklist, $caseCount, $caseMenuFilters, $closed_cases, $milestones, $projUniq, $usrDtlsArr, $frmt, $dt, $tz, $cq) {
        $curCreated = $tz->GetDateTime(SES_TIMEZONE,TZ_GMT,TZ_DST,TZ_CODE,GMT_DATETIME,"datetime");
        $curdtT = $tz->GetDateTime(SES_TIMEZONE,TZ_GMT,TZ_DST,TZ_CODE,GMT_DATETIME,"date");
        $curTime = date('H:i:s',strtotime($curCreated));
        foreach($statusTasklist as $taskkey=>$caseAll) {
            $chkDateTime = $chkDateTime1 = $projIdcnt = $newpjcnt = $repeatcaseTypeId = $repeatLastUid = $repeatAssgnUid = "";
            $pjname='';
            foreach($caseAll as $caseKey => $getdata) {
                $projId = $getdata['Easycase']['project_id'];
                $newpjcnt=$projId;
                $actuallyCreated = $tz->GetDateTime(SES_TIMEZONE,TZ_GMT,TZ_DST,TZ_CODE,$getdata['Easycase']['actual_dt_created'],"datetime");
                $newdate_actualdate = explode(" ",$actuallyCreated);
                $updated = $tz->GetDateTime(SES_TIMEZONE,TZ_GMT,TZ_DST,TZ_CODE,$getdata['Easycase']['dt_created'],"datetime");
                $newdate = explode(" ",$updated);

                //				if($caseMenuFilters == "milestone" && count($milestones)) {
                //					$mdata[] =$getdata['Easycase']['Mid'];
                //					if($chkMstone != $getdata['Easycase']['Mid']) {
                //
                //						$endDate = $getdata['Easycase']['end_date']." ".$curTime;
                //						$days = $dt->dateDiff($endDate,$curCreated);
                //
                //						$mlstDT = $dt->dateFormatOutputdateTime_day($getdata['Easycase']['end_date'],GMT_DATETIME,'week');
                //
                //						$totalCs = $milestones[$getdata['Easycase']['Mid']]['totalcases'];
                //						$totalClosedCs = 0;
                //						if(isset($closed_cases[$getdata['Easycase']['Mid']])){
                //							$totalClosedCs = $closed_cases[$getdata['Easycase']['Mid']]['totalclosed'];
                //						}
                //						$fill = 0;
                //						if($totalClosedCs != 0) {
                //							$fill = round((($totalClosedCs/$totalCs)*100));
                //						}
                //
                //						$caseAll[$caseKey]['Easycase']['intEndDate'] = strtotime($endDate);
                //						$caseAll[$caseKey]['Easycase']['days_diff'] = $days;
                //						$caseAll[$caseKey]['Easycase']['mlstDT'] = $mlstDT;
                //						$caseAll[$caseKey]['Easycase']['mlstFill'] = $fill;
                //						$caseAll[$caseKey]['Easycase']['totalClosedCs'] = $totalClosedCs;
                //						$caseAll[$caseKey]['Easycase']['totalCs'] = $totalCs;
                //					}
                //					//$getdata['Easycase']['Mid'];
                //				} else {
                if($projIdcnt != $newpjcnt && $projUniq == 'all' ) {
                    $pjname = $cq->getProjectName($projId);
                    $caseAll[$caseKey]['Easycase']['pjname'] = $pjname['Project']['name'];
                    $caseAll[$caseKey]['Easycase']['pjUniqid'] = $pjname['Project']['uniq_id'];
                }elseif($projUniq !='all') {
                    if(!$pjname) {
                        $pjname = $cq->getProjectName($projId);
                    }
                    $caseAll[$caseKey]['Easycase']['pjname'] = $pjname['Project']['name'];
                    $caseAll[$caseKey]['Easycase']['pjUniqid'] = $pjname['Project']['uniq_id'];
                }

                if($caseCreateDate) {
                    if(($chkDateTime1 != $newdate_actualdate[0]) ) {
                        $caseAll[$caseKey]['Easycase']['newActuldt'] = $dt->dateFormatOutputdateTime_day($actuallyCreated,$curCreated,'date');
                    }
                } else {
                    if(($chkDateTime != $newdate[0]) || ($projIdcnt != $newpjcnt && $projUniq == 'all') ) {
                        $caseAll[$caseKey]['Easycase']['newActuldt'] = $dt->dateFormatOutputdateTime_day($updated,$curCreated,'date');
                    }
                }

                //				}

                //case type start
                $caseTypeId = $getdata['Easycase']['type_id'];
                if($repeatcaseTypeId != $caseTypeId) {
                    $types = $cq->getTypeArr($caseTypeId,$GLOBALS['TYPE']);
                    if(count($types)) {
                        $typeShortName = $types['Type']['short_name'];
                        $typeName = $types['Type']['name'];
                    }else {
                        $typeShortName = "";
                        $typeName = "";
                    }
                }
                $caseAll[$caseKey]['Easycase']['csTdTyp'] = array($typeShortName,$typeName);
                //case type end

                //Updated column start
                $caseAll[$caseKey]['Easycase']['fbActualDt'] = $dt->facebook_datetimestyle($updated);
                $caseAll[$caseKey]['Easycase']['updted'] = $dt->dateFormatOutputdateTime_day($updated,$curCreated,'week');
                //Updated column end

                //Title Caption start
                if($getdata['Easycase']['case_count']) {
                    $getlastUid = $getdata['Easycase']['updated_by'];
                    //$caseAll[$caseKey]['Easycase']['proImage'] = $frmt->formatprofileimage($usrDtlsArr[$getlastUid]['User']['photo']); //case status title caption sh_name
                } else {
                    $getlastUid = $getdata['Easycase']['user_id'];
                    //$caseAll[$caseKey]['Easycase']['proImage'] = $frmt->formatprofileimage($getdata['User']['photo']); //case status title caption sh_name
                }
                $caseAll[$caseKey]['Easycase']['proImage'] = $frmt->formatprofileimage($usrDtlsArr[$getlastUid]['User']['photo']); //case status title caption sh_name
                if($repeatLastUid != $getlastUid) {
                    if($getlastUid && $getlastUid != SES_ID) {
                        $usrDtls = $cq->getUserDtlsArr($getlastUid,$usrDtlsArr);
                        $usrName = $frmt->formatText($usrDtls['User']['name']);
                        //$usrShortName = strtoupper($usrDtls['User']['short_name']);
                        $usrShortName = ucfirst($usrDtls['User']['name']);
                    } else {
                        $usrName = "";
                        $usrShortName = "me";
                    }
                }
                $caseAll[$caseKey]['Easycase']['usrName'] = $usrName; //case status title caption name
                $caseAll[$caseKey]['Easycase']['usrShortName'] = $usrShortName; //case status title caption sh_name

                //$caseAll[$caseKey]['Easycase']['proImage'] = $frmt->formatprofileimage($getdata['User']['photo']); //case status title caption sh_name
                $caseAll[$caseKey]['Easycase']['updtedCapDt'] = $dt->dateFormatOutputdateTime_day($updated,$curCreated,'','','kanban'); //case status title caption date
                //Title Caption end

                //case status start
                $caseLegend = $getdata['Easycase']['legend'];
                //case status end

                //assign info start
                $caseUserId = $getdata['Easycase']['user_id'];
                $caseAssgnUid = $getdata['Easycase']['assign_to'];
                if($caseAssgnUid && $repeatAssgnUid != $caseAssgnUid) {
                    if($caseAssgnUid != SES_ID) {
                        $usrAsgn = $cq->getUserDtlsArr($caseAssgnUid,$usrDtlsArr);
                        $asgnName = $frmt->formatText($usrAsgn['User']['name']);
                        //$asgnShortName = strtoupper($usrAsgn['User']['short_name']);
                        $asgnShortName = $frmt->shortLength(ucfirst($usrAsgn['User']['name']),7);
                    } else {
                        $asgnShortName = '<span style="color:#E0814E">me</span>';
                        $asgnName = "";
                    }
                }
                if(!$caseAssgnUid && $caseUserId == SES_ID) {
                    $asgnShortName = '<span style="color:#E0814E">me</span>';
                    $asgnName = "";
                }elseif(!$caseAssgnUid) {
                    $usrAsgn = $cq->getUserDtlsArr($caseUserId,$usrDtlsArr);
                    $asgnName = $frmt->formatText($usrAsgn['User']['name']);
                    $asgnShortName = $frmt->shortLength(ucfirst($usrAsgn['User']['name']),10);
                }
                $caseAll[$caseKey]['Easycase']['asgnName'] = $asgnName;
                $caseAll[$caseKey]['Easycase']['asgnShortName'] = $asgnShortName;
                //assign info end

                if($caseTypeId == 10 || $caseLegend == 3 || $caseLegend == 5) {
                    $caseDueDate = $getdata['Easycase']['due_date'];
                    if($caseDueDate != "NULL" && $caseDueDate != "0000-00-00" && $caseDueDate != "" && $caseDueDate != "1970-01-01") {
                        $csDuDtFmtT = $dt->facebook_datestyle($caseDueDate);
                        $csDuDtFmt = $dt->dateFormatOutputdateTime_day($caseDueDate,$curCreated,'week');
                    }else {
                        $csDuDtFmtT = '';
                        $csDuDtFmt = 'No Due Date';
                    }
                }else {
                    $caseDueDate = $getdata['Easycase']['due_date'];
                    if($caseDueDate != "NULL" && $caseDueDate != "0000-00-00" && $caseDueDate != "" && $caseDueDate != "1970-01-01") {
                        if($caseDueDate < $curdtT) {
                            $csDuDtFmtT = $dt->facebook_datestyle($caseDueDate);
                            $csDuDtFmt = '<span class="over-due">Overdue</span>';
                        }else {
                            $csDuDtFmtT = $dt->facebook_datestyle($caseDueDate);
                            $csDuDtFmt = $dt->dateFormatOutputdateTime_day($caseDueDate,$curCreated,'week');
                        }
                    }else {
                        $csDuDtFmtT = '';
                        $csDuDtFmt = '<span class="set-due-dt">Set Due Dt</span>';
                    }
                }
                $caseAll[$caseKey]['Easycase']['csDuDtFmtT'] = $csDuDtFmtT;
                $caseAll[$caseKey]['Easycase']['csDuDtFmt'] = $csDuDtFmt;
                $caseAll[$caseKey]['Easycase']['title'] = htmlentities($frmt->shortLength($frmt->formatText(ucfirst($frmt->convert_ascii($frmt->longstringwrap($getdata['Easycase']['title'])))),50),ENT_QUOTES,'UTF-8');
                $repeatLastUid = $getlastUid;
                $repeatAssgnUid = $caseAssgnUid;
                $repeatcaseTypeId = $caseTypeId;
                $chkDateTime = $newdate[0];
                $chkDateTime1 = $newdate_actualdate[0];
                $projIdcnt=$newpjcnt;
                unset(
                        $caseAll[$caseKey]['Easycase']['updated_by'],
                        $caseAll[$caseKey]['Easycase']['message'],
                        $caseAll[$caseKey]['Easycase']['hours'],
                        $caseAll[$caseKey]['Easycase']['completed_task'],
                        $caseAll[$caseKey]['Easycase']['due_date'],
                        $caseAll[$caseKey]['Easycase']['istype'],
                        $caseAll[$caseKey]['Easycase']['status'],
                        $caseAll[$caseKey]['Easycase']['dt_created'],
                        $caseAll[$caseKey]['Easycase']['actual_dt_created'],
                        $caseAll[$caseKey]['Easycase']['reply_type'],
                        $caseAll[$caseKey]['Easycase']['id_seq'],
                        $caseAll[$caseKey]['Easycase']['end_date'],
                        $caseAll[$caseKey]['Easycase']['Mproject_id'],
                        $caseAll[$caseKey][0],
                        $caseAll[$caseKey]['User']
                );
            }
            $retarr[$taskkey]=$caseAll;
        }
        //}

//		if($caseMenuFilters == "milestone" && count($milestones)) {
//			foreach($milestones AS $key =>$ms){
//				if(!$ms['totalcases']){
//					$endDate = $ms['end_date']." ".$curTime;
//					$days = $dt->dateDiff($endDate,$curCreated);
//					
//					$milestones[$key]['days_diff'] = $days;
//					
//					$mlstDT = $dt->dateFormatOutputdateTime_day($ms['end_date'],GMT_DATETIME,'week');
//					$milestones[$key]['mlstDT'] = $mlstDT;
//					$milestones[$key]['intEndDate'] = strtotime($ms['end_date']);
//				} else {
//					unset(
//						$milestones[$key]['title'],
//						$milestones[$key]['uinq_id'],
//						$milestones[$key]['isactive'],
//						$milestones[$key]['user_id']
//					);
//				}
//				
//				unset(
//					$milestones[$key]['end_date']
//				);
//			}
//		}	
        //return array('caseAll' => $caseAll, 'milestones' => $milestones);
        return $retarr;
    }
    function formatReplies($sqlcasedata, $allUserArr, $frmt, $cq, $tz, $dt) {
        $CSrepcount=0;
        foreach($sqlcasedata as $caseKey => $getdata) {
            $caseDtUid = $getdata['Easycase']['user_id'];
            $csUsrDtlArr = $cq->getUserDtlsArr($caseDtUid,$allUserArr);
            $by_photo = $csUsrDtlArr['User']['photo'];

            $csUsrDtlArr['User']['photo_exist'] = 0;
            if(trim($by_photo)) {
                $csUsrDtlArr['User']['photo_exist'] = 1;//$frmt->pub_file_exists(DIR_USER_PHOTOS_S3_FOLDER,$by_photo);
            }

            $sqlcasedata[$caseKey]['Easycase']['userArr'] = $csUsrDtlArr;
            $getdata['Easycase']['message'] = preg_replace('/<script.*>.*<\/script>/ims', '', $frmt->convert_ascii($getdata['Easycase']['message']));
            if($getdata['Easycase']['legend']==6) {
                $sqlcasedata[$caseKey]['Easycase']['wrap_msg']='';
            }else {
                if($getdata['Easycase']['message']) {
                    $CSrepcount ++;
                }
                $sqlcasedata[$caseKey]['Easycase']['wrap_msg'] = $frmt->html_wordwrap($frmt->formatCms($getdata['Easycase']['message']),75);
            }
            $caseDtId = $getdata['Easycase']['id'];
            $rplyFilesArr = $this->getCaseFiles($caseDtId);
            foreach($rplyFilesArr as $fkey => $getFiles) {
                $caseFileName = $getFiles['CaseFile']['file'];

                $rplyFilesArr[$fkey]['CaseFile']['is_exist'] = 0;
                if(trim($caseFileName)) {
                    $rplyFilesArr[$fkey]['CaseFile']['is_exist'] = 1;//$frmt->pub_file_exists(DIR_CASE_FILES_S3_FOLDER,$caseFileName);
                }

                if(stristr($getFiles['CaseFile']['downloadurl'],'www.dropbox.com')) {
                    $rplyFilesArr[$fkey]['CaseFile']['format_file'] = 'db';//'<img src="'.HTTP_IMAGES.'images/db16x16.png" alt="Dropbox" title="'.$caseFileName.'" width="16" height="16" border="0" style="border:0px solid #C3C3C3" />';
                } elseif(stristr($getFiles['CaseFile']['downloadurl'],'docs.google.com')) {
                    $rplyFilesArr[$fkey]['CaseFile']['format_file'] = 'gd';//'<img src="'.HTTP_IMAGES.'images/gd16x16.png" alt="Google" title="'.$caseFileName.'" width="16" height="16" border="0" style="border:0px solid #C3C3C3" />';
                } else {
                    $rplyFilesArr[$fkey]['CaseFile']['format_file'] = substr(strrchr(strtolower($caseFileName), "."), 1);//str_replace(array('"','\''), array('\'','"'), $frmt->imageType($caseFileName,25,10,1));
                }
                $rplyFilesArr[$fkey]['CaseFile']['is_ImgFileExt'] = $frmt->validateImgFileExt($caseFileName);

                if($rplyFilesArr[$fkey]['CaseFile']['is_ImgFileExt']) {
                     if(USE_S3 == 0){
                        $rplyFilesArr[$fkey]['CaseFile']['fileurl'] = HTTP_CASE_FILES.$caseFileName;
                    }else{
                        $rplyFilesArr[$fkey]['CaseFile']['fileurl'] = $frmt->generateTemporaryURL(DIR_CASE_FILES_S3.$caseFileName);
                    }
                }

                //$rplyFilesArr[$fkey]['CaseFile']['file_shname'] = $frmt->shortLength($caseFileName,37);
                $rplyFilesArr[$fkey]['CaseFile']['file_size'] = $frmt->getFileSize($getFiles['CaseFile']['file_size']);
            }
            $sqlcasedata[$caseKey]['Easycase']['rply_files'] = $rplyFilesArr;

            $caseReplyType = $getdata['Easycase']['reply_type'];
            $caseDtMsg = $getdata['Easycase']['message'];
            $caseDtLegend = $getdata['Easycase']['legend'];
            $caseAssignTo = $getdata['Easycase']['assign_to'];
            $taskhourspent = $getdata['Easycase']['hours'];
            $taskcompleted = $getdata['Easycase']['completed_task'];

            $replyCap = '';
            $asgnTo = '';
            $sts = '';
            $hourspent = '';
            $completed = '';
            if($caseReplyType == 0 && $caseDtMsg != '') {
                if($caseDtLegend == 1) {
                    $sts = '<b class="new">New</b>';
                } elseif($caseDtLegend == 2 || $caseDtLegend == 4) {
                    $sts = '<b class="wip">In Progress</b>';
                } elseif($caseDtLegend == 3) {
                    $sts = '<b class="closed">Closed</b>';
                } elseif($caseDtLegend == 5) {
                    $sts = '<b class="resolved">Resolved</b>';
                }

                $userArr1 = $cq->getUserDtlsArr($caseAssignTo,$allUserArr);

                $by_id1 = $userArr1['User']['id'];
                $by_email1 = $userArr1['User']['email'];
                $by_name_assign1 = $userArr1['User']['name'];
                $by_photo1 = $userArr1['User']['photo'];
                $short_name_assign1 = $userArr1['User']['short_name'];
                //$replyCap .= ',&nbsp;&nbsp;Assigned To: <font color="black">'.$by_name_assign1.'('.$short_name_assign1.')</font>';
                $asgnTo = $by_name_assign1;//.' ('.$short_name_assign1.')';

                if($taskhourspent != "0.0") {
                    $hourspent = $taskhourspent;
                }

                if($taskcompleted != "0") {
                    $completed = $taskcompleted;
                }

                //$replyCap .= '<br />';
            }

            if($caseReplyType == 0 && ($caseDtMsg == '' || $caseDtLegend==6)) {
                if($caseDtLegend == 3) {
                    $replyCap = '<b class="closed">Closed</b> the Task';
                } elseif($caseDtLegend == 4) {
                    $replyCap = '<b class="wip">Started</b> the Task';
                } elseif($caseDtLegend == 5) {
                    $replyCap = '<b class="resolved">Resolved</b> the Task';
                }elseif($caseDtLegend == 6) {
                    $replyCap = '<b class="resolved">Modified</b> the Task';
                }
            } else {
                if($caseReplyType == 1) {
                    $caseDtTyp = $getdata['Easycase']['type_id'];
                    $prjtype_name = $cq->getTypeArr($caseDtTyp,$GLOBALS['TYPE']);
                    $name = $prjtype_name['Type']['name'];
                    $sname = $prjtype_name['Type']['short_name'];
                    $image = $frmt->todo_typ($sname,$name);
                    $replyCap = 'Task Type changed to '.$image.' <b>'.$name.'</b>';
                } elseif($caseReplyType == 2) {
                    $userArr = $cq->getUserDtlsArr($caseAssignTo,$allUserArr);
                    $by_id = $userArr['User']['id'];
                    $by_email = $userArr['User']['email'];
                    $by_name_assign = $userArr['User']['name'];
                    $by_last_name_assign = $userArr['User']['last_name'];
                    $by_photo = $userArr['User']['photo'];
                    //$short_name_assign = $userArr['User']['short_name'];
                    $replyCap = 'Task re-assigned to <b class="ttc">'.$by_name_assign.' '.$by_last_name_assign.'</b>';
                } elseif($caseReplyType == 3) {
                    $caseDtDue = $getdata['Easycase']['due_date'];
                    $curCreated = $tz->GetDateTime(SES_TIMEZONE,TZ_GMT,TZ_DST,TZ_CODE,GMT_DATETIME,"datetime");
                    if($caseDtDue != "NULL" && $caseDtDue != "0000-00-00" && $caseDtDue != "" && $caseDtDue != "1970-01-01") {
                        $due_date = $dt->dateFormatOutputdateTime_day($caseDtDue,$curCreated,'week');
                        $replyCap = 'Due Date changed to <b>'.$due_date.'</b>';
                    } else {
                        $replyCap = 'Due Date: <i>No Due Date</i>';
                    }
                }elseif($caseReplyType == 4) {
                    $casePriority = $getdata['Easycase']['priority'];
                    if($casePriority == 0) {
                        $replyCap = 'Priority changed to <b class="pr_high">High</b><br/>';
                    }elseif($casePriority == 1) {
                        $replyCap = 'Priority changed to <b class="pr_medium">Medium</b><br/>';
                    }elseif($casePriority == 2) {
                        $replyCap = 'Priority changed to <b class="pr_low">Low</b><br/>';
                    }
                }
            }
            $sqlcasedata[$caseKey]['Easycase']['sts'] = $sts;
            $sqlcasedata[$caseKey]['Easycase']['asgnTo'] = $asgnTo;
            $sqlcasedata[$caseKey]['Easycase']['hourspent'] = $hourspent;
            $sqlcasedata[$caseKey]['Easycase']['completed'] = $completed;
            $sqlcasedata[$caseKey]['Easycase']['replyCap'] = $replyCap;
            $caseDtActdT = $getdata['Easycase']['dt_created'];
            //$updated_by = $getdata['Easycase']['updated_by'];
            $replyDt = $tz->GetDateTime(SES_TIMEZONE,TZ_GMT,TZ_DST,TZ_CODE,$caseDtActdT,"datetime");
            $curDate = $tz->GetDateTime(SES_TIMEZONE,TZ_GMT,TZ_DST,TZ_CODE,GMT_DATETIME,"date");
            if($caseDtUid == SES_ID && 0) {
                $usrName = "me";
            }else {
                $usrName = $csUsrDtlArr['User']['name'];
            }
            $sqlcasedata[$caseKey]['Easycase']['usrName'] = $usrName;
            $sqlcasedata[$caseKey]['Easycase']['rply_dt'] = $dt->dateFormatOutputdateTime_day($replyDt,$curDate);

            unset(
                    //$sqlcasedata[$caseKey]['Easycase']['uniq_id'],
                    $sqlcasedata[$caseKey]['Easycase']['case_no'],
                    $sqlcasedata[$caseKey]['Easycase']['case_count'],
                    $sqlcasedata[$caseKey]['Easycase']['updated_by'],
                    $sqlcasedata[$caseKey]['Easycase']['type_id'],
                    $sqlcasedata[$caseKey]['Easycase']['priority'],
                    $sqlcasedata[$caseKey]['Easycase']['title'],
                    $sqlcasedata[$caseKey]['Easycase']['reply_type'],
                    $sqlcasedata[$caseKey]['Easycase']['assign_to'],
                    $sqlcasedata[$caseKey]['Easycase']['completed_task'],
                    $sqlcasedata[$caseKey]['Easycase']['hours'],
                    $sqlcasedata[$caseKey]['Easycase']['due_date'],
                    $sqlcasedata[$caseKey]['Easycase']['istype'],
                    $sqlcasedata[$caseKey]['Easycase']['status'],
                    $sqlcasedata[$caseKey]['Easycase']['isactive'],
                    $sqlcasedata[$caseKey]['Easycase']['dt_created'],
                    $sqlcasedata[$caseKey]['Easycase']['actual_dt_created'],
                    $sqlcasedata[$caseKey]['Easycase']['caseReplyType'],
                    $sqlcasedata[$caseKey]['Easycase']['userArr']['User']['id'],
                    $sqlcasedata[$caseKey]['Easycase']['userArr']['User']['email'],
                    $sqlcasedata[$caseKey]['Easycase']['userArr']['User']['istype']
            );
        }
        $arr['CSrepcount']=$CSrepcount;
        $arr['sqlcasedata']=$sqlcasedata;
        //$sqlcasedata['CSrepcount']=$CSrepcount;
        //return $sqlcasedata;
        return $arr;
    }

    //From CasequeryHelper.php
    function getMilestoneName($caseid) {
        $Milestone = ClassRegistry::init('Milestone');
        $Milestone->recursive = -1;

        $milestones = $Milestone->query("SELECT Milestone.title as title FROM milestones as Milestone,easycase_milestones AS EasycaseMilestone WHERE EasycaseMilestone.milestone_id=Milestone.id AND EasycaseMilestone.easycase_id='".$caseid."'");
        if(isset($milestones['0']['Milestone']['title']) && $milestones['0']['Milestone']['title']) {
            return $milestones['0']['Milestone']['title'];
        }
        else {
            return false;
        }
    }
    function getCaseFiles($cid) {
        App::import('Model','CaseFile');
        $CaseFile = new CaseFile();
        $CaseFile->recursive = -1;
        $caseFiles = $CaseFile->find('all', array('conditions'=>array('CaseFile.easycase_id' => $cid,'CaseFile.comment_id' => 0,'CaseFile.isactive' => 1), 'fields'=>array('CaseFile.id','CaseFile.file','CaseFile.file_size','CaseFile.downloadurl'), 'order' => array('CaseFile.file ASC')));
        return $caseFiles;
    }
    function getAllCaseFiles($pid, $cno) {
        if(!$pid || !$cno) return false;

        App::import('Model','CaseFile');
        $CaseFile = new CaseFile();
        $CaseFile->bindModel(array(
                'belongsTo' => array(
                        'Easycase' => array(
                                'className' => 'Easycase',
                                'foreignKey' => 'easycase_id'
                        )
                )
                ), false);
        $filesArr = $CaseFile->find('all', array('conditions'=>array('Easycase.project_id'=>$pid, 'Easycase.case_no'=>$cno,'CaseFile.isactive' => 1),'fields'=>array('CaseFile.id','CaseFile.file','CaseFile.file_size','CaseFile.downloadurl','Easycase.actual_dt_created'), 'order' => array('Easycase.actual_dt_created DESC','CaseFile.file ASC')));
        return $filesArr;
    }
    function formatFiles($filesArr, $frmt, $tz, $dt) {
        if($filesArr) {
            $curDateTz = $tz->GetDateTime(SES_TIMEZONE,TZ_GMT,TZ_DST,TZ_CODE,GMT_DATETIME,"datetime");

            foreach($filesArr as $fkey => $getFiles) {
                $caseFileName = $getFiles['CaseFile']['file'];

                $filesArr[$fkey]['CaseFile']['is_exist'] = 0;
                if(trim($caseFileName)) {
                    $filesArr[$fkey]['CaseFile']['is_exist'] = 1;
                }

                $downloadurl = $getFiles['CaseFile']['downloadurl'];
                if(isset($downloadurl) && trim($downloadurl)) {
                    if(stristr($downloadurl,'www.dropbox.com')) {
                        $filesArr[$fkey]['CaseFile']['format_file'] = 'db';//'<img src="'.HTTP_IMAGES.'images/db16x16.png" alt="Dropbox" title="'.$caseFileName.'" width="16" height="16" border="0" style="border:0px solid #C3C3C3" />';
                    } else {
                        $filesArr[$fkey]['CaseFile']['format_file'] = 'gd';//'<img src="'.HTTP_IMAGES.'images/gd16x16.png" alt="Google" title="'.$caseFileName.'" width="16" height="16" border="0" style="border:0px solid #C3C3C3" />';
                    }
                }else {
                    $filesArr[$fkey]['CaseFile']['format_file'] = substr(strrchr(strtolower($caseFileName), "."), 1); //str_replace(array('"','\''), array('\'','"'), $frmt->imageType($caseFileName,25,10,1));
                    $filesArr[$fkey]['CaseFile']['is_ImgFileExt'] = $frmt->validateImgFileExt($caseFileName);
                    if($filesArr[$fkey]['CaseFile']['is_ImgFileExt']) {
                        $filesArr[$fkey]['CaseFile']['fileurl'] = $frmt->generateTemporaryURL(DIR_CASE_FILES_S3.$caseFileName);
                    }
                    $filesArr[$fkey]['CaseFile']['file_size'] = $frmt->getFileSize($getFiles['CaseFile']['file_size']);
                }

                $caseDtActdT = $getFiles['Easycase']['actual_dt_created'];
                $replyDt = $tz->GetDateTime(SES_TIMEZONE,TZ_GMT,TZ_DST,TZ_CODE,$caseDtActdT,"datetime");
                $filesArr[$fkey]['CaseFile']['file_date'] = $dt->dateFormatOutputdateTime_day($replyDt,$curDateTz);
            }
        }
        return $filesArr;
    }
    function getUserEmail($id) {
        $CaseUserEmail = ClassRegistry::init('CaseUserEmail');
        $CaseUserEmail->recursive = -1;
        $userIds = $CaseUserEmail->find('all', array('conditions'=>array('CaseUserEmail.easycase_id' => $id,'CaseUserEmail.ismail'=>1), 'fields'=>array('CaseUserEmail.user_id')));
        return $userIds;
    }
    //End CasequeryHelper.php

    //From FormatComponent.php
    function getMemebers($projId,$type=NULL) {
        $ProjectUser = ClassRegistry::init('ProjectUser');

        if($projId == 'all') {
            $quickMem = $ProjectUser->query("SELECT DISTINCT User.id,User.uniq_id, User.name, User.email, User.istype,User.short_name FROM users as User,project_users as ProjectUser,company_users as CompanyUser WHERE CompanyUser.user_id=ProjectUser.user_id AND CompanyUser.is_active='1' AND CompanyUser.company_id='".SES_COMP."'  AND User.isactive='1' AND ProjectUser.user_id=User.id ORDER BY User.name");
        }
        else {
            $quickMem = $ProjectUser->query("SELECT DISTINCT User.id,User.uniq_id, User.name, User.email, User.istype,User.short_name FROM users as User,project_users as ProjectUser,company_users as CompanyUser,projects as Project WHERE CompanyUser.user_id=ProjectUser.user_id AND CompanyUser.is_active='1' AND CompanyUser.company_id='".SES_COMP."' AND Project.uniq_id='".$projId."' AND Project.id=ProjectUser.project_id AND User.isactive='1' AND ProjectUser.user_id=User.id ORDER BY User.name");
        }

        return $quickMem;
    }
    function getMemebersid($projId) {
        $ProjectUser = ClassRegistry::init('ProjectUser');

        //$quickMem = $ProjectUser->find('all', array('conditions' => array('Project.id' => $projId,'User.isactive' => 1,'Project.company_id' => SES_COMP),'fields' => array('DISTINCT User.id','User.name','User.istype','User.email','User.short_name'),'order' => array('User.name')));

        $quickMem = $ProjectUser->query("SELECT DISTINCT User.id,User.uniq_id, User.name, User.last_name, User.email, User.istype,User.short_name, User.photo FROM users as User,project_users as ProjectUser,company_users as CompanyUser WHERE CompanyUser.user_id=ProjectUser.user_id AND CompanyUser.is_active='1' AND CompanyUser.company_id='".SES_COMP."' AND ProjectUser.project_id='".$projId."' AND User.isactive='1' AND ProjectUser.user_id=User.id ORDER BY User.name");

        return $quickMem;
    }
    //End FormatComponent.php
    function getCaseNo($case_uniq_id) {
        return $this->find('first', array('conditions' => array('Easycase.uniq_id' => $case_uniq_id),'fields' => array('Easycase.case_no')));
    }
    function getCaseTitle($project_id, $case_no) {
        $caseTitle = '';
        $csTtl = $this->find('first', array('conditions' => array('Easycase.project_id' => $project_id, 'Easycase.case_no' => $case_no, 'istype' => 1),'fields' => array('Easycase.title')));
        if($csTtl) {
            $caseTitle = $csTtl['Easycase']['title'];
        }
        return $caseTitle;
    }
    function getLastResolved($projId, $caseNo) {
        return $this->find('first', array(
                'conditions' => array('Easycase.project_id' => $projId, 'Easycase.case_no' => $caseNo, 'Easycase.legend' => '5'),
                'fields' => array('Easycase.dt_created'),
                'order' => 'Easycase.dt_created DESC'
                )
        );
    }
    function getLastClosed($projId, $caseNo) {
        return $this->find('first', array(
                'conditions' => array('Easycase.project_id' => $projId, 'Easycase.case_no' => $caseNo, 'Easycase.legend' => '3'),
                'fields' => array('Easycase.dt_created'),
                'order' => 'Easycase.dt_created DESC'
                )
        );
    }
    function getEasycase($case_uniq_id) {
        $thisCase = $this->find('first', array('conditions' => array('Easycase.uniq_id' => $case_uniq_id),'fields' => array('Easycase.id', 'Easycase.case_no', 'Easycase.project_id','Easycase.isactive','Easycase.istype')));

        if($thisCase['Easycase']['istype']!=1) {
            $thisCase = $this->find('first', array('conditions' => array('Easycase.case_no' => $thisCase['Easycase']['case_no'], 'Easycase.project_id' => $thisCase['Easycase']['project_id'], 'istype'=>1),'fields' => array('Easycase.id', 'Easycase.case_no', 'Easycase.project_id','Easycase.isactive')));
        }

        return $thisCase;
    }
    function getTaskUser($projId, $caseNo) {
        if(!$projId || !$caseNo) return false;

        return $this->query("SELECT DISTINCT User.id, User.name, User.last_name, User.email, User.istype,User.short_name,User.photo FROM users as User,easycases as Easycase WHERE (Easycase.user_id=User.id || Easycase.updated_by=User.id || Easycase.assign_to=User.id) AND Easycase.project_id='".$projId."' AND Easycase.case_no='".$caseNo."' AND Easycase.istype IN('1','2') ORDER BY User.short_name");
    }
    /**
     * @method public actionOntask($easycase_id, $caseuid,$type)
     
     * @return JSON
     */
    function actionOntask($caseid,$caseuid,$type) {
        if($caseid) {
            $checkStatus = $this->find('first',array('conditions'=>array('Easycase.id'=>$caseid,'Easycase.uniq_id'=>$caseuid,'Easycase.isactive'=>1)));
            if($checkStatus) {
                if($checkStatus['Easycase']['legend'] == 1) {
                    $status = '<font color="#737373" style="font-weight:bold">Status:</font> <font color="#763532" style="font:normal 12px verdana;">NEW</font>';
                }elseif($checkStatus['Easycase']['legend'] == 4) {
                    $status = '<font color="#737373" style="font-weight:bold">Status:</font> <font color="#55A0C7" style="font:normal 12px verdana;">STARTED</font>';
                }elseif($checkStatus['Easycase']['legend'] == 5) {
                    $status = '<font color="#737373" style="font-weight:bold">Status:</font> <font color="#EF6807" style="font:normal 12px verdana;">RESOLVED</font>';
                }elseif($checkStatus['Easycase']['legend'] == 3) {
                    $status = '<font color="#737373" style="font-weight:bold">Status:</font> <font color="green" style="font:normal 12px verdana;">CLOSED</font>';
                }
                //Action wrt type
                if($type=='start') {
                    $csSts = 1;
                    $csLeg = 4;
                    $acType = 2;
                    $cuvtype = 4;
                    $emailType = "Start";
                    $msg = '<font color="#737373" style="font-weight:bold">Status:</font> <font color="#55A0C7" style="font:normal 12px verdana;">STARTED</font>';
                    $emailbody = '<font color="#55A0C7" style="font:normal 12px verdana;">STARTED</font> the Task.';
                }elseif($type=='resolve') {
                    $csSts = 1;
                    $csLeg = 5;
                    $acType = 3;
                    $cuvtype = 5;
                    $emailType = "Resolve";
                    $msg = '<font color="#737373" style="font-weight:bold">Status:</font> <font color="#EF6807" style="font:normal 12px verdana;">RESOLVED</font>';
                    $emailbody = '<font color="#EF6807" style="font:normal 12px verdana;">RESOLVED</font> the Task.';
                }elseif($type=='close') {
                    $csSts = 2;
                    $csLeg = 3;
                    $acType = 1;
                    $cuvtype = 3;
                    $emailType = "Close";
                    $msg = '<font color="#737373" style="font-weight:bold">Status:</font> <font color="green" style="font:normal 12px verdana;">CLOSED</font>';
                    $emailbody = '<font color="green" style="font:normal 12px verdana;">CLOSED</font> the Task.';
                }elseif($type=='tasktype') {
                    $csSts = 1;
                    $csLeg = 4;
                    $acType = 2;
                    $cuvtype = 4;
                    $emailType = "Change Type";
                    $caseChageType1 =1;
                    $msg = $status;
                    $emailbody = '<font color="#55A0C7" style="font:normal 12px verdana;">changed the type of</font> the Task.';
                }elseif($type=='duedate') {
                    $csSts = 1;
                    $csLeg = 4;
                    $acType = 2;
                    $cuvtype = 4;
                    $emailType = "Change Duedate";
                    $caseChageDuedate1 =3;
                    $msg = $status;
                    $emailbody = '<font color="#55A0C7" style="font:normal 12px verdana;">changed the due date of</font> the Task.';
                }elseif($type=='priority') {
                    $csSts = 1;
                    $csLeg = 4;
                    $acType = 2;
                    $cuvtype = 4;
                    $emailType = "Change Priority";
                    $caseChagePriority1 =2;
                    $msg = $status;
                    $emailbody = '<font color="#55A0C7" style="font:normal 12px verdana;">changed the priority of</font> the Task.';
                }elseif($type=='assignto') {
                    $csSts = 1;
                    $csLeg = 4;
                    $acType = 2;
                    $cuvtype = 4;
                    $emailType = "Change Assignto";
                    $caseChangeAssignto1 =4;
                    $msg = $status;
                    $emailbody = '<font color="#55A0C7" style="font:normal 12px verdana;">changed the assigned to of</font> the Task.';
                }
                $commonAllId = "";
                $caseid_list = $caseid.',';
                $done = 1;
                if($caseChageType1 || $caseChageDuedate1 || $caseChagePriority1 || $caseChangeAssignto1) {
                    //socket.io implement start
                    $Project = ClassRegistry::init('Project');
                    $ProjectUser = ClassRegistry::init('ProjectUser');
                    $ProjectUser->recursive = -1;

                    //$getUser = $ProjectUser->query("SELECT user_id FROM project_users WHERE project_id='".$closeStsPid."'");
                    $actionStsPid = $checkStatus['Easycase']['project_id'];
                    $caseStsNo = $checkStatus['Easycase']['case_no'];
                    $closeStsTitle = $checkStatus['Easycase']['title'];

                    $prjuniq = $Project->query("SELECT uniq_id, short_name FROM projects WHERE id='".$actionStsPid."'");
                    $prjuniqid = $prjuniq[0]['projects']['uniq_id'];
                    $projShName = strtoupper($prjuniq[0]['projects']['short_name']);
                    $channel_name = $prjuniqid;

                    if(channel_name) {
                        $msgpub = 'Updated.~~'.SES_ID.'~~'.$caseStsNo.'~~'.'UPD'.'~~'.$closeStsTitle.'~~'.$projShName;
                        $pub_msg = array('channel' => $channel_name, 'message' => $msgpub);
                    }
                    //socket.io implement end
                }else {
                    $done = 1;
                    $caseDataArr = $checkStatus;
                    if(($caseDataArr['Easycases']['legend']==3) || ($csLeg == 4 && ($caseDataArr['Easycases']['legend'] == 4)) || ($csLeg==5 && ($caseDataArr['Easycases']['legend'] == 5))) {
                        $done = 0;
                    }
                    if($done) {
                        $caseid_list =$caseid.',' ;
                        $caseStsId = $caseDataArr['Easycase']['id'];
                        $caseStsNo = $caseDataArr['Easycase']['case_no'];
                        $closeStsPid = $caseDataArr['Easycase']['project_id'];
                        $closeStsTyp = $caseDataArr['Easycase']['type_id'];
                        $closeStsPri = $caseDataArr['Easycase']['priority'];
                        $closeStsTitle = $caseDataArr['Easycase']['title'];
                        $closeStsUniqId = $caseDataArr['Easycase']['uniq_id'];
                        $caUid = $caseDataArr['Easycase']['assign_to'];

                        $this->query("UPDATE easycases SET case_no='".$caseStsNo."',updated_by='".SES_ID."',case_count=case_count+1, project_id='".$closeStsPid."', type_id='".$closeStsTyp."', priority='".$closeStsPri."', status='".$csSts."', legend='".$csLeg."', dt_created='".GMT_DATETIME."' WHERE id=".$caseStsId." AND isactive='1'");
                        $caseuniqid = md5(uniqid());
                        $this->query("INSERT INTO easycases SET uniq_id='".$caseuniqid."', user_id='".SES_ID."', format='2', istype='2', actual_dt_created='".GMT_DATETIME."', case_no='".$caseStsNo."', project_id='".$closeStsPid."', type_id='".$closeStsTyp."', priority='".$closeStsPri."', status='".$csSts."', legend='".$csLeg."', dt_created='".GMT_DATETIME."'");

                        //socket.io implement start
                        $Project = ClassRegistry::init('Project');
                        $ProjectUser = ClassRegistry::init('ProjectUser');
                        $ProjectUser->recursive = -1;

                        //$getUser = $ProjectUser->query("SELECT user_id FROM project_users WHERE project_id='".$closeStsPid."'");
                        $prjuniq = $Project->query("SELECT uniq_id, short_name FROM projects WHERE id='".$closeStsPid."'");
                        $prjuniqid = $prjuniq[0]['projects']['uniq_id'];//print_r($prjuniq);
                        $projShName = strtoupper($prjuniq[0]['projects']['short_name']);
                        $channel_name = $prjuniqid;
                        $msgpub = 'Updated.~~'.SES_ID.'~~'.$caseStsNo.'~~'.'UPD'.'~~'.$closeStsTitle.'~~'.$projShName;

                        $pub_msg = array('channel' => $channel_name, 'message' => $msgpub);
                        //socket.io implement end
                    }
                }
                $_SESSION['email']['email_body'] = $emailbody;
                $_SESSION['email']['msg'] = $msg;
                $email_notification = array('caseNo'=>$caseStsNo,'closeStsTitle'=>$closeStsTitle,'emailMsg'=>$emailMsg,'closeStsPid'=>$closeStsPid,'closeStsPri'=>$closeStsPri,'closeStsTyp'=>$closeStsTyp,'assignTo'=>$assignTo,'usr_names'=>$usr_names,'caseuniqid'=>$caseuniqid,'csType'=>$emailType,'closeStsPid'=>$closeStsPid,'caseStsId'=>$caseStsId,'caseIstype'=>5,'caseid_list'=> $caseid_list,'caseUniqId'=>$closeStsUniqId);// $caseuniqid
                $arr['succ']=1;
                $arr['msg'] ='Succes';
                $arr['data']= json_encode($email_notification);
                $arr['pub_msg']= $pub_msg;
                return $arr;
            }else {
                $arr['err']= 1;
                $arr['msg'] = 'No Task found with the selected id';
                return $arr;
            }
        }
    }
    /**
     * @method public ajax_milestonelist($data=array()) to retrive the latest 3 Milestone and respective tasks
     
     * @return array()
     */
    function ajax_milestonelist($data=array(),$frmt, $dt, $tz, $cq,$milestone_search='') {
        $milestone_search="AND (Milestone.title LIKE '%$milestone_search%' OR Milestone.description LIKE '%$milestone_search%')";
        $caseStatus = $data['caseStatus']; // Filter by Status(legend)
        $priorityFil = $data['priFil']; // Filter by Priority
        $caseTypes = $data['caseTypes']; // Filter by case Types
        $caseUserId = $data['caseMember']; // Filter by Member
        $caseAssignTo = $data['caseAssignTo']; // Filter by AssignTo
        $caseDate = $data['caseDate']; // Sort by Date
        $caseSrch = $data['caseSearch']; // Search by keyword
        $casePage = $data['casePage']; // Pagination
        $caseUniqId = $data['caseId']; // Case Uniq ID to close a case
        $caseTitle = $data['caseTitle']; // Case Uniq ID to close a case
        $caseDueDate = $data['caseDueDate']; // Sort by Due Date
        $isActive=isset($data['isActive'])?$data['isActive']:1;//to distinguish between active and completed
//               pr($isActive);exit;
        $caseNum = $data['caseNum']; // Sort by Due Date
        $caseLegendsort = $data['caseLegendsort']; // Sort by Case Status
        $caseAtsort = $data['caseAtsort']; // Sort by Case Status
        $startCaseId = $data['startCaseId']; // Start Case
        $caseResolve = $data['caseResolve']; // Resolve Case

        $caseMenuFilters = $data['caseMenuFilters']; // Resolve Case
        $milestoneIds = $data['milestoneIds']; // Resolve Case
        $caseCreateDate = $data['caseCreateDate']; // Sort by Created Date
        @$case_srch = $data['case_srch'];
        @$case_date = $data['case_date'];
        @$case_duedate = $data['case_due_date'];
        @$milestone_type = $data['mstype'];
        $changecasetype = $data['caseChangeType'];
        $caseChangeDuedate =$data['caseChangeDuedate'];
        $caseChangePriority =$data['caseChangePriority'];
        $caseChangeAssignto = $data['caseChangeAssignto'];
        $customfilterid = $data['customfilter'];
        $detailscount = $data['data']['detailscount'];
        $msQuery = "";
        $ispaginate =  $data['ispaginate'];
        $mlimit = isset($data['mlimit'])?$data['mlimit']:0;
        if($ispaginate && $ispaginate=='prev') {
            $mlimit -=(2*MILESTONE_PER_PAGE);
        }elseif($ispaginate =='' && $mlimit) {
            $mlimit -=MILESTONE_PER_PAGE;
        }
        $projUniq = $data['projFil'];
        $projIsChange = $data['projIsChange'];
        if($projUniq !='all') {
            //$prj_cls = ClassRegistry::init('Project');
            $prj_usercls = ClassRegistry::init('ProjectUser');
            $prj_usercls->unbindModel(array('belongsTo' => array('User')));
            $projArr = $prj_usercls->find('first', array('conditions' => array('Project.uniq_id' => $projUniq,'ProjectUser.user_id'=>SES_ID,'Project.isactive'=>1,'ProjectUser.company_id' => SES_COMP),'fields' => array('Project.id','Project.short_name','ProjectUser.id')));
            //$projectDetails = $prj_cls->find('first',array('conditions'=>array('Project.uniq_id'=>$projUniq)));
            if($projArr) {
                //Updating ProjectUser table to current date-time
                if($projIsChange != $projUniq) {
                    $ProjectUser['id'] = $projArr['ProjectUser']['id'];
                    $ProjectUser['dt_visited'] = GMT_DATETIME;
                    $prj_usercls->save($ProjectUser);
                }
            }
            $curProjId = $projArr['Project']['id'];
        }else if($projUniq =='all') {

        }else {
            $projUniq = $GLOBALS['getallproj'][0]['Project']['uniq_id'];
            $curProjId = $GLOBALS['getallproj'][0]['Project']['id'];
        }
        // 3 Milestone wrt Sequence
        $milestone_cls = ClassRegistry::init('Milestone');
        if($projUniq!='all' && trim($projUniq)) {
		$milestones = $milestone_cls->query("SELECT SQL_CALC_FOUND_ROWS `Milestone`.`id`,`Milestone`.`title`,`Milestone`.`project_id`,`Milestone`.`end_date`,`Milestone`.`uniq_id`,`Milestone`.`isactive`,`Milestone`.`user_id`,COUNT(c.easycase_id) AS totalcases,GROUP_CONCAT(c.easycase_id) AS `caseids`  FROM milestones AS `Milestone` LEFT JOIN easycase_milestones AS c ON Milestone.id = c.milestone_id WHERE `Milestone`.`isactive` =".$isActive." AND `Milestone`.`project_id` =".$curProjId." AND `Milestone`.`company_id` = ".SES_COMP." $milestone_search GROUP BY Milestone.id ORDER BY Milestone.end_date ASC LIMIT ".$mlimit.','.MILESTONE_PER_PAGE);
		if(!$milestones) {
			$milestones_all = $milestone_cls->query("SELECT SQL_CALC_FOUND_ROWS `Milestone`.`id`,`Milestone`.`isactive` FROM milestones AS `Milestone` LEFT JOIN easycase_milestones AS c ON Milestone.id = c.milestone_id WHERE `Milestone`.`project_id` =".$curProjId." AND `Milestone`.`company_id` = ".SES_COMP." GROUP BY Milestone.id ORDER BY Milestone.end_date ASC");
		}

//            $milestones = $milestone_cls->find('all',array('conditions'=>array('isactive' =>$isActive,'project_id' =>$curProjId,'company_id' => SES_COMP)));
//        pr($milestones);die;

        }elseif($projUniq=='all') {
         //   echo "SELECT SQL_CALC_FOUND_ROWS `Milestone`.`id`,`Milestone`.`title`,`Milestone`.`project_id`,`Milestone`.`end_date`,`Milestone`.`uniq_id`,`Milestone`.`isactive`,`Milestone`.`user_id`,COUNT(c.easycase_id) AS totalcases,GROUP_CONCAT(c.easycase_id) AS `caseids`  FROM milestones AS `Milestone` LEFT JOIN easycase_milestones AS c ON Milestone.id = c.milestone_id LEFT JOIN projects Project on Project.id=Milestone.project_id  WHERE `Milestone`.`isactive` =".$isActive." AND c.project_id IN (SELECT ProjectUser.project_id FROM project_users AS ProjectUser WHERE ProjectUser.user_id=".SES_ID." AND ProjectUser.project_id=Project.id AND Project.isactive='1' AND ProjectUser.company_id='".SES_COMP."') AND Project.isactive=$isActive AND Milestone.isactive=$isActive AND `Milestone`.`company_id` = ".SES_COMP."  $milestone_search GROUP BY Milestone.id ORDER BY Milestone.end_date ASC LIMIT ".$mlimit.','.MILESTONE_PER_PAGE;exit;
            $milestones = $milestone_cls->query("SELECT SQL_CALC_FOUND_ROWS `Milestone`.`id`,`Milestone`.`title`,`Milestone`.`project_id`,`Milestone`.`end_date`,`Milestone`.`uniq_id`,`Milestone`.`isactive`,`Milestone`.`user_id`,COUNT(c.easycase_id) AS totalcases,GROUP_CONCAT(c.easycase_id) AS `caseids`  FROM milestones AS `Milestone` LEFT JOIN easycase_milestones AS c ON Milestone.id = c.milestone_id LEFT JOIN projects Project on Project.id=Milestone.project_id  WHERE `Milestone`.`isactive` =".$isActive." AND Milestone.project_id IN (SELECT ProjectUser.project_id FROM project_users AS ProjectUser LEFT JOIN projects AS Project ON ProjectUser.project_id=Project.id WHERE ProjectUser.user_id=".SES_ID." AND Project.isactive='1' AND ProjectUser.company_id='".SES_COMP."') AND Milestone.isactive=$isActive AND `Milestone`.`company_id` = ".SES_COMP."  $milestone_search GROUP BY Milestone.id ORDER BY Milestone.end_date ASC LIMIT ".$mlimit.','.MILESTONE_PER_PAGE);
         //   echo "SELECT SQL_CALC_FOUND_ROWS `Milestone`.`id`,`Milestone`.`title`,`Milestone`.`project_id`,`Milestone`.`end_date`,`Milestone`.`uniq_id`,`Milestone`.`isactive`,`Milestone`.`user_id`,COUNT(c.easycase_id) AS totalcases,GROUP_CONCAT(c.easycase_id) AS `caseids`  FROM milestones AS `Milestone` LEFT JOIN easycase_milestones AS c ON Milestone.id = c.milestone_id LEFT JOIN projects Project on Project.id=Milestone.project_id  WHERE `Milestone`.`isactive` =".$isActive." AND Milestone.project_id IN (SELECT ProjectUser.project_id FROM project_users AS ProjectUser LEFT JOIN projects AS Project ON ProjectUser.project_id=Project.id WHERE ProjectUser.user_id=".SES_ID." AND Project.isactive='1' AND ProjectUser.company_id='".SES_COMP."') AND Milestone.isactive=$isActive AND `Milestone`.`company_id` = ".SES_COMP."  $milestone_search GROUP BY Milestone.id ORDER BY Milestone.end_date ASC LIMIT ".$mlimit.','.MILESTONE_PER_PAGE ; exit;
			if(!$milestones) {
				$milestones_all = $milestone_cls->query("SELECT SQL_CALC_FOUND_ROWS `Milestone`.`id`,`Milestone`.`isactive` FROM milestones AS `Milestone` LEFT JOIN easycase_milestones AS c ON Milestone.id = c.milestone_id WHERE `Milestone`.`company_id` = ".SES_COMP." GROUP BY Milestone.id ORDER BY Milestone.end_date ASC");
			}
        }        
        $totmlst = $milestone_cls->query("SELECT FOUND_ROWS() as mtotal");
        $resCaseProj['totalMlstCnt'] = $totmlst[0][0]['mtotal'];
        $resCaseProj['mlimit'] = $mlimit + MILESTONE_PER_PAGE;
        
        //$milestones = $milestone_cls->find('all',array('conditions'=>array('Milestone.project_id'=>$curProjId),'order'=>array('id_seq ASC, end_date DESC'),'limit'=>'3'));
        if($milestones) {
            $milestone_ids = '';
            foreach($milestones AS $keys =>$values) {
                $milestone_ids .="'".$values['Milestone']['id']."', " ;
            }
            $milestone_ids = trim($milestone_ids,', ');
            $mstype = isset($data['msType'])?$data['msType']:1;

            if($projUniq) {
                if($projUniq != 'all') {
                    $caseAll = $this->query("SELECT SQL_CALC_FOUND_ROWS Easycase.*,User.short_name,IF((Easycase.assign_to = 0 OR Easycase.assign_to =".SES_ID."),'Me',User.short_name) AS Assigned FROM ( SELECT Easycase.*,EasycaseMilestone.id AS Emid, EasycaseMilestone.milestone_id AS Em_milestone_id,EasycaseMilestone.user_id AS Em_user_id,EasycaseMilestone.id_seq,Milestone.id as Mid,Milestone.title AS Mtitle ,Milestone.end_date,Milestone.isactive AS Misactive,Milestone.project_id AS Mproject_id,Milestone.uniq_id AS Muinq_id FROM easycases as Easycase,easycase_milestones AS EasycaseMilestone,milestones AS Milestone WHERE EasycaseMilestone.easycase_id=Easycase.id $milestone_search AND Milestone.id=EasycaseMilestone.milestone_id AND Easycase.istype='1' AND Easycase.isactive=1 AND Milestone.isactive=".$isActive." AND Milestone.id IN(".$milestone_ids.") AND Easycase.project_id='$curProjId' AND Easycase.project_id!=0  AND EasycaseMilestone.easycase_id=Easycase.id AND EasycaseMilestone.project_id=".$curProjId.$msQuery." ) AS Easycase LEFT JOIN users User ON Easycase.assign_to=User.id ORDER BY Easycase.end_date ASC,Easycase.Mtitle ASC ");
                }
                if($projUniq == 'all') {
                    //echo "SELECT SQL_CALC_FOUND_ROWS Easycase.*,User.short_name,IF((Easycase.assign_to = 0 OR Easycase.assign_to =".SES_ID."),'Me',User.short_name) AS Assigned FROM ( SELECT  Easycase.*,EasycaseMilestone.id AS Emid, EasycaseMilestone.milestone_id AS Em_milestone_id,EasycaseMilestone.user_id AS Em_user_id,EasycaseMilestone.id_seq,Milestone.id as Mid,Milestone.title AS Mtitle ,Milestone.end_date,Milestone.isactive AS Misactive,Milestone.project_id AS Mproject_id,Milestone.uniq_id AS Muinq_id FROM easycases as Easycase,easycase_milestones AS EasycaseMilestone,milestones AS Milestone WHERE EasycaseMilestone.easycase_id=Easycase.id AND Milestone.id=EasycaseMilestone.milestone_id AND Easycase.istype='1' AND Easycase.isactive=1 AND Milestone.isactive=".$mstype." AND Milestone.id IN(".$milestone_ids.") AND Easycase.project_id!=0 AND Easycase.project_id IN (SELECT ProjectUser.project_id FROM project_users AS ProjectUser,projects as Project WHERE ProjectUser.user_id=".SES_ID." AND ProjectUser.project_id=Project.id AND Project.isactive='1' AND ProjectUser.company_id='".SES_COMP."') ".$searchcase." ".trim($qry)." AND EasycaseMilestone.easycase_id=Easycase.id AND EasycaseMilestone.project_id IN (SELECT ProjectUser.project_id FROM project_users AS ProjectUser,projects as Project WHERE ProjectUser.user_id=".SES_ID." AND ProjectUser.project_id=Project.id AND Project.isactive='1')".$msQuery." ) AS Easycase LEFT JOIN users User ON Easycase.assign_to=User.id ORDER BY Easycase.end_date ASC,Easycase.Mtitle ASC";exit;
                    $caseAll = $this->query("SELECT SQL_CALC_FOUND_ROWS Easycase.*,User.short_name,IF((Easycase.assign_to = 0 OR Easycase.assign_to =".SES_ID."),'Me',User.short_name) AS Assigned FROM ( SELECT  Easycase.*,EasycaseMilestone.id AS Emid, EasycaseMilestone.milestone_id AS Em_milestone_id,EasycaseMilestone.user_id AS Em_user_id,EasycaseMilestone.id_seq,Milestone.id as Mid,Milestone.title AS Mtitle ,Milestone.end_date,Milestone.isactive AS Misactive,Milestone.project_id AS Mproject_id,Milestone.uniq_id AS Muinq_id FROM easycases as Easycase,easycase_milestones AS EasycaseMilestone,milestones AS Milestone WHERE EasycaseMilestone.easycase_id=Easycase.id $milestone_search AND Milestone.id=EasycaseMilestone.milestone_id AND Easycase.istype='1' AND Easycase.isactive=1 AND Milestone.isactive=".$isActive." AND Milestone.id IN(".$milestone_ids.") AND Easycase.project_id!=0 AND Easycase.project_id IN (SELECT ProjectUser.project_id FROM project_users AS ProjectUser,projects as Project WHERE ProjectUser.user_id=".SES_ID." AND ProjectUser.project_id=Project.id AND Project.isactive='1' AND ProjectUser.company_id='".SES_COMP."') ".$searchcase." ".trim($qry)." AND EasycaseMilestone.easycase_id=Easycase.id AND EasycaseMilestone.project_id IN (SELECT ProjectUser.project_id FROM project_users AS ProjectUser,projects as Project WHERE ProjectUser.user_id=".SES_ID." AND ProjectUser.project_id=Project.id AND Project.isactive='1')".$msQuery." ) AS Easycase LEFT JOIN users User ON Easycase.assign_to=User.id ORDER BY Easycase.end_date ASC,Easycase.Mtitle ASC");
                }
                $tot = $this->query("SELECT FOUND_ROWS() as total");
                $CaseCount = $tot[0][0]['total'];
                $msQ = "";
                if($projUniq != 'all') {
                    foreach($milestones as $mls) {
                        $mid.= $mls['Milestone']['id'].',';
                        $m[$mls['Milestone']['id']]['id'] = $mls['Milestone']['id'];
                        $m[$mls['Milestone']['id']]['caseids'] =$mls[0]['caseids'];
                        $m[$mls['Milestone']['id']]['totalcases'] = $mls[0]['totalcases'];
                        $m[$mls['Milestone']['id']]['title'] = $mls['Milestone']['title'];
                        $m[$mls['Milestone']['id']]['project_id'] = $mls['Milestone']['project_id'];
                        $m[$mls['Milestone']['id']]['end_date'] = $mls['Milestone']['end_date'];
                        $m[$mls['Milestone']['id']]['uinq_id'] = $mls['Milestone']['uniq_id'];
                        $m[$mls['Milestone']['id']]['isactive'] = $mls['Milestone']['isactive'];
                        $m[$mls['Milestone']['id']]['user_id'] = $mls['Milestone']['user_id'];
                    }
                    $c = array();
                    if($mid) {
                        $closed_cases = $this->query("SELECT EasycaseMilestone.milestone_id,COUNT(Easycase.id) as totcase FROM easycase_milestones AS EasycaseMilestone LEFT JOIN easycases as Easycase ON   EasycaseMilestone.easycase_id=Easycase.id WHERE Easycase.istype='1' AND Easycase.isactive='1' AND Easycase.legend='3' AND EasycaseMilestone.milestone_id IN(".trim($mid,',').") GROUP BY  EasycaseMilestone.milestone_id");
                        foreach($closed_cases as $key=>$val) {
                            $c[$val['EasycaseMilestone']['milestone_id']]['totalclosed'] = $val[0]['totcase'];
                        }
                    }
                    $resCaseProj['milestones'] = $m;
                }
                if($projUniq == 'all') {
                    $cond = array('conditions'=>array('ProjectUser.user_id' => SES_ID,'ProjectUser.company_id' => SES_COMP,'Project.isactive' => 1), 'fields' => array('DISTINCT  Project.id'),'order'=>array('ProjectUser.dt_visited DESC'));
                    $mid ='';
                    foreach($milestones as $k=>$v) {
                        $mid.= $v['Milestone']['id'].',';
                        $m[$v['Milestone']['id']]['id'] = $v['Milestone']['id'];
                        $m[$v['Milestone']['id']]['caseids'] =$v[0]['caseids'];
                        $m[$v['Milestone']['id']]['totalcases'] = $v[0]['totalcases'];
                        $m[$v['Milestone']['id']]['title'] = $v['Milestone']['title'];
                        $m[$v['Milestone']['id']]['project_id'] = $v['Milestone']['project_id'];
                        $m[$v['Milestone']['id']]['end_date'] = $v['Milestone']['end_date'];
                        $m[$v['Milestone']['id']]['uinq_id'] = $v['Milestone']['uniq_id'];
                        $m[$v['Milestone']['id']]['isactive'] = $v['Milestone']['isactive'];
                        $m[$v['Milestone']['id']]['user_id'] = $v['Milestone']['user_id'];
                    }
                    $c = array();
                    if($mid) {
                        $closed_cases = $this->query("SELECT EasycaseMilestone.milestone_id,COUNT(Easycase.id) as totcase FROM easycase_milestones AS EasycaseMilestone LEFT JOIN easycases as Easycase ON   EasycaseMilestone.easycase_id=Easycase.id WHERE Easycase.istype='1' AND Easycase.isactive='1' AND Easycase.legend='3' AND EasycaseMilestone.milestone_id IN (".trim($mid,',').") GROUP BY  EasycaseMilestone.milestone_id");
                        foreach($closed_cases as $key=>$val) {
                            $c[$val['EasycaseMilestone']['milestone_id']]['totalclosed'] = $val[0]['totcase'];
                        }
                    }
                    $resCaseProj['milestones'] = $m;
                }
                $ProjectUser = ClassRegistry::init('ProjectUser');
                if($projUniq != 'all') {
                    $usrDtlsAll = $ProjectUser->query("SELECT DISTINCT User.id, User.name, User.email, User.istype,User.email,User.short_name,User.photo FROM users as User,easycases as Easycase WHERE (Easycase.user_id=User.id || Easycase.updated_by=User.id || Easycase.assign_to=User.id) AND Easycase.project_id='".$curProjId."' AND Easycase.isactive='1' AND Easycase.istype IN('1','2') ORDER BY User.short_name");
                }else {
                    $usrDtlsAll = $ProjectUser->query("SELECT DISTINCT User.id, User.name, User.email, User.istype,User.email,User.short_name,User.photo FROM users as User,easycases as Easycase WHERE (Easycase.user_id=User.id || Easycase.updated_by=User.id || Easycase.assign_to=User.id) AND Easycase.project_id IN (SELECT ProjectUser.project_id FROM project_users AS ProjectUser,projects as Project WHERE ProjectUser.user_id=".SES_ID." AND ProjectUser.project_id=Project.id AND Project.isactive='1' AND ProjectUser.company_id='".SES_COMP."') AND Easycase.isactive='1' AND Easycase.istype IN('1','2') ORDER BY User.short_name");
                }
                $usrDtlsArr = array();
                $usrDtlsPrj = array();
                foreach($usrDtlsAll as $ud) {
                    $usrDtlsArr[$ud['User']['id']] = $ud;
                }
                $resCaseProj['page_limit'] = $page_limit;
                $resCaseProj['csPage'] = $casePage;
                $resCaseProj['caseUrl'] = $caseUrl;
                $resCaseProj['projUniq'] = $projUniq;
                $resCaseProj['csdt'] = $caseDate;
                $resCaseProj['csTtl'] = $caseTitle;
                $resCaseProj['csDuDt'] = $caseDueDate;
                $resCaseProj['csCrtdDt'] = $caseCreateDate;
                $resCaseProj['csNum'] = $caseNum;
                $resCaseProj['csLgndSrt'] = $caseLegendsort;
                $resCaseProj['csAtSrt'] = $caseAtsort;
                $resCaseProj['caseMenuFilters'] = $caseMenuFilters;

                $frmtCaseAll = $this->formatCases($caseAll, $CaseCount, 'milestone', $c, $m, $projUniq, $usrDtlsArr, $frmt, $dt, $tz, $cq);
                $resCaseProj['caseAll'] = $frmtCaseAll['caseAll'];
                $resCaseProj['milestones'] = $frmtCaseAll['milestones'];

                //$pgShLbl = $frmt->pagingShowRecords($CaseCount,$page_limit,$casePage);
                //$resCaseProj['pgShLbl'] = $pgShLbl;

                $curCreated = $tz->GetDateTime(SES_TIMEZONE,TZ_GMT,TZ_DST,TZ_CODE,GMT_DATETIME,"datetime");
                $friday = date('Y-m-d',strtotime($curCreated."next Friday"));
                $monday = date('Y-m-d',strtotime($curCreated."next Monday"));
                $tomorrow = date('Y-m-d',strtotime($curCreated."+1 day"));

                $resCaseProj['intCurCreated'] = strtotime($curCreated);
                $resCaseProj['mdyCurCrtd'] = date('m/d/Y', strtotime($curCreated));
                $resCaseProj['mdyFriday'] = date('m/d/Y', strtotime($friday));
                $resCaseProj['mdyMonday'] = date('m/d/Y', strtotime($monday));
                $resCaseProj['mdyTomorrow'] = date('m/d/Y', strtotime($tomorrow));

                if($projUniq != 'all') {
                    $projUser = array();
                    if($projUniq) {
                        $projUser = array($projUniq => $this->getMemebers($projUniq));
                    }
                    $resCaseProj['projUser'] = $projUser;
                }

//                pr($resCaseProj);exit;
                $resCaseProj['error']=0;
                return $resCaseProj;
            }
        }else {
			$total_exist = 0;
			$total_active = 0;
			$total_inactive = 0;
			if($milestones_all){
				$total_exist = count($milestones_all);
				foreach($milestones_all as $k => $v){
					if($v['Milestone']['isactive']){
						$total_active++;
					}else{
						$total_inactive++;
					}
				}
			}
			$arr['total_exist']= $total_exist;
			$arr['total_active']= $total_active;
			$arr['total_inactive']= $total_inactive;
			$arr['mile_type']= $isActive;
            $arr['error']= "No milestone";

            return $arr;
        }
    }
    function usedSpace($curProjId = NULL,$company_id = SES_COMP)
    {
	    App::import('Model','CaseFile');
	    $CaseFile = new CaseFile();
	    $CaseFile->recursive = -1;
	    $cond =" 1 ";
	    if($company_id){
		    $cond .=" AND company_id=".$company_id;
	    }
	    if($curProjId){
		    $cond .=" AND project_id=".$curProjId;
	    }
	    $sql = "SELECT SUM(file_size) AS file_size  FROM case_files   WHERE ".$cond;
	    $res1 = $CaseFile->query($sql);
	    $filesize = $res1['0']['0']['file_size']/1024;
	    return number_format($filesize,2);	
    }
}
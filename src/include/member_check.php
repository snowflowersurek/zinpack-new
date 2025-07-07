<?php
if (!defined("_INFOWAY_")) exit; // 개별 페이지 접근 불가


$sql = "select * from {$iw['enterprise_table']} where ep_code = '{$iw['store']}'";
$row = sql_fetch($sql);

$iw['mcb'] = $row['ep_state_mcb'];
$iw['publishing'] = $row['ep_state_publishing'];
$iw['shop'] = $row['ep_state_shop'];
$iw['doc'] = $row['ep_state_doc'];
$iw['book'] = $row['ep_state_book'];
$iw['exposed'] = $row['ep_exposed'];

if(isset($_GET['type']) && $_GET['type']=="mcb" && $iw['mcb']!=1) alert(national_language($iw['language'],"a0035","잘못된 접근입니다."),"");
if(isset($_GET['type']) && $_GET['type']=="publishing" && $iw['publishing']!=1) alert(national_language($iw['language'],"a0035","잘못된 접근입니다."),"");
if(isset($_GET['type']) && $_GET['type']=="shop" && $iw['shop']!=1) alert(national_language($iw['language'],"a0035","잘못된 접근입니다."),"");
if(isset($_GET['type']) && $_GET['type']=="doc" && $iw['doc']!=1) alert(national_language($iw['language'],"a0035","잘못된 접근입니다."),"");
if(isset($_GET['type']) && $_GET['type']=="book" && $iw['book']!=1) alert(national_language($iw['language'],"a0035","잘못된 접근입니다."),"");

$iw['level'] = "guest";
$iw['gp_level'] = "gp_guest";

if (get_cookie("iw_member")){
	$iw['member'] = get_cookie("iw_member");
	$row = sql_fetch(" select mb_display,mb_level from {$iw['member_table']} where ep_code = '{$iw['store']}' and mb_code = '{$iw['member']}' ");
	if ($row['mb_display']){
		if (!$row['mb_level']){
			$iw['mb_level'] = 0;
		}else{
			$iw['mb_level'] = $row['mb_level'];
		}
		if ($row['mb_display'] == 9){
			$iw['level'] = "super";
		}else if ($row['mb_display'] == 7){
			$iw['level'] = "admin";
			$iw['mb_level'] = 9;
		}else if ($row['mb_display'] == 4){
			$iw['level'] = "seller";
		}else{
			$iw['level'] = "member";
		}
	}

	if ($iw['group'] != "all"){
		$row = sql_fetch(" select gp_no from {$iw['group_table']} where ep_code = '{$iw['store']}' and gp_code = '{$iw['group']}' and mb_code = '{$iw['member']}' and gp_display=1 ");
		if ($row['gp_no']){
			$iw['gp_level'] = "gp_admin";
			$iw['mb_level'] = 9;
		}else{
			$row = sql_fetch(" select gm_no,gm_level from {$iw['group_member_table']} where ep_code = '{$iw['store']}' and gp_code = '{$iw['group']}' and mb_code = '{$iw['member']}' and gm_display=1 ");
			if ($row['gm_no']){
				$iw['gp_level'] = "gp_member";
				if (!$row['gm_level']){
					$iw['mb_level'] = 0;
				}else{
					$iw['mb_level'] = $row['gm_level'];
				}
			}else{
				$iw['gp_level'] = "gp_guest";
			}
		}
	}
}

//48시간 미승인 데이타 삭제
$member_confirm_date = date('Y-m-d H:i:s', strtotime('-2 days'));
$sql = "delete from {$iw['member_table']} where date(mb_datetime) < '$member_confirm_date' and mb_display = 0";
sql_query($sql);


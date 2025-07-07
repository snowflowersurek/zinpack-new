<?php
if (!defined("_INFOWAY_")) exit; // 개별 페이지 접근 불가

$iw['level'] = "guest";
$cookie_member = get_cookie("iw_member");

if (!empty($cookie_member)) {
	$iw['member'] = $cookie_member;
	$sql = " select mb_display from {$iw['member_table']} where ep_code = '{$iw['store']}' and mb_code = '{$iw['member']}' ";
	$row = sql_fetch($sql);
	if ($row && isset($row['mb_display'])){
		if ($row['mb_display'] == 9){
			$iw['level'] = "super";
		}else if ($row['mb_display'] == 7){
			$iw['level'] = "admin";
		}else if ($row['mb_display'] == 4){
			$iw['level'] = "seller";
		}else{
			$iw['level'] = "member";
		}
	}
}


<?php
$iw_path = "../.."; // common.php 의 상대 경로
include_once("$iw_path/include/common.php");
include_once("{$iw['include_path']}/member_check.php");

$cate_home = $iw['type'];
if($iw['type']=="main"){
	$sql = "select hm_code,state_sort from {$iw['setting_table']} where ep_code = '{$iw['store']}' and gp_code = '{$iw['group']}'";
	$row = sql_fetch($sql);
	$hm_code = $row["hm_code"];
	$state_sort = $row["state_sort"];

	if($hm_code == "scrap"){
		goto_url($iw['m_path']."/main.php?type=".$state_sort."&ep=".$iw['store']."&gp=".$iw['group']."&menu=".$hm_code);
	}else if($hm_code != "main"){
		goto_url($iw['m_path']."/all_data_list.php?type=".$state_sort."&ep=".$iw['store']."&gp=".$iw['group']."&menu=".$hm_code);
	}
}
$sql = "select gp_closed from {$iw['group_table']} where ep_code = '{$iw['store']}' and gp_code = '{$iw['group']}'";
$row = sql_fetch($sql);
if (($iw['exposed']==1 && $iw['level']=="guest") || ($iw['group'] != "all" && $row['gp_closed']==0 && $iw['level']=="guest")) alert(national_language($iw['language'],"a0003","로그인 해주시기 바랍니다."),"{$iw['m_path']}/all_login.php?type={$iw['type']}&ep={$iw['store']}&gp={$iw['group']}&re_url={$iw['re_url']}");
if ($iw['gp_level'] == "gp_guest" && $iw['group'] != "all" && $row['gp_closed']==0) alert(national_language($iw['language'],"a0004","그룹회원만 이용하실 수 있습니다."),"{$iw['m_path']}/all_group_join.php?type={$iw['type']}&ep={$iw['store']}&gp={$iw['group']}");
?>




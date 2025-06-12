<?php
include_once("_common.php");
if ($iw[type] != "group" || $iw[gp_level] != "gp_admin" || $iw[group] == "all") alert("잘못된 접근입니다!","");
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?
$gm_no = $_GET[idx];

$sql = "update $iw[group_member_table] set
		gm_display = 1
		where gm_no = '$gm_no' and ep_code = '$iw[store]' and gp_code = '$iw[group]'
		";

sql_query($sql);

alert("가입신청이 승인되었습니다.","$iw[admin_path]/group_join_list.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]");

?>
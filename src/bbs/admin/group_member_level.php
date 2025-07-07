<?php
include_once("_common.php");
if ($iw[type] != "group" || $iw[gp_level] != "gp_admin" || $iw[group] == "all") alert("잘못된 접근입니다!","");
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?php
$gm_level = trim(mysqli_real_escape_string($iw['connect'], $_POST['gm_level']));
$gm_count = trim(mysqli_real_escape_string($iw['connect'], $_POST['gm_count']));

for ($i=0; $i<$gm_count; $i++) {
	$ct_chk = trim(mysqli_real_escape_string($iw['connect'], $_POST['ct_chk'][$i]));
	if ($ct_chk == 1){
		$gm_no = trim(mysqli_real_escape_string($iw['connect'], $_POST['gm_no'][$i]));

		$sql = "update $iw[group_member_table] set
				gm_level = '$gm_level'
				where ep_code = '$iw[store]' and gp_code = '$iw[group]' and gm_no = '$gm_no'
				";
		sql_query($sql);
	}
}

goto_url("$iw[admin_path]/group_member_list.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]");
?>




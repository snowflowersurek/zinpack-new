<?php
include_once("_common.php");
if ($iw['level'] != "admin") alert("잘못된 접근입니다!","");
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?php
$gm_level = trim(mysqli_real_escape_string($iw['connect'], $_POST['gm_level']));
$gm_count = trim(mysqli_real_escape_string($iw['connect'], $_POST['gm_count']));

for ($i=0; $i<$gm_count; $i++) {
	$ct_chk = trim(mysqli_real_escape_string($iw['connect'], $_POST['ct_chk'][$i]));
	if ($ct_chk == 1){
		$mb_no = trim(mysqli_real_escape_string($iw['connect'], $_POST['mb_no'][$i]));

		$sql = "update $iw[member_table] set
				mb_level = '$gm_level'
				where ep_code = '$iw[store]' and mb_no = '$mb_no'
				";
		sql_query($sql);
	}
}

goto_url("$iw[admin_path]/member_data_list.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]");
?>




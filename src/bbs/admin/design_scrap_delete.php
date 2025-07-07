<?php
include_once("_common.php");
if (($iw[group] == "all" && $iw[level] != "admin") || ($iw[group] != "all" && $iw[gp_level] != "gp_admin")) alert("잘못된 접근입니다!","");
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?php
if (!$_GET['menu']) exit;
$hs_no = $_GET[menu];

$row2 = sql_fetch(" select * from $iw[home_scrap_table] where hs_no = '$hs_no' and ep_code = '$iw[store]' and gp_code = '$iw[group]'");
if (!$row2[hs_no]) {
	alert("스크래퍼가 존재하지 않습니다.","");
}else{
	$hs_scrap = $row2[hs_scrap];
	$hm_code = $row2[hm_code];
	$hs_file_1 = $row2[hs_file_1];
	$hs_file_2 = $row2[hs_file_2];
	$hs_file_3 = $row2[hs_file_3];
	$hs_file_4 = $row2[hs_file_4];
	$hs_file_5 = $row2[hs_file_5];
	$hs_file_6 = $row2[hs_file_6];
	$hs_file_7 = $row2[hs_file_7];
	$hs_file_8 = $row2[hs_file_8];
	$hs_file_9 = $row2[hs_file_9];
	$hs_file_10 = $row2[hs_file_10];


	$row = sql_fetch(" select ep_nick from $iw[enterprise_table] where ep_code = '$iw[store]'");
	$upload_path = "/main/$row[ep_nick]";

	if ($iw[group] == "all"){
		$upload_path .= "/all";
	}else{
		$row = sql_fetch(" select gp_nick from $iw[group_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]'");
		$upload_path .= "/$row[gp_nick]";
	}
	$upload_path .= "/_images";
	$abs_dir = $iw[path].$upload_path;

	if(is_file($abs_dir."/".$hs_file_1)==true && $hs_file_1 != ""){
		unlink($abs_dir."/".$hs_file_1);
	}
	if(is_file($abs_dir."/".$hs_file_2)==true && $hs_file_2 != ""){
		unlink($abs_dir."/".$hs_file_2);
	}
	if(is_file($abs_dir."/".$hs_file_3)==true && $hs_file_3 != ""){
		unlink($abs_dir."/".$hs_file_3);
	}
	if(is_file($abs_dir."/".$hs_file_4)==true && $hs_file_4 != ""){
		unlink($abs_dir."/".$hs_file_4);
	}
	if(is_file($abs_dir."/".$hs_file_5)==true && $hs_file_5 != ""){
		unlink($abs_dir."/".$hs_file_5);
	}
	if(is_file($abs_dir."/".$hs_file_6)==true && $hs_file_6 != ""){
		unlink($abs_dir."/".$hs_file_6);
	}
	if(is_file($abs_dir."/".$hs_file_7)==true && $hs_file_7 != ""){
		unlink($abs_dir."/".$hs_file_7);
	}
	if(is_file($abs_dir."/".$hs_file_8)==true && $hs_file_8 != ""){
		unlink($abs_dir."/".$hs_file_8);
	}
	if(is_file($abs_dir."/".$hs_file_9)==true && $hs_file_9 != ""){
		unlink($abs_dir."/".$hs_file_9);
	}
	if(is_file($abs_dir."/".$hs_file_10)==true && $hs_file_10 != ""){
		unlink($abs_dir."/".$hs_file_10);
	}

	if(is_file($abs_dir."/(60)".$hs_file_1)==true && $hs_file_1 != ""){
		unlink($abs_dir."/(60)".$hs_file_1);
	}
	if(is_file($abs_dir."/(60)".$hs_file_2)==true && $hs_file_2 != ""){
		unlink($abs_dir."/(60)".$hs_file_2);
	}
	if(is_file($abs_dir."/(60)".$hs_file_3)==true && $hs_file_3 != ""){
		unlink($abs_dir."/(60)".$hs_file_3);
	}
	if(is_file($abs_dir."/(60)".$hs_file_4)==true && $hs_file_4 != ""){
		unlink($abs_dir."/(60)".$hs_file_4);
	}
	if(is_file($abs_dir."/(60)".$hs_file_5)==true && $hs_file_5 != ""){
		unlink($abs_dir."/(60)".$hs_file_5);
	}
	if(is_file($abs_dir."/(60)".$hs_file_6)==true && $hs_file_6 != ""){
		unlink($abs_dir."/(60)".$hs_file_6);
	}
	if(is_file($abs_dir."/(60)".$hs_file_7)==true && $hs_file_7 != ""){
		unlink($abs_dir."/(60)".$hs_file_7);
	}

	$sql = "delete from $iw[home_scrap_table] where hs_no = '$hs_no' and ep_code = '$iw[store]' and gp_code = '$iw[group]'";
	sql_query($sql);

	echo "<script>window.parent.location.href='$iw[admin_path]/design_scrap_list.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&menu=$hm_code&scrap=$hs_scrap';</script>";
}
?>




<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php
include_once("_common.php");

$return_url = $iw['super_path']."/enterprise_list.php?type=".$iw[type]."&ep=".$iw[store]."&gp=".$iw[group];

function deleteDir($dirPath) {
	if (is_dir($dirPath)) {
		$objects = scandir($dirPath);
		foreach ($objects as $object) {
			if ($object != "." && $object !="..") {
				if (filetype($dirPath . DIRECTORY_SEPARATOR . $object) == "dir") {
					deleteDir($dirPath . DIRECTORY_SEPARATOR . $object);
				} else {
					unlink($dirPath . DIRECTORY_SEPARATOR . $object);
				}
			}
		}
		reset($objects);
		rmdir($dirPath);
	}
}

$epcode = $_REQUEST['epcode'];
$sql = "SELECT * FROM `iw_enterprise` WHERE `ep_code` = '$epcode'";
$eprow = sql_fetch($sql);
$ep_nick = $eprow['ep_nick'];
$del_epcode = str_replace(".del","",$epcode);

$tables=array("iw_about_data",
							"iw_access_count",
							"iw_account",
							"iw_category",
							"iw_comment",
							"iw_group",
							"iw_group_invite",
							"iw_group_level",
							"iw_group_member",
							"iw_home_menu",
							"iw_home_scrap",
							"iw_member",
							"iw_notice",
							"iw_popup_layer",
							"iw_setting",
							"iw_shop_cart",
							"iw_theme_data",
							"iw_theme_setting",
							"iw_total_data");

for($i=0; $i<sizeof($tables); $i++){
	$dsql = "SELECT COUNT(*) AS cnt FROM $tables[$i] WHERE ep_code = '$del_epcode'";
	$drow = sql_fetch($dsql);
	if($drow['cnt'] > 0){
		$delsql = "DELETE FROM $tables[$i] WHERE ep_code = '$del_epcode'";
		sql_query($delsql);
	}
}
$delsql = "DELETE FROM iw_enterprise WHERE ep_code = '$epcode'";
sql_query($delsql);

// 디렉토리 삭제
$dir_arr = array ("$iw[path]/about/$ep_nick",
									"$iw[path]/mcb/$ep_nick",
									"$iw[path]/publishing/$ep_nick",
									"$iw[path]/shop/$ep_nick",
									"$iw[path]/doc/$ep_nick",
									"$iw[path]/book/$ep_nick",
									"$iw[path]/main/$ep_nick");

for ($i=0; $i<count($dir_arr); $i++) {
	deleteDir($dir_arr[$i]);
}

echo "all done!";
alert("삭제가 완료되었습니다. ", "$return_url");
?>
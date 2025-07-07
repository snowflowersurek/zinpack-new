<?php
include_once("_common.php");
if (($iw[group] == "all" && $iw[level] != "admin") || ($iw[group] != "all" && $iw[gp_level] != "gp_admin")) alert("잘못된 접근입니다!","");
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?php
$menu_output = str_replace("\\", "", $_POST[menu_output]);
$json = json_decode($menu_output, true);

$a = 1;
foreach($json as $key => $value)
{
	$row = sql_fetch("select hm_code from $iw[home_menu_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and hm_no = '$value[id]'");
	$hm_code = $row["hm_code"];

	$sql = "update $iw[home_menu_table] set
		hm_order = '$a',
		hm_deep = '1',
		hm_upper_code = ''
		where hm_no = '$value[id]' and ep_code = '$iw[store]' and gp_code = '$iw[group]'
		";
	sql_query($sql);

	if($value['children']){
		$b = 1;
		foreach($value['children'] as $key2 => $value2)
		{
			$row2 = sql_fetch("select hm_code from $iw[home_menu_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and hm_no = '$value2[id]'");
			$hm_code2 = $row2["hm_code"];

			$sql = "update $iw[home_menu_table] set
				hm_order = '$b',
				hm_deep = '2',
				hm_upper_code = '$hm_code'
				where hm_no = '$value2[id]' and ep_code = '$iw[store]' and gp_code = '$iw[group]'
				";
			sql_query($sql);

			if($value2['children']){
				$c = 1;
				foreach($value2['children'] as $key3 => $value3)
				{
					$row3 = sql_fetch("select hm_code from $iw[home_menu_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and hm_no = '$value3[id]'");
					$hm_code3 = $row3["hm_code"];

					$sql = "update $iw[home_menu_table] set
						hm_order = '$c',
						hm_deep = '3',
						hm_upper_code = '$hm_code2'
						where hm_no = '$value3[id]' and ep_code = '$iw[store]' and gp_code = '$iw[group]'
						";
					sql_query($sql);

					if($value3['children']){
						$d = 1;
						foreach($value3['children'] as $key4 => $value4)
						{
							$sql = "update $iw[home_menu_table] set
								hm_order = '$d',
								hm_deep = '4',
								hm_upper_code = '$hm_code3'
								where hm_no = '$value4[id]' and ep_code = '$iw[store]' and gp_code = '$iw[group]'
								";
							sql_query($sql);

							$d++;
						}
					}
					$c++;
				}
			}
			$b++;
		}
	}
	$a++;
}

echo "<script>window.parent.location.href='$iw[admin_path]/design_menu_list.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]';</script>";

?>




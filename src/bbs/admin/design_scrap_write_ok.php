<?php
include_once("_common.php");
if (($iw['group'] == "all" && $iw['level'] != "admin") || ($iw['group'] != "all" && $iw['gp_level'] != "gp_admin")) alert("잘못된 접근입니다!","");
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?php
$upload_path = $_POST['upload_path'];
$hs_scrap = trim(sql_real_escape_string($_POST['hs_scrap']));
$hm_code = trim(sql_real_escape_string($_POST['hm_code']));
$hs_name = trim(sql_real_escape_string($_POST['hs_name']));
$hs_style = trim(sql_real_escape_string($_POST['hs_style']));
$hs_type = trim(sql_real_escape_string($_POST['hs_type']));
$hs_border_width = trim(sql_real_escape_string($_POST['hs_border_width']));
$hs_border_color = trim(sql_real_escape_string($_POST['hs_border_color']));
$hs_border_radius = trim(sql_real_escape_string($_POST['hs_border_radius']));
$hs_topic_type = trim(sql_real_escape_string($_POST['hs_topic_type']));
$hs_topic_link = trim(sql_real_escape_string($_POST['hs_topic_link']));
$hs_topic_more = trim(sql_real_escape_string($_POST['hs_topic_more']));
$hs_topic_bullet = sql_real_escape_string($_POST['hs_topic_bullet']);
$hs_bg_alpha = trim(sql_real_escape_string($_POST['hs_bg_alpha']));
$hs_title_color = trim(sql_real_escape_string($_POST['hs_title_color']));
$hs_font_color = trim(sql_real_escape_string($_POST['hs_font_color']));
$hs_font_hover = trim(sql_real_escape_string($_POST['hs_font_hover']));
$hs_box_padding = trim(sql_real_escape_string($_POST['hs_box_padding']));
$hs_file_sort = "";

if($hs_style == 1){
	$hs_size_width = 12;
	$hs_size_height = 0;
}else{
	$hs_size_width = trim(sql_real_escape_string($_POST['hs_size_width']));
	$hs_size_height = trim(sql_real_escape_string($_POST['hs_size_height']));
	$hs_bg_color = trim(sql_real_escape_string($_POST['hs_bg_color']));
}

if($hs_type == "menu"){
	$hs_menu = trim(sql_real_escape_string($_POST['hs_menu']));
	$hs_banner_cnt = trim(sql_real_escape_string($_POST['hs_banner_cnt']));
}
if($hs_type != "custom"){
	$hs_sort = trim(sql_real_escape_string($_POST['hs_sort']));
}

if($hs_style == 6 && $hs_type == "custom"){
	$hs_sns_url = trim(sql_real_escape_string($_POST['hs_sns_url']));
}else if($hs_style == 3 && $hs_type == "custom"){
	$abs_dir = $iw['path'].$upload_path;

	for ($i=0; $i<count($_POST['hs_link']); $i++) {
		$hs_title = trim(sql_real_escape_string($_POST['hs_title'][$i]));
		$hs_title_total .= ";".$hs_title;
		$hs_content = trim(sql_real_escape_string($_POST['hs_content'][$i]));
		$hs_content_total .= ";".$hs_content;
		$hs_link = trim(sql_real_escape_string($_POST['hs_link'][$i]));
		$hs_link_total .= ";".$hs_link;
		
		if($_FILES["hs_file"]["name"][$i] && $_FILES["hs_file"]["size"][$i]>0){
			$hs_file_name = uniqid(rand());
			$hs_file = $hs_file_name.".".preg_replace('/^.*\.([^.]+)$/D', '$1',$_FILES["hs_file"]["name"][$i]);
			$result = move_uploaded_file($_FILES["hs_file"]["tmp_name"][$i], "{$abs_dir}/{$hs_file}");
			
			if(!$result){
				if(is_file($abs_dir."/".$hs_file)==true){
					unlink($abs_dir."/".$hs_file);
				}

				$hs_file_total = explode(";", $hs_file_total);
				for ($a=1; $a<count($hs_file_total); $a++) {
					if(is_file($abs_dir."/".$hs_file_total[$a])==true){
						unlink($abs_dir."/".$hs_file_total[$a]);
					}
				}
				alert("이미지 첨부에러.", "");
			}else{
				$hs_file_total .= ";".$hs_file;
			}
		}
	}
}else if($hs_style != 3 && $hs_type == "custom"){
	$abs_dir = $iw['path'].$upload_path;
	
	for ($i=0; $i<count($_POST['hs_link']); $i++) {
		if($_FILES["hs_file"]["name"][$i] && $_FILES["hs_file"]["size"][$i]>0){
			$hs_file_sort .= $_POST['hs_file_sort'][$i].",";
			$hs_link = trim(sql_real_escape_string($_POST['hs_link'][$i]));
			$hs_link_total .= ";".$hs_link;
			$hs_file_name = uniqid(rand());
			$hs_file = $hs_file_name.".".preg_replace('/^.*\.([^.]+)$/D', '$1',$_FILES["hs_file"]["name"][$i]);
			$result = move_uploaded_file($_FILES["hs_file"]["tmp_name"][$i], "{$abs_dir}/{$hs_file}");
			
			if(!$result){
				if(is_file($abs_dir."/".$hs_file)==true){
					unlink($abs_dir."/".$hs_file);
				}

				$hs_file_total = explode(";", $hs_file_total);
				for ($a=1; $a<count($hs_file_total); $a++) {
					if(is_file($abs_dir."/".$hs_file_total[$a])==true){
						unlink($abs_dir."/".$hs_file_total[$a]);
					}
				}
				alert("이미지 첨부에러.", "");
			}else{
				$hs_file_total .= ";".$hs_file;
			}
		}
	}
}

$hs_title_total = explode(";", $hs_title_total);
$hs_content_total = explode(";", $hs_content_total);
$hs_link_total = explode(";", $hs_link_total);
$hs_file_total = explode(";", $hs_file_total);

$sql = "insert into $iw['home_scrap_table'] set
		ep_code = '$iw['store']',
		gp_code = '$iw['group']',
		hm_code = '$hm_code',
		hs_scrap = '$hs_scrap',
		hs_name = '$hs_name',
		hs_order = 0,
		hs_style = '$hs_style',
		hs_sort = '$hs_sort',
		hs_type = '$hs_type',
		hs_menu = '$hs_menu',
		hs_banner_cnt = '$hs_banner_cnt',
		hs_size_width = '$hs_size_width',
		hs_size_height = '$hs_size_height',
		hs_bg_color = '$hs_bg_color',
		hs_bg_alpha = '$hs_bg_alpha',
		hs_title_color = '$hs_title_color',
		hs_font_color = '$hs_font_color',
		hs_font_hover = '$hs_font_hover',
		hs_border_width = '$hs_border_width',
		hs_border_color = '$hs_border_color',
		hs_border_radius = '$hs_border_radius',
		hs_topic_type = '$hs_topic_type',
		hs_topic_link = '$hs_topic_link',
		hs_topic_more = '$hs_topic_more',
		hs_topic_bullet = '$hs_topic_bullet',
		hs_title_1 = '$hs_title_total[1]',
		hs_title_2 = '$hs_title_total[2]',
		hs_title_3 = '$hs_title_total[3]',
		hs_title_4 = '$hs_title_total[4]',
		hs_title_5 = '$hs_title_total[5]',
		hs_title_6 = '$hs_title_total[6]',
		hs_title_7 = '$hs_title_total[7]',
		hs_title_8 = '$hs_title_total[8]',
		hs_title_9 = '$hs_title_total[9]',
		hs_title_10 = '$hs_title_total[10]',
		hs_title_11 = '$hs_title_total[11]',
		hs_title_12 = '$hs_title_total[12]',
		hs_title_13 = '$hs_title_total[13]',
		hs_title_14 = '$hs_title_total[14]',
		hs_title_15 = '$hs_title_total[15]',
		hs_title_16 = '$hs_title_total[16]',
		hs_title_17 = '$hs_title_total[17]',
		hs_title_18 = '$hs_title_total[18]',
		hs_title_19 = '$hs_title_total[19]',
		hs_title_20 = '$hs_title_total[20]',
		hs_title_21 = '$hs_title_total[21]',
		hs_content_1 = '$hs_content_total[1]',
		hs_content_2 = '$hs_content_total[2]',
		hs_content_3 = '$hs_content_total[3]',
		hs_content_4 = '$hs_content_total[4]',
		hs_content_5 = '$hs_content_total[5]',
		hs_content_6 = '$hs_content_total[6]',
		hs_content_7 = '$hs_content_total[7]',
		hs_link_1 = '$hs_link_total[1]',
		hs_link_2 = '$hs_link_total[2]',
		hs_link_3 = '$hs_link_total[3]',
		hs_link_4 = '$hs_link_total[4]',
		hs_link_5 = '$hs_link_total[5]',
		hs_link_6 = '$hs_link_total[6]',
		hs_link_7 = '$hs_link_total[7]',
		hs_link_8 = '$hs_link_total[8]',
		hs_link_9 = '$hs_link_total[9]',
		hs_link_10 = '$hs_link_total[10]',
		hs_file_1 = '$hs_file_total[1]',
		hs_file_2 = '$hs_file_total[2]',
		hs_file_3 = '$hs_file_total[3]',
		hs_file_4 = '$hs_file_total[4]',
		hs_file_5 = '$hs_file_total[5]',
		hs_file_6 = '$hs_file_total[6]',
		hs_file_7 = '$hs_file_total[7]',
		hs_file_8 = '$hs_file_total[8]',
		hs_file_9 = '$hs_file_total[9]',
		hs_file_10 = '$hs_file_total[10]',
		hs_file_sort = '$hs_file_sort',
		hs_box_padding = '$hs_box_padding',
		hs_sns_url = '$hs_sns_url',
		hs_display = 1
		";

sql_query($sql);

echo "<script>window.parent.location.href='$iw['admin_path']/design_scrap_list.php?type=$iw['type']&ep=$iw['store']&gp=$iw['group']&menu=$hm_code&scrap=$hs_scrap';</script>";

?>




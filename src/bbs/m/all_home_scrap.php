<?
if (!defined("_INFOWAY_")) exit; // 개별 페이지 접근 불가
include_once("$iw[include_path]/lib/lib_image_resize.php");

	if($scrap_type != "main"){
		$sql_hm_code = "and hm_code = '$hm_code'";
	}
	$row = sql_fetch(" select ep_nick from $iw[enterprise_table] where ep_code = '$iw[store]'");
	$upload_path = "/$row[ep_nick]";

	if ($iw[group] == "all"){
		$upload_path .= "/all";
	}else{
		$row = sql_fetch(" select gp_nick from $iw[group_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]'");
		$upload_path .= "/$row[gp_nick]";
	}
	$upload_path_list = "/main".$upload_path."/_images";

	$sql_sc = "select * from $iw[home_scrap_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and hs_scrap = '$scrap_type' $sql_hm_code order by hs_order asc,hs_no asc";
	$result_sc = sql_query($sql_sc);
	while($row_sc = @sql_fetch_array($result_sc)){
		$hs_no = $row_sc["hs_no"];
		$hs_name = stripslashes($row_sc["hs_name"]);
		$hs_style = $row_sc['hs_style'];
		$hs_type = $row_sc['hs_type'];
		$hs_menu = $row_sc['hs_menu'];
		$hs_banner_cnt = $row_sc['hs_banner_cnt'];
		$hs_sort = $row_sc['hs_sort'];
		$hs_size_width = $row_sc['hs_size_width'];
		$hs_size_height = $row_sc['hs_size_height'];
		$hs_bg_color = $row_sc['hs_bg_color'];
		$hs_bg_alpha = $row_sc['hs_bg_alpha'];
		$hs_title_color = $row_sc['hs_title_color'];
		$hs_font_color = $row_sc['hs_font_color'];
		$hs_font_hover = $row_sc['hs_font_hover'];
		$hs_border_width = $row_sc['hs_border_width'];
		$hs_border_color = $row_sc['hs_border_color'];
		$hs_border_radius = $row_sc['hs_border_radius'];
		$hs_topic_type = $row_sc['hs_topic_type'];
		$hs_topic_link = $row_sc['hs_topic_link'];
		$hs_topic_more = $row_sc['hs_topic_more'];
		$hs_topic_bullet = $row_sc['hs_topic_bullet'];
		$hs_box_padding = $row_sc['hs_box_padding'];
		$hs_title = array();
		$hs_title[0] = stripslashes($row_sc["hs_title_1"]);
		$hs_title[1] = stripslashes($row_sc["hs_title_2"]);
		$hs_title[2] = stripslashes($row_sc["hs_title_3"]);
		$hs_title[3] = stripslashes($row_sc["hs_title_4"]);
		$hs_title[4] = stripslashes($row_sc["hs_title_5"]);
		$hs_title[5] = stripslashes($row_sc["hs_title_6"]);
		$hs_title[6] = stripslashes($row_sc["hs_title_7"]);
		$hs_title[7] = stripslashes($row_sc["hs_title_8"]);
		$hs_title[8] = stripslashes($row_sc["hs_title_9"]);
		$hs_title[9] = stripslashes($row_sc["hs_title_10"]);
		$hs_title[10] = stripslashes($row_sc["hs_title_11"]);
		$hs_title[11] = stripslashes($row_sc["hs_title_12"]);
		$hs_title[12] = stripslashes($row_sc["hs_title_13"]);
		$hs_title[13] = stripslashes($row_sc["hs_title_14"]);
		$hs_title[14] = stripslashes($row_sc["hs_title_15"]);
		$hs_title[15] = stripslashes($row_sc["hs_title_16"]);
		$hs_title[16] = stripslashes($row_sc["hs_title_17"]);
		$hs_title[17] = stripslashes($row_sc["hs_title_18"]);
		$hs_title[18] = stripslashes($row_sc["hs_title_19"]);
		$hs_title[19] = stripslashes($row_sc["hs_title_20"]);
		$hs_title[20] = stripslashes($row_sc["hs_title_21"]);
		$hs_content = array();
		$hs_content[0] = stripslashes($row_sc["hs_content_1"]);
		$hs_content[1] = stripslashes($row_sc["hs_content_2"]);
		$hs_content[2] = stripslashes($row_sc["hs_content_3"]);
		$hs_content[3] = stripslashes($row_sc["hs_content_4"]);
		$hs_content[4] = stripslashes($row_sc["hs_content_5"]);
		$hs_content[5] = stripslashes($row_sc["hs_content_6"]);
		$hs_content[6] = stripslashes($row_sc["hs_content_7"]);
		$hs_link = array();
		$hs_link[0] = $row_sc['hs_link_1'];
		$hs_link[1] = $row_sc['hs_link_2'];
		$hs_link[2] = $row_sc['hs_link_3'];
		$hs_link[3] = $row_sc['hs_link_4'];
		$hs_link[4] = $row_sc['hs_link_5'];
		$hs_link[5] = $row_sc['hs_link_6'];
		$hs_link[6] = $row_sc['hs_link_7'];
		$hs_link[7] = $row_sc['hs_link_8'];
		$hs_link[8] = $row_sc['hs_link_9'];
		$hs_link[9] = $row_sc['hs_link_10'];
		$hs_file = array();
		$hs_file[0] = $row_sc['hs_file_1'];
		$hs_file[1] = $row_sc['hs_file_2'];
		$hs_file[2] = $row_sc['hs_file_3'];
		$hs_file[3] = $row_sc['hs_file_4'];
		$hs_file[4] = $row_sc['hs_file_5'];
		$hs_file[5] = $row_sc['hs_file_6'];
		$hs_file[6] = $row_sc['hs_file_7'];
		$hs_file[7] = $row_sc['hs_file_8'];
		$hs_file[8] = $row_sc['hs_file_9'];
		$hs_file[9] = $row_sc['hs_file_10'];
		$hs_file_sort_value = explode(",", $row_sc['hs_file_sort']);
		$hs_file_sort = array();
		$hs_file_sort[0] = $hs_file_sort_value[0];
		$hs_file_sort[1] = $hs_file_sort_value[1];
		$hs_file_sort[2] = $hs_file_sort_value[2];
		$hs_file_sort[3] = $hs_file_sort_value[3];
		$hs_file_sort[4] = $hs_file_sort_value[4];
		$hs_file_sort[5] = $hs_file_sort_value[5];
		$hs_file_sort[6] = $hs_file_sort_value[6];
		$hs_file_sort[7] = $hs_file_sort_value[7];
		$hs_file_sort[8] = $hs_file_sort_value[8];
		$hs_file_sort[9] = $hs_file_sort_value[9];

		$hs_bg_color = hex2RGB($hs_bg_color);
		
		$hs_sns_url = $row_sc['hs_sns_url'];
		
		if($hs_sort == "random"){
			$hs_sort = "rand() desc";
		}else if($hs_type == "menu" && $hs_sort == "new"){
			$hs_sort = "td_datetime desc";
		}else if($hs_type == "menu" && $hs_sort == "best"){
			$hs_sort = "td_hit desc";
		}else if($hs_type == "notice" && $hs_sort == "new"){
			$hs_sort = "nt_no desc";
		}else if($hs_type == "notice" && $hs_sort == "best"){
			$hs_sort = "nt_hit desc";
		} 
		
		if($hs_size_width == 12){
			$hs_size_width = "full";
		}
		
		if($hs_style==1){
			include("all_home_slide.php");
		}else if($hs_style==2){
			include("all_home_banner.php");
		}else if($hs_style==3){
			include("all_home_topic.php");
		}else if($hs_style==5){
			include("all_home_reply.php");
		}else if($hs_style==6){
			include("all_home_sns.php");
		}
	}
?>

<div id="fb-root"></div>
<script>
var w = $(window).width();
var unit_h = 72;

if (w >= 768 && w < 992) {
	unit_h = 45;
} else if (w >= 992 && w < 1200) {
	unit_h = 60;
} else {
	unit_h = 72;
}

(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/ko_KR/sdk.js#xfbml=1&version=v2.10";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
</script>

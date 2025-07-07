<?php
if (!defined("_INFOWAY_")) exit; // 개별 페이지 접근 불가

//----페이지 카운트 시작
if($iw['store']=="ep136619553152f0a2d5928db"){	// 월간항공인 경우
	$count_date = date("Y-m-d H:i:s", strtotime(date("Ymd")));
	$row = sql_fetch(" select count(*) as cnt from {$iw['access_count_table']} where ep_code = '{$iw['store']}' and gp_code = '{$iw['group']}' and ac_date = '$count_date' ");
	if (!@$row['cnt']) {
		$sql = "insert into {$iw['access_count_table']} set
				ep_code = '{$iw['store']}',
				gp_code = '{$iw['group']}',
				ac_page_count = 1,
				ac_date = '$count_date'
				";
		sql_query($sql);
	}else{
		$sql = "update {$iw['access_count_table']} set
			ac_page_count = ac_page_count + 1
			where ep_code = '{$iw['store']}' and gp_code = '{$iw['group']}' and ac_date = '$count_date'
			";
		sql_query($sql);
	}
	$count_ip = get_cookie("iw_count_ip");
	if($count_ip != $_SERVER["REMOTE_ADDR"]){
		$sql = "update {$iw['access_count_table']} set
			ac_ip_count = ac_ip_count + 1
			where ep_code = '{$iw['store']}' and gp_code = '{$iw['group']}' and ac_date = '$count_date'
			";
		sql_query($sql);

		set_cookie("iw_count_ip", "{$_SERVER['REMOTE_ADDR']}", time()+86400);
	}
}
//-- 페이지 카운트 종료

$row = sql_fetch(" select ep_nick,ep_copy_off from {$iw['enterprise_table']} where ep_code = '{$iw['store']}'");
$upload_path = "/main/{$row['ep_nick']}";
$book_path = "/{$row['ep_nick']}/book";
$ep_copy_off = $row['ep_copy_off'];

if ($iw['group'] == "all"){
	$upload_path .= "/all";
}else{
	$row = sql_fetch(" select gp_nick from {$iw['group_table']} where ep_code = '{$iw['store']}' and gp_code = '{$iw['group']}'");
	$upload_path .= "/{$row['gp_nick']}";
}
$upload_path .= "/_images";

$sql = "select * from {$iw['setting_table']} where ep_code = '{$iw['store']}' and gp_code = '{$iw['group']}'";
$row = sql_fetch($sql);

$st_title = $row["st_title"];
$st_content = $row["st_content"];
if ($st_content == "") {
	$st_content = $st_title;
}
$st_top_img = $row["st_top_img"];
$st_top_align = $row["st_top_align"];
$st_favicon = $row["st_favicon"];
$st_background = explode(",",$row["st_background"]);
$st_mcb_name = $row["st_mcb_name"];
$st_publishing_name = $row["st_publishing_name"];
$st_doc_name = $row["st_doc_name"];
$st_shop_name = $row["st_shop_name"];
$st_book_name = $row["st_book_name"];
$st_menu_position = $row["st_menu_position"];
$st_menu_mobile = $row["st_menu_mobile"];
$st_navigation = $row["st_navigation"];
$st_slide_size = $row["st_slide_size"];
$st_sns_share = $row["st_sns_share"];
for ($i=0; $i<5; $i++) {
	if(isset($st_background[$i]) && $st_background[$i]){
		$backstretch_item .= "'".$upload_path."/".$st_background[$i]."',";
	}
}
/*
if(!$backstretch_item){
	$backstretch_item = "'".$iw['design_path']."/img/wallpaper1.jpg',";
	$backstretch_item .= "'".$iw['design_path']."/img/wallpaper2.jpg',";
	$backstretch_item .= "'".$iw['design_path']."/img/wallpaper3.jpg',";
	$backstretch_item .= "'".$iw['design_path']."/img/wallpaper4.jpg',";
}
*/
if($st_top_align == "center"){
	$st_top_align = "margin:0 auto";
}else{
	$st_top_align = "float:".$st_top_align;
}
if($st_top_img){
	$st_top_img = "<img class='img-responsive' style='".$st_top_align."' src='".$upload_path."/".$st_top_img."' alt='logo' />";
}else{
	$st_top_img = $st_title;
}

if($st_favicon){
	$st_favicon = "<link rel='shortcut icon' href='".$upload_path."/".$st_favicon."' />";
}else{
	$st_favicon = "<link href='/favicon.ico' rel='icon'>";
}

$iw['main_color'] = $row["st_color"];
if(!$iw['main_color']) $iw['main_color'] = "0";

$st_mcb_list = 6;
$st_publishing_list = 6;
$st_shop_list = 6;
$st_doc_list = 6;
$st_book_list = 6;



<?php
include_once("_common.php");
if (($iw['group'] == "all" && $iw['level'] != "admin") || ($iw['group'] != "all" && $iw['gp_level'] != "gp_admin")) alert("잘못된 접근입니다!","");
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?php
$cg_name = trim(sql_real_escape_string($_POST['cg_name']));
$cg_level_write = trim(sql_real_escape_string($_POST['cg_level_write']));
$cg_level_comment = trim(sql_real_escape_string($_POST['cg_level_comment']));
$cg_level_read = trim(sql_real_escape_string($_POST['cg_level_read']));
$cg_level_upload = trim(sql_real_escape_string($_POST['cg_level_upload']));
$cg_level_download = trim(sql_real_escape_string($_POST['cg_level_download']));
$cg_date = trim(sql_real_escape_string($_POST['cg_date']));
$cg_writer = trim(sql_real_escape_string($_POST['cg_writer']));
$cg_hit = trim(sql_real_escape_string($_POST['cg_hit']));
$cg_comment = trim(sql_real_escape_string($_POST['cg_comment']));
$cg_recommend = trim(sql_real_escape_string($_POST['cg_recommend']));
$cg_point_btn = trim(sql_real_escape_string($_POST['cg_point_btn']));
$cg_comment_view = trim(sql_real_escape_string($_POST['cg_comment_view']));
$cg_recommend_view = trim(sql_real_escape_string($_POST['cg_recommend_view']));
$cg_url = trim(sql_real_escape_string($_POST['cg_url']));
$cg_facebook = trim(sql_real_escape_string($_POST['cg_facebook']));
$cg_twitter = trim(sql_real_escape_string($_POST['cg_twitter']));
$cg_googleplus = trim(sql_real_escape_string($_POST['cg_googleplus']));
$cg_pinterest = trim(sql_real_escape_string($_POST['cg_pinterest']));
$cg_linkedin = trim(sql_real_escape_string($_POST['cg_linkedin']));
$cg_delicious = trim(sql_real_escape_string($_POST['cg_delicious']));
$cg_tumblr = trim(sql_real_escape_string($_POST['cg_tumblr']));
$cg_digg = trim(sql_real_escape_string($_POST['cg_digg']));
$cg_stumbleupon = trim(sql_real_escape_string($_POST['cg_stumbleupon']));
$cg_reddit = trim(sql_real_escape_string($_POST['cg_reddit']));
$cg_sinaweibo = trim(sql_real_escape_string($_POST['cg_sinaweibo']));
$cg_qzone = trim(sql_real_escape_string($_POST['cg_qzone']));
$cg_renren = trim(sql_real_escape_string($_POST['cg_renren']));
$cg_tencentweibo = trim(sql_real_escape_string($_POST['cg_tencentweibo']));
$cg_kakaotalk = trim(sql_real_escape_string($_POST['cg_kakaotalk']));
$cg_line = trim(sql_real_escape_string($_POST['cg_line']));

$cg_code = "cg".uniqid(rand());

$sql = "insert into {$iw['category_table']} set
		cg_name = '$cg_name',
		cg_code = '$cg_code',
		ep_code = '{$iw['store']}',
		gp_code = '{$iw['group']}',
		state_sort = '{$iw['type']}',
		cg_level_write = '$cg_level_write',
		cg_level_comment = '$cg_level_comment',
		cg_level_read = '$cg_level_read',
		cg_level_upload = '$cg_level_upload',
		cg_level_download = '$cg_level_download',
		cg_date = '$cg_date',
		cg_writer = '$cg_writer',
		cg_hit = '$cg_hit',
		cg_comment = '$cg_comment',
		cg_recommend = '$cg_recommend',
		cg_point_btn = '$cg_point_btn',
		cg_comment_view = '$cg_comment_view',
		cg_recommend_view = '$cg_recommend_view',
		cg_url = '$cg_url',
		cg_facebook = '$cg_facebook',
		cg_twitter = '$cg_twitter',
		cg_googleplus = '$cg_googleplus',
		cg_pinterest = '$cg_pinterest',
		cg_linkedin = '$cg_linkedin',
		cg_delicious = '$cg_delicious',
		cg_tumblr = '$cg_tumblr',
		cg_digg = '$cg_digg',
		cg_stumbleupon = '$cg_stumbleupon',
		cg_reddit = '$cg_reddit',
		cg_sinaweibo = '$cg_sinaweibo',
		cg_qzone = '$cg_qzone',
		cg_renren = '$cg_renren',
		cg_tencentweibo = '$cg_tencentweibo',
		cg_kakaotalk = '$cg_kakaotalk',
		cg_line = '$cg_line',
		cg_display = '1'
		";

sql_query($sql);

echo "<script>window.parent.location.href='{$iw['admin_path']}/category_list.php?type={$iw['type']}&ep={$iw['store']}&gp={$iw['group']}';</script>";
?>




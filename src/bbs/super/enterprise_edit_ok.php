<?php
include_once("_common.php");
if ($iw['level'] != "super") alert("잘못된 접근입니다!","");
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?
$ep_no = $_POST[ep_no];
$ep_corporate = trim(mysql_real_escape_string($_POST[ep_corporate]));
$ep_permit_number = trim(mysql_real_escape_string($_POST[ep_permit_number]));
$ep_state_mcb = trim(mysql_real_escape_string($_POST[ep_state_mcb]));
$ep_state_publishing = trim(mysql_real_escape_string($_POST[ep_state_publishing]));
$ep_state_doc = trim(mysql_real_escape_string($_POST[ep_state_doc]));
$ep_state_shop = trim(mysql_real_escape_string($_POST[ep_state_shop]));
$ep_state_book = trim(mysql_real_escape_string($_POST[ep_state_book]));
$ep_exposed = trim(mysql_real_escape_string($_POST[ep_exposed]));
$ep_autocode = trim(mysql_real_escape_string($_POST[ep_autocode]));
$ep_jointype = trim(mysql_real_escape_string($_POST[ep_jointype]));
$ep_language = trim(mysql_real_escape_string($_POST[ep_language]));
$ep_anonymity = trim(mysql_real_escape_string($_POST[ep_anonymity]));
$ep_domain = trim(mysql_real_escape_string($_POST[ep_domain]));
$ep_upload = trim(mysql_real_escape_string($_POST[ep_upload]));
$ep_upload_size = trim(mysql_real_escape_string($_POST[ep_upload_size]));
$ep_point_seller = trim(mysql_real_escape_string($_POST[ep_point_seller]));
$ep_point_site = trim(mysql_real_escape_string($_POST[ep_point_site]));
$ep_point_super = trim(mysql_real_escape_string($_POST[ep_point_super]));
$ep_copy_off = trim(mysql_real_escape_string($_POST[ep_copy_off]));
$ep_expiry_date = trim(mysql_real_escape_string($_POST[ep_expiry_date]));
$ep_charge = str_replace(",","",trim(mysql_real_escape_string($_POST[ep_charge])));
$adm_email = trim(mysql_real_escape_string($_POST[adm_email]));

$rowdomain = sql_fetch(" select count(*) as cnt from $iw[enterprise_table] where ep_domain = '$ep_domain' and ep_no <> '$ep_no'");
if ($ep_domain && $rowdomain[cnt]) {
	alert("도메인이 이미 존재합니다.","");
}else{
	$sql = "update $iw[enterprise_table] set
			ep_corporate = '$ep_corporate',
			ep_permit_number = '$ep_permit_number',
			ep_state_mcb = '$ep_state_mcb',
			ep_state_publishing = '$ep_state_publishing',
			ep_state_doc = '$ep_state_doc',
			ep_state_shop = '$ep_state_shop',
			ep_state_book = '$ep_state_book',
			ep_language = '$ep_language',
			ep_exposed = '$ep_exposed',
			ep_autocode = '$ep_autocode',
			ep_jointype = '$ep_jointype',
			ep_anonymity = '$ep_anonymity',
			ep_domain = '$ep_domain',
			ep_upload = '$ep_upload',
			ep_upload_size = '$ep_upload_size',
			ep_point_seller = '$ep_point_seller',
			ep_point_site = '$ep_point_site',
			ep_point_super = '$ep_point_super',
			ep_copy_off = '$ep_copy_off',
			ep_charge = '$ep_charge',
			ep_expiry_date = '$ep_expiry_date',
			admin_email = '$adm_email'
			where ep_no = '$ep_no'";

	sql_query($sql);

	alert("사이트 정보가 수정되었습니다.","$iw[super_path]/enterprise_view.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&idx=$ep_no");
}
?>
<?php
include_once("_common.php");
if ($iw['level'] != "super") alert("잘못된 접근입니다!","");
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?php
$ep_no = $_POST[ep_no];
$ep_corporate = trim(sql_real_escape_string($_POST['ep_corporate']));
$ep_permit_number = trim(sql_real_escape_string($_POST['ep_permit_number']));
$ep_state_mcb = trim(sql_real_escape_string($_POST['ep_state_mcb']));
$ep_state_publishing = trim(sql_real_escape_string($_POST['ep_state_publishing']));
$ep_state_doc = trim(sql_real_escape_string($_POST['ep_state_doc']));
$ep_state_shop = trim(sql_real_escape_string($_POST['ep_state_shop']));
$ep_state_book = trim(sql_real_escape_string($_POST['ep_state_book']));
$ep_exposed = trim(sql_real_escape_string($_POST['ep_exposed']));
$ep_autocode = trim(sql_real_escape_string($_POST['ep_autocode']));
$ep_jointype = trim(sql_real_escape_string($_POST['ep_jointype']));
$ep_language = trim(sql_real_escape_string($_POST['ep_language']));
$ep_anonymity = trim(sql_real_escape_string($_POST['ep_anonymity']));
$ep_domain = trim(sql_real_escape_string($_POST['ep_domain']));
$ep_upload = trim(sql_real_escape_string($_POST['ep_upload']));
$ep_upload_size = trim(sql_real_escape_string($_POST['ep_upload_size']));
$ep_point_seller = trim(sql_real_escape_string($_POST['ep_point_seller']));
$ep_point_site = trim(sql_real_escape_string($_POST['ep_point_site']));
$ep_point_super = trim(sql_real_escape_string($_POST['ep_point_super']));
$ep_copy_off = trim(sql_real_escape_string($_POST['ep_copy_off']));
$ep_expiry_date = trim(sql_real_escape_string($_POST['ep_expiry_date']));
$ep_charge = str_replace(",","",trim(sql_real_escape_string($_POST['ep_charge'])));
$adm_email = trim(sql_real_escape_string($_POST['adm_email']));

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




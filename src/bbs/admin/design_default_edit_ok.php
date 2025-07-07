<?php
include_once("_common.php");
if (($iw['group'] == "all" && $iw['level'] != "admin") || ($iw['group'] != "all" && $iw['gp_level'] != "gp_admin")) alert("잘못된 접근입니다!","");
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?php
$upload_path = $_POST['upload_path'];
$st_no = trim(mysqli_real_escape_string($iw['connect'], $_POST['st_no']));
$st_title = trim(mysqli_real_escape_string($iw['connect'], $_POST['st_title']));
$st_content = trim(mysqli_real_escape_string($iw['connect'], $_POST['st_content']));
$state_sort = trim(mysqli_real_escape_string($iw['connect'], $_POST['state_sort']));
$st_mcb_name = trim(mysqli_real_escape_string($iw['connect'], $_POST['st_mcb_name']));
$st_publishing_name = trim(mysqli_real_escape_string($iw['connect'], $_POST['st_publishing_name']));
$st_doc_name = trim(mysqli_real_escape_string($iw['connect'], $_POST['st_doc_name']));
$st_shop_name = trim(mysqli_real_escape_string($iw['connect'], $_POST['st_shop_name']));
$st_book_name = trim(mysqli_real_escape_string($iw['connect'], $_POST['st_book_name']));
$st_top_img_old = trim(mysqli_real_escape_string($iw['connect'], $_POST['st_top_img_old']));
$st_favicon_old = trim(mysqli_real_escape_string($iw['connect'], $_POST['st_favicon_old']));
$hm_code = trim(mysqli_real_escape_string($iw['connect'], $_POST['hm_code']));
$st_top_align = trim(mysqli_real_escape_string($iw['connect'], $_POST['st_top_align']));
$st_menu_position = trim(mysqli_real_escape_string($iw['connect'], $_POST['st_menu_position']));
$st_menu_mobile = trim(mysqli_real_escape_string($iw['connect'], $_POST['st_menu_mobile']));
$st_navigation = trim(mysqli_real_escape_string($iw['connect'], $_POST['st_navigation']));
$st_slide_size = trim(mysqli_real_escape_string($iw['connect'], $_POST['st_slide_size']));
$st_sns_share = trim(mysqli_real_escape_string($iw['connect'], $_POST['st_sns_share']));

$abs_dir = $iw['path'].$upload_path;

if($_FILES["st_top_img"]["name"] && $_FILES["st_top_img"]["size"]>0){
	$st_top_img_name = uniqid(rand());

	$filename = $st_top_img_name.".".preg_replace('/^.*\.([^.]+)$/D', '$1',$_FILES["st_top_img"]["name"]);
	$result = move_uploaded_file($_FILES["st_top_img"]["tmp_name"], "{$abs_dir}/{$filename}");
	
	if($result){
		$sql = "update $iw['setting_table'] set
			st_top_img = '$filename'
			where st_no = '$st_no' and ep_code = '$iw['store']' and gp_code = '$iw['group']'
			";
		sql_query($sql);

		if(is_file($abs_dir."/".$st_top_img_old)==true){
			unlink($abs_dir."/".$st_top_img_old);
		}
	}else{
		alert("이미지 첨부에러.","");
	}
}

if($_FILES["st_favicon"]["name"] && $_FILES["st_favicon"]["size"]>0){
	$st_favicon_name = uniqid(rand());

	$filename = $st_favicon_name.".".preg_replace('/^.*\.([^.]+)$/D', '$1',$_FILES["st_favicon"]["name"]);
	$result = move_uploaded_file($_FILES["st_favicon"]["tmp_name"], "{$abs_dir}/{$filename}");
	
	if($result){
		$sql = "update $iw['setting_table'] set
			st_favicon = '$filename'
			where st_no = '$st_no' and ep_code = '$iw['store']' and gp_code = '$iw['group']'
			";
		sql_query($sql);

		if(is_file($abs_dir."/".$st_favicon_old)==true){
			unlink($abs_dir."/".$st_favicon_old);
		}
	}else{
		alert("이미지 첨부에러.","");
	}
}

for ($i=0; $i<5; $i++) {
	$st_background_before = trim(mysqli_real_escape_string($iw['connect'], $_POST['st_background_before'][$i]));
	if($_POST['st_background_del'][$i] == "del"){
			if(is_file("{$abs_dir}/{$st_background_before}")==true){
				unlink("{$abs_dir}/{$st_background_before}");
			}
	}else{
		if($_FILES["st_background"]["name"][$i] && $_FILES["st_background"]["size"][$i]>0){
			$st_background_name = uniqid(rand());

			$filename = $st_background_name.".".preg_replace('/^.*\.([^.]+)$/D', '$1',$_FILES["st_background"]["name"][$i]);
			$result = move_uploaded_file($_FILES["st_background"]["tmp_name"][$i], "{$abs_dir}/{$filename}");
			
			if($result){
				$st_background .= $filename.",";

				if(is_file($abs_dir."/".$st_background_before)==true){
					unlink($abs_dir."/".$st_background_before);
				}
			}else{
				alert("이미지 첨부에러.","");
			}
		}else{
			if($st_background_before){
				$st_background .= $st_background_before.",";
			}
		}
	}
}

$st_datetime = date("Y-m-d H:i:s");

$sql = "update $iw['setting_table'] set
		st_title = '$st_title',
		st_content = '$st_content',
		state_sort = '$state_sort',
		st_background = '$st_background',
		st_mcb_name = '$st_mcb_name',
		st_publishing_name = '$st_publishing_name',
		st_doc_name = '$st_doc_name',
		st_shop_name = '$st_shop_name',
		st_book_name = '$st_book_name',
		st_datetime = '$st_datetime',
		hm_code = '$hm_code',
		st_top_align = '$st_top_align',
		st_menu_position = '$st_menu_position',
		st_menu_mobile = '$st_menu_mobile',
		st_navigation = '$st_navigation',
		st_slide_size = '$st_slide_size',
		st_sns_share = '$st_sns_share'
		where st_no = '$st_no' and ep_code = '$iw['store']' and gp_code = '$iw['group']'
		";

sql_query($sql);


if($iw['group'] == "all"){
	$ep_footer = mysqli_real_escape_string($iw['connect'], $_POST['contents1']);
	$ep_copy_off = trim(mysqli_real_escape_string($iw['connect'], $_POST['ep_copy_off']));
	$ep_terms_display = trim(mysqli_real_escape_string($iw['connect'], $_POST['ep_terms_display']));
	
	$sql = "update $iw['enterprise_table'] set
			ep_footer = '$ep_footer',
			ep_copy_off = '$ep_copy_off',
			ep_terms_display = '$ep_terms_display'
			where ep_code = '$iw['store']' and mb_code = '$iw['member']'
			";
	sql_query($sql);
}

alert("기본 설정이 수정되었습니다.","$iw['admin_path']/design_default_edit.php?type=$iw['type']&ep=$iw['store']&gp=$iw['group']");
?>




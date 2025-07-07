<?php
include_once("_common.php");
if ($iw['type'] != "book" || ($iw['level'] != "seller" && $iw['level'] != "member")) alert("잘못된 접근입니다!","");
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?php
$bd_code = trim(sql_real_escape_string($_POST['bd_code']));
$cg_code = trim(sql_real_escape_string($_POST['cg_code']));
$bd_image = trim(sql_real_escape_string($_POST['bd_image']));
$bd_type = trim(sql_real_escape_string($_POST['bd_type']));
$bd_subject = trim(sql_real_escape_string($_POST['bd_subject']));
$bd_author = trim(sql_real_escape_string($_POST['bd_author']));
$bd_publisher = trim(sql_real_escape_string($_POST['bd_publisher']));
$bd_price = trim(sql_real_escape_string($_POST['bd_price']));
$bd_tag = trim(sql_real_escape_string($_POST['bd_tag']));
$bd_content = sql_real_escape_string($_POST['contents1']);

$row = sql_fetch(" select * from {$iw['category_table']} where ep_code = '{$iw['store']}' and gp_code = '{$iw['group']}' and state_sort = '{$iw['type']}' and cg_code = '$cg_code' ");
if (!$row['cg_code']) {
	alert("카테고리가 존재하지 않습니다.","");
}else if($iw['mb_level'] < $row['cg_level_write']){
	alert("해당 카테고리에 글쓰기 권한이 없습니다.","");
}else{
	function rmdirAll($dir) {
		   $dirs = dir($dir);
	   while(false !== ($entry = $dirs->read())) {
		  if(($entry != '.') && ($entry != '..')) {
			 if(is_dir($dir.'/'.$entry)) {
				rmdirAll($dir.'/'.$entry);
			 } else {
				@unlink($dir.'/'.$entry);
			 }
		   }
		}
		$dirs->close();
		@rmdir($dir);
	}

	$abs_dir = $iw['path'].$_POST['upload_path'];
	if($_FILES["bd_image"]["name"] && $_FILES["bd_image"]["size"]>0){
		@mkdir($abs_dir, 0707);
		@chmod($abs_dir, 0707);

		$bd_image_name = uniqid(rand());

		$bd_image = $bd_image_name.".".preg_replace('/^.*\.([^.]+)$/D', '$1',$_FILES["bd_image"]["name"]);
		$result = move_uploaded_file($_FILES["bd_image"]["tmp_name"], "{$abs_dir}/{$bd_image}");
		
		if(!$result){
			rmdirAll($abs_dir);
			alert("표지 첨부에러.","");
		}
	}
	$bd_datetime = date("Y-m-d H:i:s");

	$sql = "insert into {$iw['book_data_table']} set
			bd_code = '$bd_code',
			ep_code = '{$iw['store']}',
			gp_code = '{$iw['group']}',
			mb_code = '{$iw['member']}',
			cg_code = '$cg_code',
			bd_image = '$bd_image',
			bd_type = '$bd_type',
			bd_subject = '$bd_subject',
			bd_author = '$bd_author',
			bd_publisher = '$bd_publisher',
			bd_price = '$bd_price',
			bd_tag = '$bd_tag',
			bd_content = '$bd_content',
			bd_datetime = '$bd_datetime',
			bd_display = 0
			";

	sql_query($sql);

	$sql = "insert into {$iw['total_data_table']} set
			td_code = '$bd_code',
			cg_code = '$cg_code',
			ep_code = '{$iw['store']}',
			gp_code = '{$iw['group']}',
			state_sort = '{$iw['type']}',
			td_title = '$bd_subject',
			td_datetime = '$bd_datetime',
			td_edit_datetime = '$bd_datetime'
			";
	sql_query($sql);

	alert("이북정보가 등록되었습니다.","{$iw['admin_path']}/book_data_list.php?type={$iw['type']}&ep={$iw['store']}&gp={$iw['group']}");
}
?>




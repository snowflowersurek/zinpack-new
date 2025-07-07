<?php
$iw_path = "../.."; // common.php 의 상대 경로
include_once("$iw_path/include/common.php");
include_once("$iw['include_path']/member_check.php");
if ($iw['group'] != "all" && $iw['level']=="guest") alert(national_language($iw['language'],"a0003","로그인 해주시기 바랍니다."),"$iw['m_path']/all_login.php?type=$iw['type']&ep=$iw['store']&gp=$iw['group']");
if ($iw['gp_level'] != "gp_guest" || $iw['group'] == "all") alert(national_language($iw['language'],"a0035","잘못된 접근입니다."),"");
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?php
$gp_no = trim(mysqli_real_escape_string($iw['connect'], $_POST['gp_no']));

$sql = "select * from $iw['group_table'] where ep_code = '$iw['store']' and gp_no = '$gp_no'";
$row = sql_fetch($sql);
if (!$row["gp_no"]) alert(national_language($iw['language'],"a0035","잘못된 접근입니다."),"");

$gp_code = $row["gp_code"];
$gp_type = $row["gp_type"];
$gp_autocode = $row["gp_autocode"];

$sql = "select * from $iw['member_table'] where ep_code = '$iw['store']' and mb_code = '$iw['member']'";
$row = sql_fetch($sql);
if (!$row["mb_no"]) alert(national_language($iw['language'],"a0035","잘못된 접근입니다."),"");
$mb_name = $row["mb_name"];
$mb_mail = $row["mb_mail"];
$mb_tel = $row["mb_tel"];

$row = sql_fetch(" select count(*) as cnt from $iw['group_member_table'] where ep_code = '$iw['store']' and gp_code = '$gp_code' and mb_code = '$iw['member']' ");
if ($row['cnt']) {
	alert(national_language($iw['language'],"a0057","이미 가입신청이 되었거나, 회원이십니다."),"");
}else{
	$gm_datetime = date("Y-m-d H:i:s");

	if ($gp_type == 0){
		alert(national_language($iw['language'],"a0058","해당 그룹은 가입이 불가능합니다."),"");
	}else if ($gp_type == 1){
		$sql = "insert into $iw['group_member_table'] set
				ep_code = '$iw['store']',
				gp_code = '$gp_code',
				mb_code = '$iw['member']',
				gm_datetime = '$gm_datetime',
				gm_display = 0
				";
		sql_query($sql);
		alert(national_language($iw['language'],"a0059","그룹에 가입이 신청되었습니다."),"$iw['m_path']/main.php?type=main&ep=$iw['store']&gp=$iw['group']");
	}else if ($gp_type == 2){
		$sql = "insert into $iw['group_member_table'] set
				ep_code = '$iw['store']',
				gp_code = '$gp_code',
				mb_code = '$iw['member']',
				gm_datetime = '$gm_datetime',
				gm_display = 1
				";
		sql_query($sql);
		alert(national_language($iw['language'],"a0060","그룹에 가입되었습니다."),"$iw['m_path']/main.php?type=main&ep=$iw['store']&gp=$iw['group']");
	}else if ($gp_type == 4){
		$row = sql_fetch(" select * from $iw['group_invite_table'] where ep_code = '$iw['store']' and gp_code = '$gp_code' and gi_display = 0 and if(gi_name_check = 1, gi_name = '$mb_name', gi_name_check=0) and if(gi_mail_check = 1, gi_mail = '$mb_mail', gi_mail_check=0) and if(gi_tel_check = 1, gi_tel = '$mb_tel', gi_tel_check=0) ");
		if ($row["gi_no"]) {
			$gi_no = $row["gi_no"];
			$gi_datetime_end = $row["gi_datetime_end"];
			if ($gi_datetime_end >= $gm_datetime){
				$sql = "update $iw['group_invite_table'] set
						mb_code = '$iw['member']',
						gi_display = 1
						where ep_code = '$iw['store']' and gp_code = '$gp_code' and gi_no = '$gi_no' and gi_display = 0
						";
				sql_query($sql);

				$sql = "insert into $iw['group_member_table'] set
						ep_code = '$iw['store']',
						gp_code = '$gp_code',
						mb_code = '$iw['member']',
						gm_datetime = '$gm_datetime',
						gm_display = 1
						";
				sql_query($sql);
				alert(national_language($iw['language'],"a0060","그룹에 가입되었습니다."),"$iw['m_path']/main.php?type=main&ep=$iw['store']&gp=$iw['group']");
			}else{
				alert(national_language($iw['language'],"a0061","가입할수 있는 날짜를 초과하였습니다."),"");
			}
		}else{
			alert(national_language($iw['language'],"a0062","초대자 명단에 일치하는 회원정보가 없습니다."),"");
		}
	}else if ($gp_type == 5){
		$autocode = trim(mysqli_real_escape_string($iw['connect'], $_POST['gp_autocode']));
		if($gp_autocode == $autocode){
			$sql = "insert into $iw['group_member_table'] set
					ep_code = '$iw['store']',
					gp_code = '$gp_code',
					mb_code = '$iw['member']',
					gm_datetime = '$gm_datetime',
					gm_display = 1
					";
			sql_query($sql);
			alert(national_language($iw['language'],"a0060","그룹에 가입되었습니다."),"$iw['m_path']/main.php?type=main&ep=$iw['store']&gp=$iw['group']");
		}else{
			alert(national_language($iw['language'],"a0063","가입코드를 확인하여 주십시오."),"");
		}
	}else{
		 alert(national_language($iw['language'],"a0035","잘못된 접근입니다."),"");
	}
}

?>




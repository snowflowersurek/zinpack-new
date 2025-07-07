<?php
include_once("_common_guest.php");
if ($iw['level']=="guest") alert(national_language($iw['language'],"a0003","로그인 해주시기 바랍니다."),"$iw[m_path]/all_login.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]");

global $db_conn;
if (!$db_conn) {
    $db_conn = $connect_db;
}
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?php
function removeDir($dir) {
	$dirs = dir($dir);
	
	while (false !== ($entry = $dirs->read())) {
		if (($entry != '.') && ($entry != '..')) {
			if (is_dir($dir.'/'.$entry)) {
				removeDir($dir.'/'.$entry);
			} else {
				@unlink($dir.'/'.$entry);
			}
		}
	}
	
	$dirs->close();
	@rmdir($dir);
}

$confirm_password = trim($_POST['confirm_password']);

// 1. 비밀번호 확인
$sql_check = "select mb_password from {$iw['member_table']} where ep_code = ? and mb_code = ?";
$stmt_check = mysqli_prepare($db_conn, $sql_check);
mysqli_stmt_bind_param($stmt_check, "ss", $iw['store'], $iw['member']);
mysqli_stmt_execute($stmt_check);
$result_check = mysqli_stmt_get_result($stmt_check);
$row_member = mysqli_fetch_assoc($result_check);
mysqli_stmt_close($stmt_check);

if ($row_member && password_verify($confirm_password, $row_member['mb_password'])) {
	if ($iw['level'] == "member") {
		// 2. 게시글 전체삭제
		$sql_posts = "select md_code, gp_code from {$iw['mcb_data_table']} where ep_code = ? and mb_code = ?";
        $stmt_posts = mysqli_prepare($db_conn, $sql_posts);
        mysqli_stmt_bind_param($stmt_posts, "ss", $iw['store'], $iw['member']);
        mysqli_stmt_execute($stmt_posts);
		$result_posts = mysqli_stmt_get_result($stmt_posts);
		
		while ($row_post = mysqli_fetch_assoc($result_posts)) {
			$md_code = $row_post['md_code'];
			$gp_code = $row_post['gp_code'];
			
			// mcb_data_table 삭제
			$sql_del_mcb = "delete from {$iw['mcb_data_table']} where ep_code = ? and md_code = ?";
            $stmt_del_mcb = mysqli_prepare($db_conn, $sql_del_mcb);
            mysqli_stmt_bind_param($stmt_del_mcb, "ss", $iw['store'], $md_code);
            mysqli_stmt_execute($stmt_del_mcb);
            mysqli_stmt_close($stmt_del_mcb);
			
			// total_data_table 삭제
			$sql_del_total = "delete from {$iw['total_data_table']} where ep_code = ? and td_code = ?";
            $stmt_del_total = mysqli_prepare($db_conn, $sql_del_total);
            mysqli_stmt_bind_param($stmt_del_total, "ss", $iw['store'], $md_code);
            mysqli_stmt_execute($stmt_del_total);
            mysqli_stmt_close($stmt_del_total);
			
			// 첨부파일 삭제
			$sql_ep = "select ep_nick from {$iw['enterprise_table']} where ep_code = ?";
            $stmt_ep = mysqli_prepare($db_conn, $sql_ep);
            mysqli_stmt_bind_param($stmt_ep, "s", $iw['store']);
            mysqli_stmt_execute($stmt_ep);
            $result_ep = mysqli_stmt_get_result($stmt_ep);
			$row_ep = mysqli_fetch_assoc($result_ep);
            mysqli_stmt_close($stmt_ep);
			$upload_path = "/mcb/".$row_ep['ep_nick'];
			
			if ($gp_code == "all") {
				$upload_path .= "/all/".$md_code;
			} else {
				$sql_gp = "select gp_nick from {$iw['group_table']} where ep_code = ? and gp_code = ?";
                $stmt_gp = mysqli_prepare($db_conn, $sql_gp);
                mysqli_stmt_bind_param($stmt_gp, "ss", $iw['store'], $gp_code);
                mysqli_stmt_execute($stmt_gp);
                $result_gp = mysqli_stmt_get_result($stmt_gp);
				$row_gp = mysqli_fetch_assoc($result_gp);
                mysqli_stmt_close($stmt_gp);
				$upload_path .= "/".$row_gp['gp_nick']."/".$md_code;
			}
			removeDir($iw['path'].$upload_path);
		}
        mysqli_stmt_close($stmt_posts);
		
		// 3. 댓글 전체삭제
		$sql_del_comment = "delete from {$iw['comment_table']} where ep_code = ? and mb_code = ?";
        $stmt_del_comment = mysqli_prepare($db_conn, $sql_del_comment);
        mysqli_stmt_bind_param($stmt_del_comment, "ss", $iw['store'], $iw['member']);
        mysqli_stmt_execute($stmt_del_comment);
        mysqli_stmt_close($stmt_del_comment);
		
		// 4. 그룹회원 삭제
		$sql_del_group = "delete from {$iw['group_member_table']} where ep_code = ? and mb_code = ?";
        $stmt_del_group = mysqli_prepare($db_conn, $sql_del_group);
        mysqli_stmt_bind_param($stmt_del_group, "ss", $iw['store'], $iw['member']);
        mysqli_stmt_execute($stmt_del_group);
        mysqli_stmt_close($stmt_del_group);
		
		// 5. 회원정보 삭제
		$sql_del_member = "delete from {$iw['member_table']} where ep_code = ? and mb_code = ?";
        $stmt_del_member = mysqli_prepare($db_conn, $sql_del_member);
        mysqli_stmt_bind_param($stmt_del_member, "ss", $iw['store'], $iw['member']);
        mysqli_stmt_execute($stmt_del_member);
        mysqli_stmt_close($stmt_del_member);
		
		// 6. 로그아웃
		set_cookie("iw_member", "", time()-3600*24*365);
		
		alert("회원탈퇴가 완료되었습니다.", "{$iw['m_path']}/main.php?type=main&ep={$iw['store']}&gp={$iw['group']}");
	} else {
		alert("회원탈퇴할 수 없는 계정입니다.\n관리자에게 문의해주세요.", "");
	}
} else {
	alert(national_language($iw['language'],"a0116","비밀번호를 확인하여 주십시오."),"");
}
?>




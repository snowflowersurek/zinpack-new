<?php
include_once("_common.php");
if (($iw['gp_level'] == "gp_guest" && $iw['group'] != "all") || ($iw['level'] == "guest" && $iw['group'] == "all")) alert("로그인 후 이용해주세요!","{$iw['m_path']}/all_login.php?type={$iw['type']}&ep={$iw['store']}&gp={$iw['group']}");

global $db_conn;
if (!$db_conn) {
    $db_conn = $connect_db;
}

if (!isset($_GET['item'])) alert(national_language($iw['language'],"a0035","잘못된 접근입니다."),"");
$hm_code = $_GET['menu'];
$md_code = $_GET['item'];

$sql_check = "select md_no from {$iw['mcb_data_table']} where ep_code = ? and gp_code= ? and md_code = ?";
$stmt_check = mysqli_prepare($db_conn, $sql_check);
mysqli_stmt_bind_param($stmt_check, "sss", $iw['store'], $iw['group'], $md_code);
mysqli_stmt_execute($stmt_check);
$result_check = mysqli_stmt_get_result($stmt_check);
$row_check = mysqli_fetch_assoc($result_check);
mysqli_stmt_close($stmt_check);
if (!$row_check["md_no"]) alert(national_language($iw['language'],"a0035","잘못된 접근입니다."),"");

$sql_ep = "select ep_nick from {$iw['enterprise_table']} where ep_code = ?";
$stmt_ep = mysqli_prepare($db_conn, $sql_ep);
mysqli_stmt_bind_param($stmt_ep, "s", $iw['store']);
mysqli_stmt_execute($stmt_ep);
$result_ep = mysqli_stmt_get_result($stmt_ep);
$row_ep = mysqli_fetch_assoc($result_ep);
mysqli_stmt_close($stmt_ep);
$upload_path = "/{$iw['type']}/".$row_ep['ep_nick'];

if ($iw['group'] == "all"){
	$upload_path .= "/all";
}else{
	$sql_gp = "select gp_nick from {$iw['group_table']} where ep_code = ? and gp_code = ?";
    $stmt_gp = mysqli_prepare($db_conn, $sql_gp);
    mysqli_stmt_bind_param($stmt_gp, "ss", $iw['store'], $iw['group']);
    mysqli_stmt_execute($stmt_gp);
    $result_gp = mysqli_stmt_get_result($stmt_gp);
	$row_gp = mysqli_fetch_assoc($result_gp);
    mysqli_stmt_close($stmt_gp);
	$upload_path .= "/".$row_gp['gp_nick'];
}
$upload_path .= "/".$md_code;
$abs_dir = $iw['path'].$upload_path;

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
rmdirAll($abs_dir);

$sql_del_mcb = "delete from {$iw['mcb_data_table']} where ep_code = ? and gp_code = ? and md_code = ?";
$stmt_del_mcb = mysqli_prepare($db_conn, $sql_del_mcb);
mysqli_stmt_bind_param($stmt_del_mcb, "sss", $iw['store'], $iw['group'], $md_code);
mysqli_stmt_execute($stmt_del_mcb);
mysqli_stmt_close($stmt_del_mcb);

$sql_del_comment = "delete from {$iw['comment_table']} where ep_code = ? and gp_code = ? and state_sort = ? and cm_code = ?";
$stmt_del_comment = mysqli_prepare($db_conn, $sql_del_comment);
mysqli_stmt_bind_param($stmt_del_comment, "ssss", $iw['store'], $iw['group'], $iw['type'], $md_code);
mysqli_stmt_execute($stmt_del_comment);
mysqli_stmt_close($stmt_del_comment);

$sql_del_total = "delete from {$iw['total_data_table']} where ep_code = ? and gp_code = ? and state_sort = ? and td_code = ?";
$stmt_del_total = mysqli_prepare($db_conn, $sql_del_total);
mysqli_stmt_bind_param($stmt_del_total, "ssss", $iw['store'], $iw['group'], $iw['type'], $md_code);
mysqli_stmt_execute($stmt_del_total);
mysqli_stmt_close($stmt_del_total);

alert(national_language($iw['language'],"a0181","글이 삭제되었습니다."),"{$iw['m_path']}/all_data_list.php?type={$iw['type']}&ep={$iw['store']}&gp={$iw['group']}&menu={$hm_code}");
?>




<?php
include_once("_common.php");
if (($iw['gp_level'] == "gp_guest" && $iw['group'] != "all") || ($iw['level'] == "guest" && $iw['group'] == "all")) alert(national_language($iw['language'],"a0032","로그인 후 이용해주세요"),"{$iw['m_path']}/all_login.php?type={$iw['type']}&ep={$iw['store']}&gp={$iw['group']}");

global $db_conn;
if (!$db_conn) {
    $db_conn = $connect_db;
}
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?php
	$sd_code = trim($_POST['sd_code']);
	$sd_mb_code = trim($_POST['sd_mb_code']);
	$sy_group = trim($_POST['sy_group']);

	if (is_array($_POST['so_no'])) {
		for ($i=0; $i<count($_POST['so_no']); $i++) {
			$so_no = (int)$_POST['so_no'][$i];
			$so_amount = (int)$_POST['so_amount'][$i];

            if ($so_amount <= 0) continue;

            // 기존 장바구니 항목 확인
            $sql_check = "select sc_no from {$iw['shop_cart_table']} where ep_code = ? and mb_code = ? and sd_code = ? and so_no = ?";
            $stmt_check = mysqli_prepare($db_conn, $sql_check);
            mysqli_stmt_bind_param($stmt_check, "sssi", $iw['store'], $iw['member'], $sd_code, $so_no);
            mysqli_stmt_execute($stmt_check);
            $result_check = mysqli_stmt_get_result($stmt_check);
            $row_check = mysqli_fetch_assoc($result_check);
            mysqli_stmt_close($stmt_check);
			
			if ($row_check) {
                // 수량 업데이트
				$sql = "update {$iw['shop_cart_table']} set sc_amount = sc_amount + ? where sc_no = ?";
                $stmt = mysqli_prepare($db_conn, $sql);
                mysqli_stmt_bind_param($stmt, "ii", $so_amount, $row_check['sc_no']);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
			}else{
                // 신규 삽입
				$sql = "insert into {$iw['shop_cart_table']} set
						ep_code = ?, gp_code = ?, mb_code = ?, sd_code = ?, seller_mb_code = ?,
						so_no = ?, sc_amount = ?, sd_delivery_type = ?";
                $stmt = mysqli_prepare($db_conn, $sql);
                mysqli_stmt_bind_param($stmt, "sssssiis", $iw['store'], $iw['group'], $iw['member'], $sd_code, $sd_mb_code, $so_no, $so_amount, $sy_group);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
			}
		}

        // 배송 그룹 업데이트 (해당 상품 전체에 대해)
		$sql_deliv = "update {$iw['shop_cart_table']} set sd_delivery_type = ? where ep_code = ? and mb_code = ? and sd_code = ?";
        $stmt_deliv = mysqli_prepare($db_conn, $sql_deliv);
        mysqli_stmt_bind_param($stmt_deliv, "ssss", $sy_group, $iw['store'], $iw['member'], $sd_code);
        mysqli_stmt_execute($stmt_deliv);
        mysqli_stmt_close($stmt_deliv);
	}

	goto_url("{$iw['m_path']}/shop_cart_form.php?type={$iw['type']}&ep={$iw['store']}&gp={$iw['group']}");
?>




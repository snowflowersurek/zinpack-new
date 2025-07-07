<?php
include_once("_common_guest.php");
$iw[type] = "mcb";
include_once("_head.php");
?>

<div class="breadcrumb-box input-group">
	<ol class="breadcrumb ">
		<li><a href="#"><?=national_language($iw[language],"a0037","이메일 인증");?></a></li>
	</ol>
</div>
<div class="content">
<?php
	$mb_code = $_GET['mb'];
	
	$row = sql_fetch(" select count(*) as cnt from $iw[member_table] where ep_code ='$iw[store]' and mb_code = '$mb_code' and mb_display = 0 ");
	
	if ($row[cnt]) {
		$row2 = sql_fetch("select ep_jointype from $iw[enterprise_table] where ep_code = '$iw[store]'");
		$ep_jointype = $row2["ep_jointype"];
		
		if ($ep_jointype == 1) {
			$sql = "update $iw[member_table] set
					mb_display = 3
					where mb_code = '$mb_code' and ep_code ='$iw[store]' and mb_display = 0 ";
		} else {
			$sql = "update $iw[member_table] set
					mb_display = 1
					where mb_code = '$mb_code' and ep_code ='$iw[store]' and mb_display = 0 ";
		}
		sql_query($sql);

		if ($ep_jointype == 1) {
			echo '
			<div class="alert alert-warning">
				<p>관리자 가입인증 후 로그인이 가능합니다.</p>
			</div>
			';
		} else {
			echo '
			<div class="alert alert-success">
				<p>이메일 인증이 완료되었습니다.</p>
				<br/>
				<p><a href="'.$iw['m_path'].'/all_login.php?type='.$iw[type].'&ep='.$iw[store].'&gp='.$iw[group].'" class="btn btn-theme">'.national_language($iw[language],"a0016","로그인").'</a></p>
			</div>
			';
		}
	} else {
		$row2 = sql_fetch(" select count(*) as cnt from $iw[member_table] where ep_code ='$iw[store]' and mb_code = '$mb_code' ");
		
		if ($row2[cnt]) {
			echo '
			<div class="alert alert-success">
				<p>인증 완료된 이메일입니다.</p>
			</div>
			';
		} else {
			echo '
			<div class="alert alert-danger">
				<p>인증기간이 만료되었거나 가입 이메일이 존재하지 않습니다.</p>
			</div>
			';
		}
	}
?>
</div>
<?php
include_once("_tail.php");
?>




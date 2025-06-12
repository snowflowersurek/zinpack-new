<?php
include_once("_common_guest.php");
$iw[type] = "mcb";
include_once("_head.php");
?>

<div class="breadcrumb-box input-group">
	<ol class="breadcrumb ">
		<li><a href="<?=$iw[m_path]?>/main.php?type=main&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>"><?=$st_main_name?></a></li>
		<li><a href="#"><?=national_language($iw[language],"a0029","계좌정보 인증");?></a></li>
	</ol>
</div>
<div class="content">
	<?
		$mb_code = $_GET['mb'];
		$ac_datetime = $_GET['date'];
		$row = sql_fetch(" select count(*) as cnt from $iw[account_table] where mb_code = '$mb_code' and ep_code ='$iw[store]' and ac_datetime ='$ac_datetime' and ac_display = 0 ");
		if ($row[cnt]) {
			$sql = "update $iw[account_table] set
				ac_display = 1
				where mb_code = '$mb_code' and ep_code ='$iw[store]' and ac_display = 0 ";

			sql_query($sql);
	?>
		<div class="alert alert-success">
			<p><?=national_language($iw[language],"a0030","계좌정보 인증이 완료되었습니다.");?></p>
		</div>
	<?}else{?>
		<div class="alert alert-danger">
			<p><?=national_language($iw[language],"a0031","이미 인증되었거나, 계좌정보가 존재하지 않습니다.");?></p>
		</div>
	<?}?>
</div>
<?
include_once("_tail.php");
?>
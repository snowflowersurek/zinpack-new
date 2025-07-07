<?php
include_once("_common.php");
if (($iw[gp_level] == "gp_guest" && $iw[group] != "all") || ($iw[level] == "guest" && $iw[group] == "all")) alert(national_language($iw[language],"a0003","로그인 해주시기 바랍니다."),"$iw[m_path]/all_login.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&re_url=$iw[re_url]");
include_once("_head.php");

if(!$_GET["seller"]) alert(national_language($iw[language],"a0035","잘못된 접근입니다."),"");
$seller_mb_code = $_GET["seller"];

$sql = "select mb_nick from $iw[member_table] where mb_code = '$seller_mb_code' and ep_code = '$iw[store]'";
$row = sql_fetch($sql);
if($iw['anonymity']==0){
	$seller_mb_nick = $row["mb_nick"];
}else{
	$seller_mb_nick = cut_str($row["mb_nick"],4,"")."*****";
}

$sql = "select * from $iw[member_table] where mb_code = '$iw[member]' and ep_code = '$iw[store]'";
$row = sql_fetch($sql);
$mb_point = $row["mb_point"];
$mb_nick = $row["mb_nick"];

echo "<script>";
echo "var point_total = $mb_point;";
echo "</script>";
?>

<div class="breadcrumb-box input-group">
	<ol class="breadcrumb ">
		<li><a href="#"><?=$st_mcb_name?> <?=national_language($iw[language],"a0199","Point 후원하기");?></a></li>
	</ol>
</div>

<div class="content">
	<div class="box br-theme">
		<form id="ms_form" name="ms_form" action="<?=$iw['m_path']?>/mcb_point_give_ok.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" method="post">
			<input type="hidden" name="seller_mb_code" value="<?=$seller_mb_code?>" />
			<div class="form-group">
				<label for=""><?=national_language($iw[language],"a0137","잔여포인트");?></label>
				<input type="text" class="form-control" value="<?=$mb_point?> Point" readonly />
			</div>
			<div class="form-group">
				<label for=""><?=national_language($iw[language],"a0200","받으실분");?></label>
				<input type="text" class="form-control" value="<?=$seller_mb_nick?>" readonly />
			</div>
			<div class="form-group">
				<label for=""><?=national_language($iw[language],"a0201","선물금액");?></label>
				<input type="text" class="form-control" name="ms_price" maxlength="9" value="0" onKeyUp="onlyNum();" />
			</div>
			<div class="form-group">
				<label for=""><?=national_language($iw[language],"a0202","선물메세지");?></label>
				<input type="text" class="form-control" name="ms_subject" maxlength="100" value="<?=$mb_nick?> <?=national_language($iw[language],"a0203","님의 Point 선물");?>" />
			</div>
			<div class="btn-list">
				<a class="btn btn-theme" href="javascript:check_form();"><?=national_language($iw[language],"a0170","후원하기");?></a>
				<a class="btn btn-theme" href="<?=$iw[m_path]?>/all_point_charge.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>">포인트 충전</a>
			</div>
		</form>
	</div> <!-- /.box --> 
</div>

<script type="text/javascript">
	function check_form() {
		if (ms_form.ms_price.value <= 0) {
			alert("<?=national_language($iw[language],'a0204','선물금액을 입력하여 주십시오.');?>");
			ms_form.ms_price.focus();
			return;
		}
		if (ms_form.ms_subject.value == "") {
			alert("<?=national_language($iw[language],'a0205','선물메세지 내용을 입력하여 주십시오.');?>");
			ms_form.ms_subject.focus();
			return;
		}
		ms_form.submit();
	}

	function onlyNum(){
		var e1 = event.srcElement;
		var num ="0123456789";
		event.returnValue = true;
	   
		for (var i=0;i<e1.value.length;i++){
			if(-1 == num.indexOf(e1.value.charAt(i)))
			event.returnValue = false;
			if(i == 0 && e1.value.charAt(i) == "0")
			event.returnValue = false;
		}
		if (!event.returnValue || point_total < e1.value)
			e1.value="0";
	}
</script>

<?php
include_once("_tail.php");
?>





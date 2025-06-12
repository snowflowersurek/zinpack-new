<?php
$iw_path = "../.."; // common.php 의 상대 경로
include_once("$iw_path/include/common.php");
include_once("$iw[include_path]/member_check.php");

$cate_home = $iw[type];
if($iw[type]=="main"){
	$sql = "select state_sort from $iw[setting_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]'";
	$row = sql_fetch($sql);
	$state_sort = $row["state_sort"];

	if($state_sort != "main"){
		goto_url($iw[m_path]."/main.php?type=".$state_sort."&ep=".$iw[store]."&gp=".$iw[group]);
	}else{
		$iw[type] = "mcb";
	}
}

if ($iw[group] != "all" && $iw[level]=="guest") alert(national_language($iw[language],"a0003","로그인 해주시기 바랍니다."),"$iw[m_path]/all_login.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]");
if ($iw[gp_level] != "gp_guest" || $iw[group] == "all") alert(national_language($iw[language],"a0035","잘못된 접근입니다."),"");

include_once("_head.php");

$row = sql_fetch(" select ep_nick from $iw[enterprise_table] where ep_code = '$iw[store]' ");
$ep_nick = $row["ep_nick"];

$sql = "select * from $iw[group_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]'";
$row = sql_fetch($sql);
if (!$row["gp_no"]) alert(national_language($iw[language],"a0035","잘못된 접근입니다."),"");
$gp_no = $row["gp_no"];
$gp_nick = $row["gp_nick"];
$gp_subject = $row["gp_subject"];
$gp_content = $row["gp_content"];
$gp_type = $row["gp_type"];

if($gp_type == 0){
	$gp_type_name = national_language($iw[language],"a0046","가입 불가");
}else if($gp_type == 1){
	$gp_type_name = national_language($iw[language],"a0047","가입 신청 > 관리자 승인");
}else if($gp_type == 2){
	$gp_type_name = national_language($iw[language],"a0048","무조건 가입 > 자동 승인");
}else if($gp_type == 4){
	$gp_type_name = national_language($iw[language],"a0049","초대후 가입 > 자동 승인");
}else if($gp_type == 5){
	$gp_type_name = national_language($iw[language],"a0050","가입코드 입력 > 자동 승인");
}
?>

<div class="breadcrumb-box input-group">
	<ol class="breadcrumb ">
		<li><a href="#"><?=national_language($iw[language],"a0041","그룹 가입");?></a></li>
	</ol>
</div>
<div class="content">
	<div class="box br-theme">
		<form id="gp_form" name="gp_form" action="<?=$iw['m_path']?>/all_group_join_ok.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" method="post">
		<input type="hidden" name="gp_no" value="<?=$gp_no?>" />
			<div class="form-group">
				<label for=""><?=national_language($iw[language],"a0042","그룹이름");?></label>
				<input type="text" class="form-control" value="<?=$gp_subject?>" readonly />
			</div>
			<div class="form-group">
				<label for=""><?=national_language($iw[language],"a0043","그룹설명");?></label>
				<input type="text" class="form-control" value="<?=$gp_content?>" readonly />
			</div>
			<div class="form-group">
				<label for=""><?=national_language($iw[language],"a0044","그룹URL");?></label>
				<input type="text" class="form-control" value="<?=$iw['url']?>/main/<?=$ep_nick?>/<?=$gp_nick?>" readonly />
			</div>
			<div class="form-group">
				<label for=""><?=national_language($iw[language],"a0045","가입방식");?></label>
				<input type="text" class="form-control" value="<?=$gp_type_name?>" readonly />
			</div>
			<?if($gp_type == 1){?>
				<div class="btn-list">
					<a class="btn btn-theme" href="javascript:check_form();"><?=national_language($iw[language],"a0051","가입신청");?></a>
				</div>
			<?}else if($gp_type == 2){?>
				<div class="btn-list">
					<a class="btn btn-theme" href="javascript:check_form();"><?=national_language($iw[language],"a0052","자동가입");?></a>
				</div>
			<?}else if($gp_type == 4){?>
				<div class="btn-list">
					<a class="btn btn-theme" href="javascript:check_form();"><?=national_language($iw[language],"a0053","초대자 가입");?></a>
				</div>
			<?}else if($gp_type == 5){?>
				<div class="form-group">
					<label for=""><?=national_language($iw[language],"a0056","가입코드");?></label>
					<input type="text" class="form-control" name="gp_autocode" placeholder="<?=national_language($iw[language],"a0055","가입코드 입력");?>" />
				</div>
				<div class="btn-list">
					<a class="btn btn-theme" href="javascript:check_form();"><?=national_language($iw[language],"a0054","가입코드 전송");?></a>
				</div>
			<?}?>
		</form>
	</div> <!-- /.box -->
</div>

<script type="text/javascript">
	function check_form() {
		gp_form.submit();
	}
</script>

<?
include_once("_tail.php");
?>
<?php
include_once("_common.php");
if ($iw[type] != "bank" || ($iw[level] != "seller" && $iw[level] != "member")) alert("잘못된 접근입니다!","");

include_once("_head.php");

$row = sql_fetch(" select count(*) as cnt from $iw[account_table] where ep_code = '$iw[store]' and mb_code = '$iw[member]' ");
if (!$row[cnt]) {

	$row2 = sql_fetch("select mb_datetime from $iw[member_table] where ep_code = '$iw[store]' and mb_code = '$iw[member]'");
	if (!$row2["mb_datetime"]) alert("잘못된 접근입니다!","");

	$sql = "insert into $iw[account_table] set
			ep_code = '$iw[store]',
			mb_code = '$iw[member]',
			ac_calculate = '$row2[mb_datetime]',
			ac_display = 0
			";
	sql_query($sql);
}

$sql = "select * from $iw[account_table] where ep_code = '$iw[store]' and mb_code = '$iw[member]'";
$row = sql_fetch($sql);

$ac_bank = $row["ac_bank"];
$ac_number = $row["ac_number"];
$ac_holder = $row["ac_holder"];
$ac_no = $row["ac_no"];
$ac_display = $row["ac_display"];
?>
<div class="breadcrumbs" id="breadcrumbs">
	<ul class="breadcrumb">
		<li>
			<i class="fa fa-money"></i>
			정산관리
		</li>
		<li class="active">계좌정보</li>
	</ul><!-- .breadcrumb -->

	<!--<div class="nav-search" id="nav-search">
		<form class="form-search">
			<span class="input-icon">
				<input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off">
				<i class="fa fa-search"></i>
			</span>
		</form>
	</div>--><!-- #nav-search -->
</div>
<div class="page-content">
	<div class="page-header">
		<h1>
			계좌정보
			<small>
				<i class="fa fa-angle-double-right"></i>
				환전계좌
			</small>
		</h1>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
			<!-- PAGE CONTENT BEGINS -->
				<form class="form-horizontal" id="ac_form" name="ac_form" action="<?=$iw['admin_path']?>/bank_account_write_ok.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" method="post">
				<input type="hidden"  name="ac_no" value="<?=$ac_no?>" />
					<div class="form-group">
						<label class="col-sm-1 control-label">예금주</label>
						<div class="col-sm-11">
							<input type="text" placeholder="입력" class="col-xs-5 col-sm-3" name="ac_holder" value="<?=$ac_holder?>" maxlength="30">
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">은행명</label>
						<div class="col-sm-11">
							<select name="ac_bank">
								<option value="">은행선택</option>
								<?php
									$category_arr = array ("기업은행", "국민은행", "우리은행", "신한은행", "하나은행", "농협", "단위농협", "SC제일은행", "외환은행", "한국씨티은행", "우체국", "경남은행", "광주은행", "대구은행", "도이치", "부산은행", "산림조합", "산업은행", "상호저축은행", "새마을금고", "수협", "신협중앙회", "전북은행", "제주은행", "BOA", "HSBC");
									for ($i=0; $i<count($category_arr); $i++) {
								?>
									<option value="<?=$category_arr[$i]?>" <?php if($ac_bank == $category_arr[$i]){?>selected<?php }?>><?=$category_arr[$i]?></option>
								<?php }?>
							</select>
						</div>
					</div>
					<div class="space-4"></div>
					
					<div class="form-group">
						<label class="col-sm-1 control-label">계좌번호</label>
						<div class="col-sm-11">
							<input type="text" placeholder="입력" class="col-xs-12 col-sm-8" name="ac_number" value="<?=$ac_number?>">
							<span class="help-block col-xs-12">계좌번호 입력 시 (-) 없이 입력해주세요.</span>
						</div>
					</div>
					<div class="clearfix form-actions">
						<div class="col-md-offset-3 col-md-9">
							<button class="btn btn-primary" type="button" onclick="javascript:check_form();">
								<i class="fa fa-check"></i>
								계좌정보 변경
							</button>
						</div>
					</div>
				</form>
			<!-- PAGE CONTENT ENDS -->
			</div><!-- /col -->
		</div><!-- /row -->
	</div><!-- /container -->
</div><!-- /end .page-content -->

<script type="text/javascript">
	function check_form() {
		if (ac_form.ac_holder.value == ""){
			alert("예금주를 입력하여 주십시오.");
			ac_form.ac_holder.focus();
			return;
		}
		if (ac_form.ac_bank.value == ""){
			alert("은행명을 선택하여 주십시오.");
			ac_form.ac_bank.focus();
			return;
		}
		if (ac_form.ac_number.value == ""){
			alert("계좌번호를 입력하여 주십시오.");
			ac_form.ac_number.focus();
			return;
		}
		var e1 = ac_form.ac_number;
		var num ="0123456789";
		event.returnValue = true;
		for (var i=0;i<e1.value.length;i++){
			if(-1 == num.indexOf(e1.value.charAt(i)))
			event.returnValue = false;
		}
		if (!event.returnValue){
			alert('숫자로만 입력가능한 항목입니다.');
			ac_form.ac_number.focus();
			return;
		}
		ac_form.submit();
	}
</script>
 
<?php
include_once("_tail.php");
?>




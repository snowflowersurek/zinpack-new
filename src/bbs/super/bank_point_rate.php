<?php
include_once("_common.php");
include_once("_head.php");

$sql = "select * from $iw[master_table] where ma_no = 1";
$row = sql_fetch($sql);
$buy_rate = $row["ma_buy_rate"];
$sell_rate = $row["ma_sell_rate"];
$shop_rate = $row["ma_shop_rate"];
$exchange_point = $row["ma_exchange_point"];
$exchange_amount = $row["ma_exchange_amount"];
?>
<div class="breadcrumbs" id="breadcrumbs">
	<ul class="breadcrumb">
		<li>
			<i class="fa fa-calculator"></i>
			정산관리
		</li>
		<li class="active">포인트 환율</li>
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
			포인트 환율
			<small>
				<i class="fa fa-angle-double-right"></i>
				설정
			</small>
		</h1>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
			<!-- PAGE CONTENT BEGINS -->
				<form class="form-horizontal" id="ma_form" name="ma_form" action="<?=$iw['super_path']?>/bank_point_rate_ok.php?ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" method="post">
					<div class="form-group">
						<label class="col-sm-1 control-label">살때 환율</label>
						<div class="col-sm-11">
							<?
								$price = $buy_rate/100;
								$price = sprintf("%1.2f", $price);
								$price = explode(".", $price);
							?>
							<input type="text" placeholder="입력" name="ma_buy_rate" maxlength="8" style="text-align:right" value="<?=$price[0]?>"> . <input type="text" placeholder="입력" name="ma_buy_rate_2" maxlength="2" size='2' value="<?=$price[1]?>"> %
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">팔때 환율</label>
						<div class="col-sm-11">
							<?
								$price = $sell_rate/100;
								$price = sprintf("%1.2f", $price);
								$price = explode(".", $price);
							?>
							<input type="text" placeholder="입력" name="ma_sell_rate" maxlength="8" style="text-align:right" value="<?=$price[0]?>"> . <input type="text" placeholder="입력" name="ma_sell_rate_2" maxlength="2" size='2' value="<?=$price[1]?>"> %
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">쇼핑몰 환율</label>
						<div class="col-sm-11">
							<?
								$price = $shop_rate/100;
								$price = sprintf("%1.2f", $price);
								$price = explode(".", $price);
							?>
							<input type="text" placeholder="입력" name="ma_shop_rate" maxlength="8" style="text-align:right" value="<?=$price[0]?>"> . <input type="text" placeholder="입력" name="ma_shop_rate_2" maxlength="2" size='2' value="<?=$price[1]?>"> %
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">환전금액</label>
						<div class="col-sm-11">
							<input type="text" placeholder="입력" name="ma_exchange_point" maxlength="8" style="text-align:right" value="<?=$exchange_point?>">포인트 = <input type="text" placeholder="입력" name="ma_exchange_amount" maxlength="8" style="text-align:right" value="<?=$exchange_amount?>"> 원
						</div>
					</div>

					<div class="clearfix form-actions">
						<div class="col-md-offset-3 col-md-9">
							<button class="btn btn-primary" type="button" onclick="javascript:check_form();">
								<i class="fa fa-check"></i>
								확인
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
		var e1;
		var num ="0123456789";
		event.returnValue = true;

		if (ma_form.ma_buy_rate.value == ""){
			alert("살때 환율을 입력하여 주십시오.");
			ma_form.ma_buy_rate.focus();
			return;
		}
		e1 = ma_form.ma_buy_rate;
		for (var i=0;i<e1.value.length;i++){
			if(-1 == num.indexOf(e1.value.charAt(i)))
			event.returnValue = false;
		}
		if (!event.returnValue){
			alert('숫자로만 입력가능한 항목입니다.');
			e1.focus();
			return;
		}
		if (ma_form.ma_buy_rate_2.value.length < 2) {
			alert('소수점이하는 2글자 이상 입력하여 주십시오.');
			ma_form.ma_buy_rate_2.focus();
			return;
		}
		e1 = ma_form.ma_buy_rate_2;
		for (var i=0;i<e1.value.length;i++){
			if(-1 == num.indexOf(e1.value.charAt(i)))
			event.returnValue = false;
		}
		if (!event.returnValue){
			alert('숫자로만 입력가능한 항목입니다.');
			e1.focus();
			return;
		}
		if (ma_form.ma_sell_rate.value == ""){
			alert("팔때 환율을 입력하여 주십시오.");
			ma_form.ma_sell_rate.focus();
			return;
		}
		e1 = ma_form.ma_sell_rate;
		for (var i=0;i<e1.value.length;i++){
			if(-1 == num.indexOf(e1.value.charAt(i)))
			event.returnValue = false;
		}
		if (!event.returnValue){
			alert('숫자로만 입력가능한 항목입니다.');
			e1.focus();
			return;
		}
		if (ma_form.ma_sell_rate_2.value.length < 2) {
			alert('소수점이하는 2글자 이상 입력하여 주십시오.');
			ma_form.ma_sell_rate_2.focus();
			return;
		}
		e1 = ma_form.ma_sell_rate_2;
		for (var i=0;i<e1.value.length;i++){
			if(-1 == num.indexOf(e1.value.charAt(i)))
			event.returnValue = false;
		}
		if (!event.returnValue){
			alert('숫자로만 입력가능한 항목입니다.');
			e1.focus();
			return;
		}
		if (ma_form.ma_shop_rate.value == ""){
			alert("쇼핑몰 환율을 입력하여 주십시오.");
			ma_form.ma_shop_rate.focus();
			return;
		}
		e1 = ma_form.ma_shop_rate;
		for (var i=0;i<e1.value.length;i++){
			if(-1 == num.indexOf(e1.value.charAt(i)))
			event.returnValue = false;
		}
		if (!event.returnValue){
			alert('숫자로만 입력가능한 항목입니다.');
			e1.focus();
			return;
		}
		if (ma_form.ma_shop_rate_2.value.length < 2) {
			alert('소수점이하는 2글자 이상 입력하여 주십시오.');
			ma_form.ma_shop_rate_2.focus();
			return;
		}
		e1 = ma_form.ma_shop_rate_2;
		for (var i=0;i<e1.value.length;i++){
			if(-1 == num.indexOf(e1.value.charAt(i)))
			event.returnValue = false;
		}
		if (!event.returnValue){
			alert('숫자로만 입력가능한 항목입니다.');
			e1.focus();
			return;
		}
		if (ma_form.ma_exchange_point.value == ""){
			alert("환전금액 포인트를 입력하여 주십시오.");
			ma_form.ma_exchange_point.focus();
			return;
		}e1 = ma_form.ma_exchange_point;
		for (var i=0;i<e1.value.length;i++){
			if(-1 == num.indexOf(e1.value.charAt(i)))
			event.returnValue = false;
		}
		if (!event.returnValue){
			alert('숫자로만 입력가능한 항목입니다.');
			e1.focus();
			return;
		}

		if (ma_form.ma_exchange_amount.value == ""){
			alert("환전금액 원단위를 입력하여 주십시오.");
			ma_form.ma_exchange_amount.focus();
			return;
		}e1 = ma_form.ma_exchange_amount;
		for (var i=0;i<e1.value.length;i++){
			if(-1 == num.indexOf(e1.value.charAt(i)))
			event.returnValue = false;
		}
		if (!event.returnValue){
			alert('숫자로만 입력가능한 항목입니다.');
			e1.focus();
			return;
		}
		ma_form.submit();
	}

</script>
 
<?
include_once("_tail.php");
?>
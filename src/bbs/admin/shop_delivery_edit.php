<?php
include_once("_common.php");
if ($iw[type] != "shop" || !($iw[level] == "seller" || $iw[level] == "admin")) alert("잘못된 접근입니다!","");
include_once("_head.php");

$sy_no = $_GET["idx"];

$sql = "select * from $iw[shop_delivery_table] where ep_code = '$iw[store]' and mb_code = '$iw[member]' and sy_no = '$sy_no'";
$row = sql_fetch($sql);
if (!$row["sy_no"]) alert("잘못된 접근입니다!","");
$sy_max = $row["sy_max"];
$sy_display = $row["sy_display"];
?>
<div class="breadcrumbs" id="breadcrumbs">
	<ul class="breadcrumb">
		<li>
			<i class="fa fa-shopping-cart"></i>
			쇼핑몰
		</li>
		<li class="active">배송코드관리</li>
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
			배송코드관리
			<small>
				<i class="fa fa-angle-double-right"></i>
				수정
			</small>
		</h1>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
			<!-- PAGE CONTENT BEGINS -->
				<form class="form-horizontal" id="sy_form" name="sy_form" action="<?=$iw['admin_path']?>/shop_delivery_edit_ok.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" method="post">
				<input type="hidden" name="sy_no" value="<?=$sy_no?>" />
				<input type="hidden" name="sy_display" value="<?=$sy_display?>" />
					<div class="form-group">
						<label class="col-sm-1 control-label">배송코드</label>
						<div class="col-sm-11">
							<?=$row["sy_code"]?>
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">배송가격</label>
						<div class="col-sm-11">
							<?php if($iw[language]=="ko"){?>
								<input type="text" placeholder="입력" name="sy_price" maxlength="10" value="<?=$row["sy_price"]?>"> 원
							<?php }else if($iw[language]=="en"){
								$price = $row["sy_price"]/1000;
								$price = sprintf("%1.2f", $price);
								$price = explode(".", $price);
							?>
								US$ <input type="text" placeholder="입력" name="sy_price" maxlength="8" style="text-align:right" value="<?=$price[0]?>"> . <input type="text" placeholder="입력" name="sy_price_2" maxlength="2" size='2' value="<?=$price[1]?>">
							<?php }?>
						</div>
					</div>
					<div class="space-4"></div>
					
					<div class="form-group">
						<label class="col-sm-1 control-label">그룹가입방식</label>
						<div class="col-sm-11">
							<div class="radio">
								<label>
									<input type="radio" name="type" value="1" onclick="javascript:radio_check(this.value);" <?php if{?>checked<?php }?>>
									<span class="lbl"> 무료배송</span>
									<?php if($iw[language]=="ko"){?>
										<input type="text" placeholder="입력" name="sy_max" maxlength="10" value="<?php if{?><?=$sy_max }?>"> 원
									<?php }else if($iw[language]=="en"){
										$price_max = $row["sy_max"]/1000;
										$price_max = sprintf("%1.2f", $price_max);
										$price_max = explode(".", $price_max);
									?>
										US$ <input type="text" placeholder="입력" name="sy_max" maxlength="8" style="text-align:right" value="<?php if{?><?=$price_max[0] }?>"> . <input type="text" placeholder="입력" name="sy_max_2" maxlength="2" size='2' value="<?php if{?><?=$price_max[1] }?>">
									<?php }?> 이상
								</label>
							</div>
							<div class="radio">
								<label>
									<input type="radio" name="type" value="2" onclick="javascript:radio_check(this.value);" <?php if{?>checked<?php }?>>
									<span class="lbl"> 묶음배송</span> <input type="text" placeholder="입력" name="sy_max_3" maxlength="3" value="<?php if{?><?=$sy_max }?>"> 개 이하
								</label>
							</div>
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">메모</label>
						<div class="col-sm-11">
							<input type="text" placeholder="입력" class="col-xs-12 col-sm-8" name="sy_name" maxlength="100" value="<?=$row["sy_name"]?>">
						</div>
					</div>
					<div class="space-4"></div>

					<div class="clearfix form-actions">
						<div class="col-md-offset-3 col-md-9">
							<button class="btn btn-primary" type="button" onclick="javascript:check_form();">
								<i class="fa fa-check"></i>
								등록
							</button>
							<button class="btn btn-default" type="button" onclick="location='<?=$iw['admin_path']?>/shop_delivery_list.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>'">
								<i class="fa fa-undo"></i>
								취소
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

		if (sy_form.sy_price.value == "") {
			alert('배송가격을 입력하여 주십시오.');
			sy_form.sy_price.focus();
			return;
		}
		e1 = sy_form.sy_price;
		for (var i=0;i<e1.value.length;i++){
			if(-1 == num.indexOf(e1.value.charAt(i)))
			event.returnValue = false;
		}
		if (!event.returnValue){
			alert('숫자로만 입력가능한 항목입니다.');
			e1.focus();
			return;
		}
	<?php if($iw[language]=="en"){?>
		if (sy_form.sy_price_2.value.length < 2) {
			alert('소수점이하는 2글자를 입력하여 주십시오.');
			sy_form.sy_price_2.focus();
			return;
		}
		e1 = sy_form.sy_price_2;
		for (var i=0;i<e1.value.length;i++){
			if(-1 == num.indexOf(e1.value.charAt(i)))
			event.returnValue = false;
		}
		if (!event.returnValue){
			alert('숫자로만 입력가능한 항목입니다.');
			e1.focus();
			return;
		}
	<?php }?>
		if (sy_form.sy_display.value == "1") {
			if (sy_form.sy_max.value == "") {
				alert('배송비무료 가격을 입력하여 주십시오.');
				sy_form.sy_max.focus();
				return;
			}
			e1 = sy_form.sy_max;
			for (var i=0;i<e1.value.length;i++){
				if(-1 == num.indexOf(e1.value.charAt(i)))
				event.returnValue = false;
			}
			if (!event.returnValue){
				alert('숫자로만 입력가능한 항목입니다.');
				e1.focus();
				return;
			}
			<?php if($iw[language]=="en"){?>
				if (sy_form.sy_max_2.value.length < 2) {
					alert('소수점이하는 2글자를 입력하여 주십시오.');
					sy_form.sy_max_2.focus();
					return;
				}
				e1 = sy_form.sy_max_2;
				for (var i=0;i<e1.value.length;i++){
					if(-1 == num.indexOf(e1.value.charAt(i)))
					event.returnValue = false;
				}
				if (!event.returnValue){
					alert('숫자로만 입력가능한 항목입니다.');
					e1.focus();
					return;
				}
			<?php }?>
		}
		if (sy_form.sy_display.value == "2") {
			if (sy_form.sy_max_3.value == "") {
				alert('최대수량을 입력하여 주십시오.');
				sy_form.sy_max_3.focus();
				return;
			}
			e1 = sy_form.sy_max_3;
			for (var i=0;i<e1.value.length;i++){
				if(-1 == num.indexOf(e1.value.charAt(i)))
				event.returnValue = false;
			}
			if (!event.returnValue){
				alert('숫자로만 입력가능한 항목입니다.');
				e1.focus();
				return;
			}
		}
		e1 = sy_form.sy_max;
		for (var i=0;i<e1.value.length;i++){
			if(-1 == num.indexOf(e1.value.charAt(i)))
			event.returnValue = false;
		}
		if (!event.returnValue){
			alert('숫자로만 입력가능한 항목입니다.');
			e1.focus();
			return;
		}
		sy_form.submit();
	}

	function radio_check(chk) {
		sy_form.sy_display.value = chk;
	}
</script>
 
<?php
include_once("_tail.php");
?>




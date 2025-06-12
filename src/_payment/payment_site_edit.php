<?php
include_once("_common.php");
include_once("_head.php");

if(!$_GET["idx"])exit;
$ps_no = $_GET["idx"];

$sql = "select * from $payment[site_user_table] where ps_no = $ps_no";
$row = sql_fetch($sql);
if (!$row["ps_no"]) alert("잘못된 접근입니다!","");
$ps_display = $row["ps_display"];
?>
<div class="breadcrumbs" id="breadcrumbs">
	<ul class="breadcrumb">
		<li>
			<i class="fa fa-home"></i>
			결제시스템
		</li>
		<li class="active">사이트관리</li>
	</ul><!-- .breadcrumb -->
</div>
<div class="page-content">
	<div class="page-header">
		<h1>
			사이트관리
			<small>
				<i class="fa fa-angle-double-right"></i>
				정보수정
			</small>
		</h1>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
			<!-- PAGE CONTENT BEGINS -->
				<form class="form-horizontal" id="ps_form" name="ps_form" action="payment_site_edit_ok.php" method="post">
					<input type="hidden" name="ps_no" value="<?=$ps_no?>" />
					<div class="form-group">
						<label class="col-sm-1 control-label">사이트명</label>
						<div class="col-sm-11">
							<input type="text" placeholder="입력" class="col-xs-12 col-sm-8" name="ps_corporate" value="<?=$row["ps_corporate"]?>">
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">도메인주소</label>
						<div class="col-sm-11">
							<input type="text" placeholder="입력" class="col-xs-12 col-sm-8" name="ps_domain" value="<?=$row["ps_domain"]?>">
							<span class="help-block col-xs-12">
								www.info-way.co.kr ( O ) / info-way.co.kr ( X ) / http://www.info-way.co.kr ( X )
							</span>
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">사용설정</label>
						<div class="col-sm-11">
							<select name="ps_display">
								<option value="1" <?if($ps_display == "1"){?>selected<?}?>>사용</option>
								<option value="0" <?if($ps_display == "0"){?>selected<?}?>>정지</option>
							</select>
						</div>
					</div>
					<div class="space-4"></div>

					<div class="clearfix form-actions">
						<div class="col-md-offset-3 col-md-9">
							<button class="btn btn-primary" type="button" onclick="javascript:check_form();">
								<i class="fa fa-check"></i>
								확인
							</button>
							<button class="btn btn-default" type="button" onclick="location='payment_site_view.php?idx=<?=$ps_no?>'">
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
		
		if (ps_form.ps_corporate.value == ""){
			alert("사이트명을 입력하여 주십시오.");
			ps_form.ps_corporate.focus();
			return;
		}
		if (ps_form.ps_domain.value == ""){
			alert("도메인주소를 입력하여 주십시오.");
			ps_form.ps_domain.focus();
			return;
		}
		ps_form.submit();
	}
</script>
 
<?
include_once("_tail.php");
?>
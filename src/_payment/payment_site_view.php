<?php
include_once("_common.php");
include_once("_head.php");

if(!$_GET["idx"])exit;
$ps_no = $_GET["idx"];

$sql = "select * from $payment[site_user_table] where ps_no = $ps_no";
$row = sql_fetch($sql);
if (!$row["ps_no"]) alert("잘못된 접근입니다!","");
$ps_domain = $row["ps_domain"];
$ps_corporate = $row["ps_corporate"];
$ps_datetime = $row["ps_datetime"];
$ps_display = $row["ps_display"];

$row = sql_fetch(" select count(*) as cnt from $payment[lgd_request_table] where payment_domain = '$ps_domain'");
$lgd_request = number_format($row[cnt]);
$row = sql_fetch(" select count(*) as cnt from $payment[lgd_response_table] where payment_domain = '$ps_domain' ");
$lgd_response = number_format($row[cnt]);
$row = sql_fetch(" select count(*) as cnt from $payment[cancel_request_table] where payment_domain = '$ps_domain' ");
$cancel_request = number_format($row[cnt]);
$row = sql_fetch(" select count(*) as cnt from $payment[cancel_response_table] where payment_domain = '$ps_domain' ");
$cancel_response = number_format($row[cnt]);
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
				상세정보
			</small>
		</h1>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
			<!-- PAGE CONTENT BEGINS -->
				<form class="form-horizontal no-input-detail">
					<div class="form-group">
						<label class="col-sm-1 control-label">사이트명</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$ps_corporate?></p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">도메인주소</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$ps_domain?></p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">사용현황</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static">
								<?if($ps_display == 1){?>
										<span class="label label-sm label-success">사용</span>
								<?}else if($ps_display == 0){?>
										<span class="label label-sm label-warning">정지</span>
								<?}?>
							</p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">개설일자</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$ps_datetime?></p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">결제</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static">요청 : <?=$lgd_request?>건 / 응답 : <?=$lgd_response?>건</p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">취소</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static">요청 : <?=$cancel_request?>건 / 응답 : <?=$cancel_response?>건</p>
						</div>
					</div>
				</form>
				<div class="clearfix form-actions">
					<div class="col-md-offset-3 col-md-9">
						<button class="btn btn-info" type="button" onclick="location='payment_site_edit.php?idx=<?=$ps_no?>'">
							<i class="fa fa-check"></i>
							수정
						</button>
						<button class="btn btn-default" type="button" onclick="location='payment_site_list.php'">
							<i class="fa fa-undo"></i>
							목록
						</button>
					</div>
				</div>
			<!-- PAGE CONTENT ENDS -->
			</div><!-- /col -->
		</div><!-- /row -->
	</div><!-- /container -->
</div><!-- /end .page-content -->
<?
include_once("_tail.php");
?>
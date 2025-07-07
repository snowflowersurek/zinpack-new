<?php
include_once("_common.php");
include_once("_head.php");

$sql = "select * from {$iw['master_table']} where ma_no = 1";
$row = sql_fetch($sql);

// 마스터키가 설정되지 않았다면 기본값 생성
if (!$row) {
    $sql = "insert into {$iw['master_table']} (ma_no, ma_userid, ma_password, ma_buy_rate, ma_sell_rate, ma_shop_rate, ma_display) values (1, '', '', 0, 0, 0, 0)";
    sql_query($sql);
    $row = sql_fetch("select * from {$iw['master_table']} where ma_no = 1");
}

?>
<div class="breadcrumbs" id="breadcrumbs">
	<ul class="breadcrumb">
		<li>
			<i class="fa fa-sitemap"></i>
			사이트관리
		</li>
		<li class="active">마스터키</li>
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
			마스터키
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
				<?php if($row['ma_userid']) { ?>
				<div class="alert alert-info">
					<strong>현재 설정된 마스터키:</strong><br>
					아이디: <?=htmlspecialchars($row['ma_userid'])?><br>
					비밀번호: *** (보안상 표시되지 않음)
				</div>
				<?php } else { ?>
				<div class="alert alert-warning">
					<strong>마스터키가 설정되지 않았습니다.</strong><br>
					새로운 마스터키를 설정해주세요.
				</div>
				<?php } ?>
				
				<form class="form-horizontal" id="ma_form" name="ma_form" action="<?=$iw['super_path']?>/master_key_ok.php?ep=<?=$iw['store']?>&gp=<?=$iw['group']?>" method="post">
					<div class="form-group">
						<label class="col-sm-1 control-label">아이디</label>
						<div class="col-sm-11">
							<input type="text" placeholder="새 아이디 입력" class="col-xs-12 col-sm-8" name="ma_userid" value="<?=htmlspecialchars($row['ma_userid'] ?? '')?>">
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">비밀번호</label>
						<div class="col-sm-11">
							<input type="password" placeholder="새 비밀번호 입력" class="col-xs-12 col-sm-8" name="ma_password">
							<p class="help-block">보안을 위해 비밀번호는 항상 새로 입력해주세요.</p>
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
		if (ma_form.ma_userid.value == ""){
			alert("아이디를 입력하여 주십시오.");
			ma_form.ma_userid.focus();
			return;
		}
		if (ma_form.ma_password.value == ""){
			alert("비밀번호를 입력하여 주십시오.");
			ma_form.ma_password.focus();
			return;
		}
		ma_form.submit();
	}

</script>
 
<?php
include_once("_tail.php");




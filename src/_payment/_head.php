<?php
if (!defined("_PAYMENT_")) exit; // 개별 페이지 접근 불가

include_once("_head_sub.php");

if (get_cookie("payment_member") != "payment") {
	alert("잘못된 접근입니다!","index.php");
} else {
	set_cookie("payment_member", "payment", time()+3600);
}
?>
<div class="navbar navbar-default" id="navbar">
	<div class="navbar-container" id="navbar-container">
		<div class="navbar-header">
			<span class="navbar-brand">
				<i class="zp zp-type"></i>
				결제시스템
			</span>
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#topmenu">
				<span class="sr-only">Toggle navigation</span>
				<i class="fa fa-cogs"></i>
			</button>
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#sidebar">
				<span class="sr-only">Toggle navigation</span>
				<i class="fa fa-bars"></i>
			</button>
		</div><!-- /.navbar-header -->

		<div class="collapse navbar-collapse" id="topmenu">
			<ul class="nav navbar-nav pull-right">
				<li class="dropdown">
					<a data-toggle="dropdown" href="#" class="dropdown-toggle" style="background:none;">
						<span class="user-info">
							<small>Welcome,</small>
							관리자
						</span>
						<b class="caret"></b>
					</a>

					<ul class="dropdown-menu" role="menu">
						<li><a href="logout.php"><i class="fa fa-power-off"></i> 로그아웃</a></li>
					</ul>
				</li>
			</ul><!-- /.nav -->
		</div><!-- /.navbar-header -->
	</div><!-- /.container -->
</div>

<div class="main-container" id="main-container">
	<div class="main-container-inner">
		<!-- sidebar start -->
		<div class="sidebar" id="sidebar">
			
			<ul class="nav nav-list">
				<li>
					<a href="main.php">
						<i class="fa fa-dashboard"></i>
						<span class="menu-text"> 데쉬보드</span>
					</a>
				</li>

				<li>
					<a href="payment_site_list.php">
						<i class="fa fa-sitemap"></i>
						<span class="menu-text"> 사이트관리</span>
					</a>
				</li>

				<li>
					<a href="payment_lgd_req_list.php">
						<i class="fa fa-credit-card"></i>
						<span class="menu-text"> 결제요청</span>
					</a>
				</li>

				<li>
					<a href="payment_lgd_res_list.php">
						<i class="fa fa-credit-card-alt"></i>
						<span class="menu-text"> 결제응답</span>
					</a>
				</li>

				<li>
					<a href="payment_cancel_req_list.php">
						<i class="fa fa-window-close-o"></i>
						<span class="menu-text"> 취소요청</span>
					</a>
				</li>

				<li>
					<a href="payment_cancel_res_list.php">
						<i class="fa fa-window-close"></i>
						<span class="menu-text"> 취소응답</span>
					</a>
				</li>

			</ul><!-- /.nav-list -->

		</div><!-- / end sidebar -->
		
		<div class="main-content">




<?php
if (!defined("_INFOWAY_")) exit; // 개별 페이지 접근 불가

include_once("{$iw['super_path']}/_head_sub.php");
// if (($iw['level'] ?? 'guest') != "super") alert("잘못된 접근입니다!", "{$iw['super_path']}/login.php?type=dashboard&ep={$iw['store']}&gp={$iw['group']}");
?>
<div class="navbar navbar-default" id="navbar">
	<div class="navbar-container" id="navbar-container">
		<div class="navbar-header">
			<span class="navbar-brand">
				<i class="zp zp-type"></i>
			</span><!-- /.brand -->
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
							<?php
								$mb_name = '';
								if (isset($iw['member'])) {
									$row = sql_fetch("select mb_name from {$iw['member_table']} where ep_code = '{$iw['store']}' and mb_code = '{$iw['member']}'");
									$mb_name = $row["mb_name"] ?? '';
								}
								echo $mb_name;
							?>
						</span>
						<b class="caret"></b>
					</a>

					<ul class="dropdown-menu" role="menu">
						<!--<li><a href="#"><i class="fa fa-cog"></i> 세팅<a></li>
						<li><a href="#"><i class="fa fa-user"></i> 프로파일</a></li>

						<li class="divider"></li>-->

						<li><a href="<?php echo $iw['super_path']; ?>/logout.php?type=dashboard&ep=<?php echo $iw['store']; ?>&gp=<?php echo $iw['group']; ?>"><i class="fa fa-power-off"></i> 로그아웃</a></li>
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
				<li <?php if($iw['type'] == "dashboard"){ echo 'class="active"'; }?>>
					<a href="<?php echo $iw['super_path']; ?>/main.php?type=dashboard&ep=<?php echo $iw['store']; ?>&gp=<?php echo $iw['group']; ?>">
						<i class="fa fa-dashboard"></i>
						<span class="menu-text"> 데쉬보드</span>
					</a>
				</li>

				<li <?php if($iw['type'] == "site"){ echo 'class="active"'; }?>>
					<a href="#" data-toggle="collapse" data-target="#group">
						<i class="fa fa-sitemap"></i>
						<span class="menu-text"> 사이트관리</span>
						<i class="arrow fa fa-angle-down"></i>
					</a>
					
					<ul class="submenu <?php if($iw['type'] == "site"){ echo 'in'; } else { echo 'collapse'; }?>" id="group">
						<li>
							<a href="<?php echo $iw['super_path']; ?>/enterprise_list.php?type=site&ep=<?php echo $iw['store']; ?>&gp=<?php echo $iw['group']; ?>">
								<i class="fa fa-angle-double-right"></i>
								업체정보
							</a>
						</li>
						<li>
							<a href="<?php echo $iw['super_path']; ?>/member_data_list.php?type=site&ep=<?php echo $iw['store']; ?>&gp=<?php echo $iw['group']; ?>">
								<i class="fa fa-angle-double-right"></i>
								회원정보
							</a>
						</li>
						<li>
							<a href="<?php echo $iw['super_path']; ?>/group_list.php?type=site&ep=<?php echo $iw['store']; ?>&gp=<?php echo $iw['group']; ?>">
								<i class="fa fa-angle-double-right"></i>
								그룹정보
							</a>
						</li>
						<li>
							<a href="<?php echo $iw['super_path']; ?>/count_list.php?type=site&ep=<?php echo $iw['store']; ?>&gp=<?php echo $iw['group']; ?>">
								<i class="fa fa-angle-double-right"></i>
								방문자수
							</a>
						</li>
						<li>
							<a href="<?php echo $iw['super_path']; ?>/master_key.php?type=site&ep=<?php echo $iw['store']; ?>&gp=<?php echo $iw['group']; ?>">
								<i class="fa fa-angle-double-right"></i>
								마스터키 설정
							</a>
						</li>
						<li>
							<a href="<?php echo $iw['super_path']; ?>/policy_edit.php?type=site&ep=<?php echo $iw['store']; ?>&gp=<?php echo $iw['group']; ?>">
								<i class="fa fa-angle-double-right"></i>
								이용약관 설정
							</a>
						</li>
					</ul>
				</li>

				<li <?php if($iw['type'] == "sale"){ echo 'class="active"'; }?>>
					<a href="#" data-toggle="collapse" data-target="#news">
						<i class="fa fa-line-chart"></i>
						<span class="menu-text"> 매출내역</span>
						<i class="arrow fa fa-angle-down"></i>
					</a>

					<ul class="submenu <?php if($iw['type'] == "sale"){ echo 'in'; } else { echo 'collapse'; }?>" id="news">
						<li>
							<a href="<?php echo $iw['super_path']; ?>/point_list.php?type=sale&ep=<?php echo $iw['store']; ?>&gp=<?php echo $iw['group']; ?>">
								<i class="fa fa-angle-double-right"></i>
								포인트 사용내역
							</a>
						</li>
						<li>
							<a href="<?php echo $iw['super_path']; ?>/mcb_support_list.php?type=sale&ep=<?php echo $iw['store']; ?>&gp=<?php echo $iw['group']; ?>">
								<i class="fa fa-angle-double-right"></i>
								게시판 후원내역
							</a>
						</li>
						<li>
							<a href="<?php echo $iw['super_path']; ?>/doc_sale_list.php?type=sale&ep=<?php echo $iw['store']; ?>&gp=<?php echo $iw['group']; ?>">
								<i class="fa fa-angle-double-right"></i>
								컨텐츠몰 판매내역
							</a>
						</li>
						<li>
							<a href="<?php echo $iw['super_path']; ?>/doc_support_list.php?type=sale&ep=<?php echo $iw['store']; ?>&gp=<?php echo $iw['group']; ?>">
								<i class="fa fa-angle-double-right"></i>
								컨텐츠몰 후원내역
							</a>
						</li>
						<li>
							<a href="<?php echo $iw['super_path']; ?>/book_sale_list.php?type=sale&ep=<?php echo $iw['store']; ?>&gp=<?php echo $iw['group']; ?>">
								<i class="fa fa-angle-double-right"></i>
								이북몰 판매내역
							</a>
						</li>
						<li>
							<a href="<?php echo $iw['super_path']; ?>/book_support_list.php?type=sale&ep=<?php echo $iw['store']; ?>&gp=<?php echo $iw['group']; ?>">
								<i class="fa fa-angle-double-right"></i>
								이북몰 후원내역
							</a>
						</li>
						<li>
							<a href="<?php echo $iw['super_path']; ?>/shop_post_list.php?type=sale&ep=<?php echo $iw['store']; ?>&gp=<?php echo $iw['group']; ?>">
								<i class="fa fa-angle-double-right"></i>
								쇼핑몰 주문내역
							</a>
						</li>
					</ul>
				</li>

				<li <?php if($iw['type'] == "pay"){ echo 'class="active"'; }?>>
					<a href="#" data-toggle="collapse" data-target="#shopping">
						<i class="fa fa-credit-card"></i>
						<span class="menu-text"> 결제내역</span>
						<i class="arrow fa fa-angle-down"></i>
					</a>
					
					<ul class="submenu <?php if($iw['type'] == "pay"){ echo 'in'; } else { echo 'collapse'; }?>" id="shopping">
						<li>
							<a href="<?php echo $iw['super_path']; ?>/pay_list.php?type=pay&ep=<?php echo $iw['store']; ?>&gp=<?php echo $iw['group']; ?>">
								<i class="fa fa-angle-double-right"></i>
								TOSS 결제내역
							</a>
						</li>
						<li>
							<a href="<?php echo $iw['super_path']; ?>/pay_cancel_list.php?type=pay&ep=<?php echo $iw['store']; ?>&gp=<?php echo $iw['group']; ?>">
								<i class="fa fa-angle-double-right"></i>
								TOSS 취소내역
							</a>
						</li>
						<li>
							<a href="<?php echo $iw['super_path']; ?>/charge_list.php?type=pay&ep=<?php echo $iw['store']; ?>&gp=<?php echo $iw['group']; ?>">
								<i class="fa fa-angle-double-right"></i>
								관리비 결제내역
							</a>
						</li>
						<li>
							<a href="<?php echo $iw['super_path']; ?>/pay_paypal_list.php?type=pay&ep=<?php echo $iw['store']; ?>&gp=<?php echo $iw['group']; ?>">
								<i class="fa fa-angle-double-right"></i>
								PAYPAL결제내역
							</a>
						</li>
						<li>
							<a href="<?php echo $iw['super_path']; ?>/pay_alipay_list.php?type=pay&ep=<?php echo $iw['store']; ?>&gp=<?php echo $iw['group']; ?>">
								<i class="fa fa-angle-double-right"></i>
								ALIPAY결제내역
							</a>
						</li>
						<!-- <li>
							<a href="<?php //echo $iw['super_path']; ?>/charge_list.php?type=pay&ep=<?php //echo $iw['store']; ?>&gp=<?php //echo $iw['group']; ?>">
								<i class="fa fa-angle-double-right"></i>
								관리비 결제내역
							</a>
						</li> -->
					</ul>
				</li>

				<li <?php if($iw['type'] == "bank"){ echo 'class="active"'; }?>>
					<a href="#" data-toggle="collapse" data-target="#contents-mall">
						<i class="fa fa-calculator"></i>
						<span class="menu-text"> 정산관리</span>
						<i class="arrow fa fa-angle-down"></i>
					</a>
					<ul class="submenu <?php if($iw['type'] == "bank"){ echo 'in'; } else { echo 'collapse'; }?>" id="contents-mall">
						<li>
							<a href="<?php echo $iw['super_path']; ?>/bank_exchange_list.php?type=bank&ep=<?php echo $iw['store']; ?>&gp=<?php echo $iw['group']; ?>">
								<i class="fa fa-angle-double-right"></i>
								환전내역
							</a>
						</li>
						<li>
							<a href="<?php echo $iw['super_path']; ?>/bank_point_rate.php?type=bank&ep=<?php echo $iw['store']; ?>&gp=<?php echo $iw['group']; ?>">
								<i class="fa fa-angle-double-right"></i>
								포인트 환율 설정
							</a>
						</li>
						<li>
							<a href="<?php echo $iw['super_path']; ?>/shop_sale_list.php?type=bank&ep=<?php echo $iw['store']; ?>&gp=<?php echo $iw['group']; ?>">
								<i class="fa fa-angle-double-right"></i>
								업체별 정산내역
							</a>
						</li>
					</ul>
				</li>

			</ul><!-- /.nav-list -->

		</div><!-- / end sidebar -->
		
		<div class="main-content">
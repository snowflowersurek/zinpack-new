<?php
if (!defined("_INFOWAY_")) exit; // 개별 페이지 접근 불가

include_once("{$iw['super_path']}/_head_sub.php");
// if (($iw['level'] ?? 'guest') != "super") alert("잘못된 접근입니다!", "{$iw['super_path']}/login.php?type=dashboard&ep={$iw['store']}&gp={$iw['group']}");
?>

<!-- Google Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@400;500;700&display=swap" rel="stylesheet">

<style>
body {
    font-family: 'Noto Sans KR', sans-serif;
}
	
/* 레이아웃 스타일 추가 */
#main-container {
    display: flex !important;
    min-height: 100vh;
    gap: 0 !important;
    margin: 0 !important;
    padding: 0 !important;
}

/* 사이드바 기본 스타일 */
.sidebar, #sidebar {
    width: 250px !important;
    min-height: 100vh;
    flex-shrink: 0;
    padding: 0 !important;
    margin: 0 !important;
    margin-right: 0 !important; /* 오른쪽 마진 확실히 제거 */
    background-color: #f8f9fa;
    font-family: 'Noto Sans KR', sans-serif;
}

/* 메인 콘텐츠 스타일 - 사이드바에 붙도록 */
.main-content {
    flex: 1;
    padding: 0 !important;
    margin: 0 !important;
    margin-left: 0 !important; /* 왼쪽 마진 확실히 제거 */
    background-color: #fff;
    min-height: 100vh;
}


/* 메뉴 아이템 높이 통일 및 폰트 크기 */
.nav-list .nav-link {
    min-height: 50px;
    font-size: 1.0rem;
    font-weight: 500; /* Medium 굵기 */
    color: #212529; /* 검정색 텍스트 */
}

/* 메뉴 호버 효과 */
.nav-list .nav-link:hover {
    background-color: #e9ecef;
    color: #000;
}

/* 활성 메뉴 스타일 - 폰트 크기 동일하게 유지 */
.nav-list .nav-item.active > .nav-link {
    background-color: #0d6efd;
    color: #fff;
    font-size: 1.0rem;
    font-weight: 500;
}

.nav-list .nav-item.active > .nav-link:hover {
    background-color: #0b5ed7;
    color: #fff;
}

/* 서브메뉴 스타일 */
.nav-list .submenu {
    background-color: #f1f3f4;
}

.nav-list .submenu a {
    color: #495057;
    font-size: 0.9rem;
    font-weight: 400; /* Regular 굵기 */
    min-height: 45px;
}

.nav-list .submenu a:hover {
    background-color: #dee2e6;
    color: #000;
}

/* 활성 서브메뉴의 부모 메뉴 스타일 - 폰트 크기 동일 */
.nav-list .nav-item.active .submenu a {
    font-size: 0.9rem;
    font-weight: 400;
}

/* 화살표 회전 애니메이션 */
.nav-list .nav-link[data-bs-toggle="collapse"] .arrow {
    transition: transform 0.3s ease;
    color: #212529; /* 화살표 검정색 */
}

.nav-list .nav-link[data-bs-toggle="collapse"]:not(.collapsed) .arrow {
    transform: rotate(180deg);
}

/* 모든 링크의 기본 스타일 제거 */
.nav-list a {
    text-decoration: none;
    color: #212529; /* 기본 검정색 */
}

/* 아이콘 크기 */
.nav-list i {
    font-size: 1.0rem;
}

/* 서브메뉴 아이콘 크기 */
.nav-list .submenu i {
    font-size: 0.9rem;
}

/* 활성 메뉴의 아이콘도 같은 크기 유지 */
.nav-list .nav-item.active > .nav-link i {
    font-size: 1.0rem;
}

.nav-list .nav-item.active .submenu i {
    font-size: 0.9rem;
}

/* 반응형 대응 */
@media (max-width: 768px) {
    #main-container {
        flex-direction: column;
    }
    
    .sidebar {
        width: 100%;
        min-height: auto;
    }
    
    .main-content {
        width: 100%;
    }
}
</style>

<!-- 나머지 코드는 동일 -->

<div class="navbar navbar-default" id="navbar">
	<div class="navbar-container" id="navbar-container">
		<div class="navbar-header">
			<span class="navbar-brand">
				<i class="zp zp-type"></i>
			</span><!-- /.brand -->
			<button type="button" class="navbar-toggle" data-bs-toggle="collapse" data-bs-target="#topmenu">
				<span class="sr-only">Toggle navigation</span>
				<i class="fas fa-cogs"></i>
			</button>
			<button type="button" class="navbar-toggle" data-bs-toggle="collapse" data-bs-target="#sidebar">
				<span class="sr-only">Toggle navigation</span>
				<i class="fas fa-bars"></i>
			</button>
		</div><!-- /.navbar-header -->

		<div class="collapse navbar-collapse" id="topmenu">
			<ul class="nav navbar-nav ms-auto">
				<li class="dropdown">
					<a data-bs-toggle="dropdown" href="#" class="dropdown-toggle" style="background:none;">
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
						<li><a href="#"><i class="fas fa-user"></i> 프로파일</a></li>

						<li class="divider"></li>-->

						<li><a href="<?php echo $iw['super_path']; ?>/logout.php?type=dashboard&ep=<?php echo $iw['store']; ?>&gp=<?php echo $iw['group']; ?>"><i class="fas fa-power-off"></i> 로그아웃</a></li>
					</ul>
				</li>
			</ul><!-- /.nav -->
		</div><!-- /.navbar-header -->
	</div><!-- /.container -->
</div>

<div class="main-container" id="main-container">
	<!-- sidebar start -->
	<div class="sidebar" id="sidebar">
		
		<ul class="nav nav-list flex-column w-100 p-0">
			<li class="nav-item w-100 <?php if($iw['type'] == "dashboard"){ echo 'active'; }?>">
				<a href="<?php echo $iw['super_path']; ?>/main.php?type=dashboard&ep=<?php echo $iw['store']; ?>&gp=<?php echo $iw['group']; ?>" 
				   class="nav-link d-flex align-items-center w-100 px-3 py-2 border-0 rounded-0">
					<i class="fas fa-gauge fa-fw me-2"></i>
					<span class="menu-text">데쉬보드</span>
				</a>
			</li>

			<li class="nav-item w-100 <?php if($iw['type'] == "site"){ echo 'active'; }?>">
				<a href="#" class="nav-link d-flex justify-content-between align-items-center w-100 px-3 py-2 border-0 rounded-0" 
				   data-bs-toggle="collapse" data-bs-target="#group">
					<span class="d-flex align-items-center">
						<i class="fas fa-sitemap fa-fw me-2"></i>
						<span class="menu-text">사이트관리</span>
					</span>
					<i class="arrow fas fa-angle-down"></i>
				</a>
				
				<ul class="submenu list-unstyled collapse <?php if($iw['type'] == "site"){ echo 'show'; }?>" id="group">
					<li class="w-100">
						<a href="<?php echo $iw['super_path']; ?>/enterprise_list.php?type=site&ep=<?php echo $iw['store']; ?>&gp=<?php echo $iw['group']; ?>"
						   class="d-flex align-items-center w-100 text-decoration-none px-4 py-2">
							<span class="d-flex align-items-center">
								<i class="fas fa-caret-right me-2"></i>
								<span class="menu-text">업체정보</span>
							</span>
						</a>
					</li>
					<li class="w-100">
						<a href="<?php echo $iw['super_path']; ?>/member_data_list.php?type=site&ep=<?php echo $iw['store']; ?>&gp=<?php echo $iw['group']; ?>"
						   class="d-flex align-items-center w-100 text-decoration-none px-4 py-2">
							<span class="d-flex align-items-center">
								<i class="fas fa-caret-right me-2"></i>
								<span class="menu-text">회원정보</span>
							</span>
						</a>
					</li>
					<li class="w-100">
						<a href="<?php echo $iw['super_path']; ?>/group_list.php?type=site&ep=<?php echo $iw['store']; ?>&gp=<?php echo $iw['group']; ?>"
						   class="d-flex align-items-center w-100 text-decoration-none px-4 py-2">
							<span class="d-flex align-items-center">
								<i class="fas fa-caret-right me-2"></i>
								<span class="menu-text">그룹정보</span>
							</span>
						</a>
					</li>
					<li class="w-100">
						<a href="<?php echo $iw['super_path']; ?>/count_list.php?type=site&ep=<?php echo $iw['store']; ?>&gp=<?php echo $iw['group']; ?>"
						   class="d-flex align-items-center w-100 text-decoration-none px-4 py-2">
							<span class="d-flex align-items-center">
								<i class="fas fa-caret-right me-2"></i>
								<span class="menu-text">방문자수</span>
							</span>
						</a>
					</li>
					<li class="w-100">
						<a href="<?php echo $iw['super_path']; ?>/master_key.php?type=site&ep=<?php echo $iw['store']; ?>&gp=<?php echo $iw['group']; ?>"
						   class="d-flex align-items-center w-100 text-decoration-none px-4 py-2">
							<span class="d-flex align-items-center">
								<i class="fas fa-caret-right me-2"></i>
								<span class="menu-text">마스터키 설정</span>
							</span>
						</a>
					</li>
					<li class="w-100">
						<a href="<?php echo $iw['super_path']; ?>/policy_edit.php?type=site&ep=<?php echo $iw['store']; ?>&gp=<?php echo $iw['group']; ?>"
						   class="d-flex align-items-center w-100 text-decoration-none px-4 py-2">
							<span class="d-flex align-items-center">
								<i class="fas fa-caret-right me-2"></i>
								<span class="menu-text">이용약관 설정</span>
							</span>
						</a>
					</li>
				</ul>
			</li>

			<li class="nav-item w-100 <?php if($iw['type'] == "sale"){ echo 'active'; }?>">
				<a href="#" class="nav-link d-flex justify-content-between align-items-center w-100 px-3 py-2 border-0 rounded-0" 
				   data-bs-toggle="collapse" data-bs-target="#news">
					<span class="d-flex align-items-center">
						<i class="fas fa-chart-line fa-fw me-2"></i>
						<span class="menu-text">매출내역</span>
					</span>
					<i class="arrow fas fa-angle-down"></i>
				</a>

				<ul class="submenu list-unstyled collapse <?php if($iw['type'] == "sale"){ echo 'show'; }?>" id="news">
					<li class="w-100">
						<a href="<?php echo $iw['super_path']; ?>/point_list.php?type=sale&ep=<?php echo $iw['store']; ?>&gp=<?php echo $iw['group']; ?>"
						   class="d-flex align-items-center w-100 text-decoration-none px-4 py-2">
							<span class="d-flex align-items-center">
								<i class="fas fa-caret-right me-2"></i>
								<span class="menu-text">포인트 사용내역</span>
							</span>
						</a>
					</li>
					<li class="w-100">
						<a href="<?php echo $iw['super_path']; ?>/mcb_support_list.php?type=sale&ep=<?php echo $iw['store']; ?>&gp=<?php echo $iw['group']; ?>"
						   class="d-flex align-items-center w-100 text-decoration-none px-4 py-2">
							<span class="d-flex align-items-center">
								<i class="fas fa-caret-right me-2"></i>
								<span class="menu-text">게시판 후원내역</span>
							</span>
						</a>
					</li>
					<li class="w-100">
						<a href="<?php echo $iw['super_path']; ?>/doc_sale_list.php?type=sale&ep=<?php echo $iw['store']; ?>&gp=<?php echo $iw['group']; ?>"
						   class="d-flex align-items-center w-100 text-decoration-none px-4 py-2">
							<span class="d-flex align-items-center">
								<i class="fas fa-caret-right me-2"></i>
								<span class="menu-text">컨텐츠몰 판매내역</span>
							</span>
						</a>
					</li>
					<li class="w-100">
						<a href="<?php echo $iw['super_path']; ?>/doc_support_list.php?type=sale&ep=<?php echo $iw['store']; ?>&gp=<?php echo $iw['group']; ?>"
						   class="d-flex align-items-center w-100 text-decoration-none px-4 py-2">
							<span class="d-flex align-items-center">
								<i class="fas fa-caret-right me-2"></i>
								<span class="menu-text">컨텐츠몰 후원내역</span>
							</span>
						</a>
					</li>
					<li class="w-100">
						<a href="<?php echo $iw['super_path']; ?>/book_sale_list.php?type=sale&ep=<?php echo $iw['store']; ?>&gp=<?php echo $iw['group']; ?>"
						   class="d-flex align-items-center w-100 text-decoration-none px-4 py-2">
							<span class="d-flex align-items-center">
								<i class="fas fa-caret-right me-2"></i>
								<span class="menu-text">이북몰 판매내역</span>
							</span>
						</a>
					</li>
					<li class="w-100">
						<a href="<?php echo $iw['super_path']; ?>/book_support_list.php?type=sale&ep=<?php echo $iw['store']; ?>&gp=<?php echo $iw['group']; ?>"
						   class="d-flex align-items-center w-100 text-decoration-none px-4 py-2">
							<span class="d-flex align-items-center">
								<i class="fas fa-caret-right me-2"></i>
								<span class="menu-text">이북몰 후원내역</span>
							</span>
						</a>
					</li>
					<li class="w-100">
						<a href="<?php echo $iw['super_path']; ?>/shop_post_list.php?type=sale&ep=<?php echo $iw['store']; ?>&gp=<?php echo $iw['group']; ?>"
						   class="d-flex align-items-center w-100 text-decoration-none px-4 py-2">
							<span class="d-flex align-items-center">
								<i class="fas fa-caret-right me-2"></i>
								<span class="menu-text">쇼핑몰 주문내역</span>
							</span>
						</a>
					</li>
				</ul>
			</li>

			<li class="nav-item w-100 <?php if($iw['type'] == "pay"){ echo 'active'; }?>">
				<a href="#" class="nav-link d-flex justify-content-between align-items-center w-100 px-3 py-2 border-0 rounded-0" 
				   data-bs-toggle="collapse" data-bs-target="#shopping">
					<span class="d-flex align-items-center">
						<i class="fas fa-credit-card fa-fw me-2"></i>
						<span class="menu-text">결제내역</span>
					</span>
					<i class="arrow fas fa-angle-down"></i>
				</a>
				
				<ul class="submenu list-unstyled collapse <?php if($iw['type'] == "pay"){ echo 'show'; }?>" id="shopping">
					<li class="w-100">
						<a href="<?php echo $iw['super_path']; ?>/pay_list.php?type=pay&ep=<?php echo $iw['store']; ?>&gp=<?php echo $iw['group']; ?>"
						   class="d-flex align-items-center w-100 text-decoration-none px-4 py-2">
							<span class="d-flex align-items-center">
								<i class="fas fa-caret-right me-2"></i>
								<span class="menu-text">TOSS 결제내역</span>
							</span>
						</a>
					</li>
					<li class="w-100">
						<a href="<?php echo $iw['super_path']; ?>/pay_cancel_list.php?type=pay&ep=<?php echo $iw['store']; ?>&gp=<?php echo $iw['group']; ?>"
						   class="d-flex align-items-center w-100 text-decoration-none px-4 py-2">
							<span class="d-flex align-items-center">
								<i class="fas fa-caret-right me-2"></i>
								<span class="menu-text">TOSS 취소내역</span>
							</span>
						</a>
					</li>
					<li class="w-100">
						<a href="<?php echo $iw['super_path']; ?>/charge_list.php?type=pay&ep=<?php echo $iw['store']; ?>&gp=<?php echo $iw['group']; ?>"
						   class="d-flex align-items-center w-100 text-decoration-none px-4 py-2">
							<span class="d-flex align-items-center">
								<i class="fas fa-caret-right me-2"></i>
								<span class="menu-text">관리비 결제내역</span>
							</span>
						</a>
					</li>
					<li class="w-100">
						<a href="<?php echo $iw['super_path']; ?>/pay_paypal_list.php?type=pay&ep=<?php echo $iw['store']; ?>&gp=<?php echo $iw['group']; ?>"
						   class="d-flex align-items-center w-100 text-decoration-none px-4 py-2">
							<span class="d-flex align-items-center">
								<i class="fas fa-caret-right me-2"></i>
								<span class="menu-text">PAYPAL결제내역</span>
							</span>
						</a>
					</li>
					<li class="w-100">
						<a href="<?php echo $iw['super_path']; ?>/pay_alipay_list.php?type=pay&ep=<?php echo $iw['store']; ?>&gp=<?php echo $iw['group']; ?>"
						   class="d-flex align-items-center w-100 text-decoration-none px-4 py-2">
							<span class="d-flex align-items-center">
								<i class="fas fa-caret-right me-2"></i>
								<span class="menu-text">ALIPAY결제내역</span>
							</span>
						</a>
					</li>
				</ul>
			</li>

			<li class="nav-item w-100 <?php if($iw['type'] == "bank"){ echo 'active'; }?>">
				<a href="#" class="nav-link d-flex justify-content-between align-items-center w-100 px-3 py-2 border-0 rounded-0" 
				   data-bs-toggle="collapse" data-bs-target="#contents-mall">
					<span class="d-flex align-items-center">
						<i class="fas fa-calculator fa-fw me-2"></i>
						<span class="menu-text">정산관리</span>
					</span>
					<i class="arrow fas fa-angle-down"></i>
				</a>
				<ul class="submenu list-unstyled collapse <?php if($iw['type'] == "bank"){ echo 'show'; }?>" id="contents-mall">
					<li class="w-100">
						<a href="<?php echo $iw['super_path']; ?>/bank_exchange_list.php?type=bank&ep=<?php echo $iw['store']; ?>&gp=<?php echo $iw['group']; ?>"
						   class="d-flex align-items-center w-100 text-decoration-none px-4 py-2">
							<span class="d-flex align-items-center">
								<i class="fas fa-caret-right me-2"></i>
								<span class="menu-text">환전내역</span>
							</span>
						</a>
					</li>
					<li class="w-100">
						<a href="<?php echo $iw['super_path']; ?>/bank_point_rate.php?type=bank&ep=<?php echo $iw['store']; ?>&gp=<?php echo $iw['group']; ?>"
						   class="d-flex align-items-center w-100 text-decoration-none px-4 py-2">
							<span class="d-flex align-items-center">
								<i class="fas fa-caret-right me-2"></i>
								<span class="menu-text">포인트 환율 설정</span>
							</span>
						</a>
					</li>
					<li class="w-100">
						<a href="<?php echo $iw['super_path']; ?>/shop_sale_list.php?type=bank&ep=<?php echo $iw['store']; ?>&gp=<?php echo $iw['group']; ?>"
						   class="d-flex align-items-center w-100 text-decoration-none px-4 py-2">
							<span class="d-flex align-items-center">
								<i class="fas fa-caret-right me-2"></i>
								<span class="menu-text">업체별 정산내역</span>
							</span>
						</a>
					</li>
				</ul>
			</li>

		</ul><!-- /.nav-list -->

	</div><!-- / end sidebar -->
	
	<div class="main-content">
		<!-- <div class="main-container-inner"> -->
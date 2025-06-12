<?
if (!defined("_INFOWAY_")) exit; // 개별 페이지 접근 불가

include_once("$iw[admin_path]/_head_sub.php");
if ($iw[level] == "guest") alert("잘못된 접근입니다!","");
?>
<div class="navbar navbar-default" id="navbar" style="background-color:#737379;">
	<div class="navbar-container" id="navbar-container">
		<div class="navbar-header">
			<span class="navbar-brand">
				<i class="zp zp-type white"></i>
				<a href="<?=$iw[m_path]?>/main.php?type=main&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" class="white">
				<?
					$row = sql_fetch("select * from $iw[setting_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]'");
					echo $row["st_title"];
				?>
				<span class="glyphicon glyphicon-home"></span>
				</a>
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
						<span class="user-info" style="color:#ffffff;">
							<small>Welcome,</small>
							<?
								$row = sql_fetch("select mb_name from $iw[member_table] where ep_code = '$iw[store]' and mb_code = '$iw[member]'");
								echo $row["mb_name"];
							?>
						</span>
						<b class="caret"></b>
					</a>

					<ul class="dropdown-menu" role="menu">
						<!--<li><a href="#"><i class="fa fa-cog"></i> 세팅<a></li>
						<li><a href="#"><i class="fa fa-user"></i> 프로파일</a></li>

						<li class="divider"></li>-->

						<li><a href="<?=$iw[m_path]?>/all_logout.php?type=mcb&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>"><i class="fa fa-power-off"></i> 로그아웃</a></li>
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
			<div class="sidebar-shortcuts" id="sidebar-shortcuts">
				<div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
					<a class="btn btn-primary shortcut-btn" data-toggle="tooltip" title="게시판" style="padding:6px 8px;">
						<i class="fa fa-clipboard"></i>
					</a>
					<a class="btn btn-success shortcut-btn" data-toggle="tooltip" title="출판도서" style="padding:6px 8px;">
						<i class="fa fa-book"></i>
					</a>
					<a class="btn btn-info shortcut-btn" data-toggle="tooltip" title="쇼핑몰" style="padding:6px 8px;">
						<i class="fa fa-shopping-cart"></i>
					</a>
					<a class="btn btn-warning shortcut-btn" data-toggle="tooltip" title="컨텐츠몰" style="padding:6px 8px;">
						<i class="fa fa-inbox"></i>
					</a>
					<a class="btn btn-danger shortcut-btn" data-toggle="tooltip" title="이북몰" style="padding:6px 8px;">
						<i class="fa fa-newspaper-o"></i>
					</a>
				</div>
			</div><!-- #sidebar-shortcuts -->

			<ul class="nav nav-list">
				<li <?if($iw[type] == "dashboard"){?>class="active"<?}?>>
					<a href="<?=$iw[admin_path]?>/main.php?type=dashboard&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>">
						<i class="fa fa-dashboard"></i>
						<span class="menu-text"> 데쉬보드</span>
					</a>
				</li>
			<?if($iw[mcb]==1){?>
				<li<?if ($iw[type] == "mcb"){?> class="active"<?}?>>
					<a href="#" data-toggle="collapse" data-target="#news">
						<i class="fa fa-clipboard"></i>
						<span class="menu-text"> 게시판</span>
						<i class="arrow fa fa-angle-down"></i>
					</a>

					<ul class="submenu <?if($iw[type] == "mcb"){?>in<?}else{?>collapse<?}?>" id="news">
						<li>
							<a href="<?=$iw[admin_path]?>/mcb_data_list.php?type=mcb&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>">
								<i class="fa fa-angle-double-right"></i>
								게시글 관리
							</a>
						</li>
						<li>
							<a href="<?=$iw[admin_path]?>/mcb_support_list.php?type=mcb&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>">
								<i class="fa fa-angle-double-right"></i>
								후원내역
							</a>
						</li>

					<?if($iw[group] == "all" && $iw[level] == "admin"){?>
						<li>
							<a href="<?=$iw[admin_path]?>/mcb_all_list.php?type=mcb&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>">
								<i class="fa fa-angle-double-right"></i>
								전체 게시글 조회
							</a>
						</li>
						<li>
							<a href="<?=$iw[admin_path]?>/mcb_all_support_list.php?type=mcb&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>">
								<i class="fa fa-angle-double-right"></i>
								전체 후원내역
							</a>
						</li>
					<?}?>

					<?if(($iw[group]=="all" && $iw[level] == "admin") || ($iw[group]!="all" && $iw[gp_level] == "gp_admin")){?>
						<li>
							<a href="<?=$iw[admin_path]?>/category_list.php?type=mcb&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>">
								<i class="fa fa-angle-double-right"></i>
								분류 관리
							</a>
						</li>
					<?}?>
					</ul>
				</li>
			<?}?>

			<?if($iw[publishing] == 1 && (($iw[group] == "all" && $iw[level] == "admin") || ($iw[group] != "all" && $iw[gp_level] == "gp_admin"))){?>
				<li<?if ($iw[type] == "publishing" || $iw[type] == "publishing_brand"){?> class="active"<?}?>>
					<a href="#" data-toggle="collapse" data-target="#publishing">
						<i class="fa fa-book"></i>
						<span class="menu-text"> 출판도서</span>
						<i class="arrow fa fa-angle-down"></i>
					</a>

					<ul class="submenu <?if($iw[type] == "publishing" || $iw[type] == "publishing_brand" || $iw[type] == "publishing_contest"){?>in<?}else{?>collapse<?}?>" id="publishing">
					<?if(($iw[group] == "all" && $iw[level] == "admin") || ($iw[group] != "all" && $iw[gp_level] == "gp_admin")){?>
						<li>
							<a href="<?=$iw[admin_path]?>/category_list.php?type=publishing&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>">
								<i class="fa fa-angle-double-right"></i>
								분류관리
							</a>
						</li>
						<li>
							<a href="<?=$iw[admin_path]?>/category_list.php?type=publishing_brand&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>">
								<i class="fa fa-angle-double-right"></i>
								브랜드관리
							</a>
						</li>
						<li>
							<a href="<?=$iw[admin_path]?>/publishing_books_list.php?type=publishing&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>">
								<i class="fa fa-angle-double-right"></i>
								도서관리
							</a>
						</li>
						<li>
							<a href="<?=$iw[admin_path]?>/publishing_author_list.php?type=publishing&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>">
								<i class="fa fa-angle-double-right"></i>
								저자관리
							</a>
						</li>
						<?if($iw[group] == "all" && $iw[level] == "admin"){?>
						<li>
							<a href="<?=$iw[admin_path]?>/publishing_exhibit_list.php?type=publishing&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>">
								<i class="fa fa-angle-double-right"></i>
								그림전시관리
							</a>
						</li>
						<li>
							<a href="<?=$iw[admin_path]?>/publishing_exhibit_status_list.php?type=publishing&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>">
								<i class="fa fa-angle-double-right"></i>
								그림전시 신청내역
							</a>
						</li>
						<li>
							<a href="<?=$iw[admin_path]?>/publishing_lecture_list.php?type=publishing&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>">
								<i class="fa fa-angle-double-right"></i>
								작가강연회 신청내역
							</a>
						</li>
						<li>
							<a href="<?=$iw[admin_path]?>/category_list.php?type=publishing_contest&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>">
								<i class="fa fa-angle-double-right"></i>
								공모전 분류관리
							</a>
						</li>
						<li>
							<a href="<?=$iw[admin_path]?>/publishing_contest_list.php?type=publishing&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>">
								<i class="fa fa-angle-double-right"></i>
								공모전관리
							</a>
						</li>
						<?}?>
					<?}?>
					</ul>
				</li>
			<?}?>

			<?if($iw[shop]==1){?>
				<li<?if($iw[type] == "shop"){?> class="active"<?}?>>
					<a href="#" data-toggle="collapse" data-target="#shopping">
						<i class="fa fa-shopping-cart"></i>
						<span class="menu-text"> 쇼핑몰</span>
						<i class="arrow fa fa-angle-down"></i>
					</a>
					
					<ul class="submenu <?if($iw[type] == "shop"){?>in<?}else{?>collapse<?}?>" id="shopping">
					<?if($iw[group] == "all" && $iw[level] == "admin"){?>
						<li>
							<a href="<?=$iw[admin_path]?>/shop_seller_approval_list.php?type=shop&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>">
								<i class="fa fa-angle-double-right"></i>
								판매자등록 승인
							</a>
						</li>
						<li>
							<a href="<?=$iw[admin_path]?>/shop_seller_list.php?type=shop&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>">
								<i class="fa fa-angle-double-right"></i>
								전체 판매자
							</a>
						</li>
						<li>
							<a href="<?=$iw[admin_path]?>/shop_post_store_list.php?type=shop&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&search=e&searchs=주문">
								<i class="fa fa-angle-double-right"></i>
								주문관리
							</a>
						</li>
						<li>
							<a href="<?=$iw[admin_path]?>/shop_data_list.php?type=shop&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>">
								<i class="fa fa-angle-double-right"></i>
								상품관리
							</a>
						</li>
						<li>
							<a href="<?=$iw[admin_path]?>/shop_delivery_list.php?type=shop&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>">
								<i class="fa fa-angle-double-right"></i>
								배송코드관리
							</a>
						</li>
						<li>
							<a href="<?=$iw[admin_path]?>/shop_seller_edit.php?type=shop&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>">
								<i class="fa fa-angle-double-right"></i>
								판매자 정보
							</a>
						</li>
						<li>
							<a href="<?=$iw[admin_path]?>/shop_seller_write.php?type=shop&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>">
								<i class="fa fa-angle-double-right"></i>
								판매자등록 신청
							</a>
						</li>
					<?}else if($iw[level] == "seller"){?>
						<li>
							<a href="<?=$iw[admin_path]?>/shop_post_store_list.php?type=shop&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&search=e&searchs=주문">
								<i class="fa fa-angle-double-right"></i>
								주문관리
							</a>
						</li>
						<li>
							<a href="<?=$iw[admin_path]?>/shop_data_list.php?type=shop&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>">
								<i class="fa fa-angle-double-right"></i>
								상품관리
							</a>
						</li>
						<li>
							<a href="<?=$iw[admin_path]?>/shop_delivery_list.php?type=shop&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>">
								<i class="fa fa-angle-double-right"></i>
								배송코드관리
							</a>
						</li>
						<li>
							<a href="<?=$iw[admin_path]?>/shop_seller_edit.php?type=shop&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>">
								<i class="fa fa-angle-double-right"></i>
								판매자 정보
							</a>
						</li>
					<?}else if($iw[level] == "member"){?>
						<li>
							<a href="<?=$iw[admin_path]?>/shop_seller_write.php?type=shop&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>">
								<i class="fa fa-angle-double-right"></i>
								판매자등록 신청
							</a>
						</li>
					<?}?>

					<?if($iw[group] == "all" && $iw[level] == "admin"){?>
						<li>
							<a href="<?=$iw[admin_path]?>/shop_all_list.php?type=shop&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>">
								<i class="fa fa-angle-double-right"></i>
								전체 상품조회
							</a>
						</li>
						<li>
							<a href="<?=$iw[admin_path]?>/shop_post_all_list.php?type=shop&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>">
								<i class="fa fa-angle-double-right"></i>
								전체 주문조회
							</a>
						</li>
						<li>
							<a href="<?=$iw[admin_path]?>/shop_post_pay_list.php?type=shop&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>">
								<i class="fa fa-angle-double-right"></i>
								전체 결제조회
							</a>
						</li>
						<li>
							<a href="<?=$iw[admin_path]?>/shop_card_list.php?type=shop&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>">
								<i class="fa fa-angle-double-right"></i>
								TOSS 결제내역
							</a>
						</li>
						<li>
							<a href="<?=$iw[admin_path]?>/shop_card_cancel_list.php?type=shop&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>">
								<i class="fa fa-angle-double-right"></i>
								TOSS 취소내역
							</a>
						</li>
						<li>
							<a href="<?=$iw[admin_path]?>/shop_paypal_list.php?type=shop&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>">
								<i class="fa fa-angle-double-right"></i>
								PAYPAL결제내역
							</a>
						</li>
						<li>
							<a href="<?=$iw[admin_path]?>/shop_alipay_list.php?type=shop&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>">
								<i class="fa fa-angle-double-right"></i>
								ALIPAY결제내역
							</a>
						</li>
						<!--<li>
							<a href="<?=$iw[admin_path]?>/shop_card_partial_list.php?type=shop&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>">
								<i class="fa fa-angle-double-right"></i>
								부분취소내역
							</a>
						</li>-->
					<?}?>

					<?if(($iw[group] == "all" && $iw[level] == "admin") || ($iw[group] != "all" && $iw[gp_level] == "gp_admin")){?>
						<li>
							<a href="<?=$iw[admin_path]?>/category_list.php?type=shop&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>">
								<i class="fa fa-angle-double-right"></i>
								분류 관리
							</a>
						</li>
						<li>
							<a href="<?=$iw[admin_path]?>/shop_approval_list.php?type=shop&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>">
								<i class="fa fa-angle-double-right"></i>
								상품등록 승인
							</a>
						</li>
						<li>
							<a href="<?=$iw[admin_path]?>/shop_exposure_list.php?type=shop&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>">
								<i class="fa fa-angle-double-right"></i>
								상품 노출관리
							</a>
						</li>
					<?}?>
					</ul>
				</li>
			<?}?>

			<?if($iw[doc]==1){?>
				<li<?if($iw[type] == "doc"){?> class="active"<?}?>>
					<a href="#" data-toggle="collapse" data-target="#contents-mall">
						<i class="fa fa-inbox"></i>
						<span class="menu-text"> 컨텐츠몰</span>
						<i class="arrow fa fa-angle-down"></i>
					</a>
					
					<ul class="submenu <?if($iw[type] == "doc"){?>in<?}else{?>collapse<?}?>" id="contents-mall">
					<?if($iw[level] == "member" || $iw[level] == "seller"){?>
						<li>
							<a href="<?=$iw[admin_path]?>/doc_data_list.php?type=doc&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>">
								<i class="fa fa-angle-double-right"></i>
								컨텐츠 관리
							</a>
						</li>
						<li>
							<a href="<?=$iw[admin_path]?>/doc_sale_list.php?type=doc&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>">
								<i class="fa fa-angle-double-right"></i>
								판매내역
							</a>
						</li>
						<li>
							<a href="<?=$iw[admin_path]?>/doc_support_list.php?type=doc&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>">
								<i class="fa fa-angle-double-right"></i>
								후원내역
							</a>
						</li>
					<?}?>

					<?if($iw[group] == "all" && $iw[level] == "admin"){?>
						<li>
							<a href="<?=$iw[admin_path]?>/doc_all_list.php?type=doc&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>">
								<i class="fa fa-angle-double-right"></i>
								전체 컨텐츠 조회
							</a>
						</li>
						<li>
							<a href="<?=$iw[admin_path]?>/doc_all_sale_list.php?type=doc&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>">
								<i class="fa fa-angle-double-right"></i>
								전체 판매내역
							</a>
						</li>
						<li>
							<a href="<?=$iw[admin_path]?>/doc_all_support_list.php?type=doc&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>">
								<i class="fa fa-angle-double-right"></i>
								전체 후원내역
							</a>
						</li>
					<?}?>
					
					<?if(($iw[group] == "all" && $iw[level] == "admin") || ($iw[group] != "all" && $iw[gp_level] == "gp_admin")){?>
						<li>
							<a href="<?=$iw[admin_path]?>/category_list.php?type=doc&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>">
								<i class="fa fa-angle-double-right"></i>
								분류 관리
							</a>
						</li>
						<li>
							<a href="<?=$iw[admin_path]?>/doc_approval_list.php?type=doc&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>">
								<i class="fa fa-angle-double-right"></i>
								컨텐츠 등록 승인
							</a>
						</li>
						<li>
							<a href="<?=$iw[admin_path]?>/doc_exposure_list.php?type=doc&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>">
								<i class="fa fa-angle-double-right"></i>
								컨텐츠 노출 관리
							</a>
						</li>
					<?}?>
					</ul>
				</li>
			<?}?>

			<?if($iw[book]==1){?>
				<li<?if($iw[type] == "book"){?> class="active"<?}?>>
					<a href="#" data-toggle="collapse" data-target="#ebook-mall">
						<i class="fa fa-newspaper-o"></i>
						<span class="menu-text"> 이북몰</span>
						<i class="arrow fa fa-angle-down"></i>
					</a>
					
					<ul class="submenu <?if($iw[type] == "book"){?>in<?}else{?>collapse<?}?>" id="ebook-mall">
					<?if($iw[level] == "member" || $iw[level] == "seller"){?>
						<li>
							<a href="<?=$iw[admin_path]?>/book_data_list.php?type=book&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>">
								<i class="fa fa-angle-double-right"></i>
								이북 관리
							</a>
						</li>
						<li>
							<a href="<?=$iw[admin_path]?>/book_sale_list.php?type=book&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>">
								<i class="fa fa-angle-double-right"></i>
								판매내역
							</a>
						</li>
						<li>
							<a href="<?=$iw[admin_path]?>/book_support_list.php?type=book&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>">
								<i class="fa fa-angle-double-right"></i>
								후원내역
							</a>
						</li>
					<?}?>

					<?if($iw[group] == "all" && $iw[level] == "admin"){?>
						<li>
							<a href="<?=$iw[admin_path]?>/book_all_list.php?type=book&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>">
								<i class="fa fa-angle-double-right"></i>
								전체 이북 조회
							</a>
						</li>
						<li>
							<a href="<?=$iw[admin_path]?>/book_all_sale_list.php?type=book&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>">
								<i class="fa fa-angle-double-right"></i>
								전체 판매내역
							</a>
						</li>
						<li>
							<a href="<?=$iw[admin_path]?>/book_all_support_list.php?type=book&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>">
								<i class="fa fa-angle-double-right"></i>
								전체 후원내역
							</a>
						</li>
					<?}?>
					
					<?if(($iw[group] == "all" && $iw[level] == "admin") || ($iw[group] != "all" && $iw[gp_level] == "gp_admin")){?>
						<li>
							<a href="<?=$iw[admin_path]?>/category_list.php?type=book&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>">
								<i class="fa fa-angle-double-right"></i>
								분류 관리
							</a>
						</li>
						<li>
							<a href="<?=$iw[admin_path]?>/book_approval_list.php?type=book&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>">
								<i class="fa fa-angle-double-right"></i>
								이북 등록 승인
							</a>
						</li>
						<li>
							<a href="<?=$iw[admin_path]?>/book_exposure_list.php?type=book&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>">
								<i class="fa fa-angle-double-right"></i>
								이북 노출 관리
							</a>
						</li>
					<?}?>
					</ul>
				</li>
			<?}?>
				<li <?if($iw[type] == "group"){?>class="active"<?}?>>
					<a href="#" data-toggle="collapse" data-target="#group">
						<i class="fa fa-users"></i>
						<span class="menu-text"> 그룹/회원관리</span>
						<i class="arrow fa fa-angle-down"></i>
					</a>
					
					<ul class="submenu <?if($iw[type] == "group"){?>in<?}else{?>collapse<?}?>" id="group">
					<?if($iw[group] != "all" && $iw[gp_level] == "gp_admin"){?>
						<li>
							<a href="<?=$iw[admin_path]?>/group_data_edit.php?type=group&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>">
								<i class="fa fa-angle-double-right"></i>
								그룹 정보
							</a>
						</li>
						<li>
							<a href="<?=$iw[admin_path]?>/group_member_list.php?type=group&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>">
								<i class="fa fa-angle-double-right"></i>
								그룹 회원
							</a>
						</li>
						<li>
							<a href="<?=$iw[admin_path]?>/group_invite_list.php?type=group&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>">
								<i class="fa fa-angle-double-right"></i>
								그룹 초대
							</a>
						</li>
						<li>
							<a href="<?=$iw[admin_path]?>/group_join_list.php?type=group&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>">
								<i class="fa fa-angle-double-right"></i>
								그룹가입 승인
							</a>
						</li>
					<?}else if($iw[group] == "all" && $iw[level] == "admin"){?>
						<li>
							<a href="<?=$iw[admin_path]?>/group_approval_list.php?type=group&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>">
								<i class="fa fa-angle-double-right"></i>
								신규그룹 승인
							</a>
						</li>
						<li>
							<a href="<?=$iw[admin_path]?>/group_all_list.php?type=group&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>">
								<i class="fa fa-angle-double-right"></i>
								전체 그룹
							</a>
						</li>
						
						<li>
							<a href="<?=$iw[admin_path]?>/member_join_list.php?type=group&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>">
								<i class="fa fa-angle-double-right"></i>
								회원가입 승인
							</a>
						</li>
						<li>
							<a href="<?=$iw[admin_path]?>/member_invite_list.php?type=group&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>">
								<i class="fa fa-angle-double-right"></i>
								회원 초대
							</a>
						</li>
						<li>
							<a href="<?=$iw[admin_path]?>/member_join_setting.php?type=group&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>">
								<i class="fa fa-angle-double-right"></i>
								회원가입 설정
							</a>
						</li>
						<li>
							<a href="<?=$iw[admin_path]?>/member_data_list.php?type=group&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>">
								<i class="fa fa-angle-double-right"></i>
								전체 회원관리
							</a>
						</li>
						<li>
							<a href="<?=$iw[admin_path]?>/member_point_list.php?type=group&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>">
								<i class="fa fa-angle-double-right"></i>
								포인트 내역
							</a>
						</li>
					<?}else if($iw[group] == "all"){?>
						<li>
							<a href="<?=$iw[admin_path]?>/group_my_list.php?type=group&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>">
								<i class="fa fa-angle-double-right"></i>
								가입한 그룹
							</a>
						</li>
						<li>
							<a href="<?=$iw[admin_path]?>/group_all_list.php?type=group&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>">
								<i class="fa fa-angle-double-right"></i>
								전체 그룹
							</a>
						</li>
					<?}?>

					<?if(($iw[group]=="all" && $iw[level] == "admin") || ($iw[group]!="all" && $iw[gp_level] == "gp_admin")){?>
						<li>
							<a href="<?=$iw[admin_path]?>/group_level_edit.php?type=group&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>">
								<i class="fa fa-angle-double-right"></i>
								회원등급 설정
							</a>
						</li>
					<?}?>
					</ul>
				</li>

				<li<?if($iw[type] == "bank"){?> class="active"<?}?>>
					<a href="#" data-toggle="collapse" data-target="#financial">
						<i class="fa fa-calculator"></i>
						<span class="menu-text"> 정산관리</span>
						<i class="arrow fa fa-angle-down"></i>
					</a>

					<ul class="submenu <?if($iw[type] == "bank"){?>in<?}else{?>collapse<?}?>" id="financial">
					<?if($iw[level] == "member" || $iw[level] == "seller"){?>
						<li>
							<a href="<?=$iw[admin_path]?>/bank_exchange_list.php?type=bank&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>">
								<i class="fa fa-angle-double-right"></i>
								환전
							</a>
						</li>
						<li>
							<a href="<?=$iw[admin_path]?>/bank_account_write.php?type=bank&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>">
								<i class="fa fa-angle-double-right"></i>
								계좌정보
							</a>
						</li>
					<?}?>

					<?if($iw[level] == "admin"){?>
						<li>
							<a href="<?=$iw[admin_path]?>/bank_all_exchange_list.php?type=bank&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>">
								<i class="fa fa-angle-double-right"></i>
								전체 환전내역
							</a>
						</li>
					<?}?>
					</ul>
				</li>
				
			<?if(($iw[group]=="all" && $iw[level] == "admin") || ($iw[group]!="all" && $iw[gp_level] == "gp_admin")){?>
				<li<?if($iw[type] == "design"){?> class="active"<?}?>>
					<a href="#" data-toggle="collapse" data-target="#designset">
						<i class="fa fa-object-group"></i>
						<span class="menu-text"> 디자인 설정</span>
						<i class="arrow fa fa-angle-down"></i>
					</a>

					<ul class="submenu <?if($iw[type] == "design"){?>in<?}else{?>collapse<?}?>" id="designset">
						<li>
							<a href="<?=$iw[admin_path]?>/design_default_edit.php?type=design&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>">
								<i class="fa fa-angle-double-right"></i>
								기본 설정
							</a>
						</li>
						<li>
							<a href="<?=$iw[admin_path]?>/design_category_edit.php?type=design&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>">
								<i class="fa fa-angle-double-right"></i>
								게시판 기본 설정
							</a>
						</li>
						<li>
							<a href="<?=$iw[admin_path]?>/design_menu_list.php?type=design&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>">
								<i class="fa fa-angle-double-right"></i>
								메뉴 및 레이아웃 설정
							</a>
						</li>
						<li>
							<a href="<?=$iw[admin_path]?>/design_scrap_list.php?type=design&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&menu=&scrap=main">
								<i class="fa fa-angle-double-right"></i>
								메인화면 설정
							</a>
						</li>
						<li>
							<a href="<?=$iw[admin_path]?>/design_theme_edit.php?type=design&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>">
								<i class="fa fa-angle-double-right"></i>
								테마디자인 설정
							</a>
						</li>
						<li>
							<a href="<?=$iw[admin_path]?>/about_data_list.php?type=design&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>">
								<i class="fa fa-angle-double-right"></i>
								독립페이지 관리
							</a>
						</li>
					<?if($iw[group]=="all" && $iw[level] == "admin"){?>
						<li>
							<a href="<?=$iw[admin_path]?>/design_policy_edit.php?type=design&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>">
								<i class="fa fa-angle-double-right"></i>
								이용약관 설정
							</a>
						</li>
						<li>
							<a href="<?=$iw[admin_path]?>/popup_layer_list.php?type=design&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>">
								<i class="fa fa-angle-double-right"></i>
								팝업창
							</a>
						</li>
					<?}?>
					</ul>
				</li>
			<?}?>
			<?if(($iw[group]=="all" && $iw[level] == "admin") || ($iw[group]!="all" && $iw[gp_level] == "gp_admin")){?>
				<li <?if($iw[type] == "notice"){?>class="active"<?}?>>
					<a href="<?=$iw[admin_path]?>/home_notice_list.php?type=notice&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>">
						<i class="fa fa-bullhorn"></i>
						<span class="menu-text"> 공지사항</span>
					</a>
				</li>
				<li <?if($iw[type] == "count"){?>class="active"<?}?>>
					<a href="<?=$iw[admin_path]?>/home_count_list.php?type=count&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>">
						<i class="fa fa-bar-chart-o"></i>
						<span class="menu-text"> 방문자수</span>
					</a>
				</li>
			<?}?>
			</ul><!-- /.nav-list -->

		</div><!-- / end sidebar -->
		
		<div class="main-content" id="main-content">
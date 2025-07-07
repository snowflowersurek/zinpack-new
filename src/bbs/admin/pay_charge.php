<?php
include_once("_common.php");
if (($iw[group] == "all" && $iw[level] != "admin") || ($iw[group] != "all" && $iw[gp_level] != "gp_admin")) alert("잘못된 접근입니다!","");
/*
Array ( 
	[path] => ../.. [bbs] => bbs [bbs_path] => ../../bbs [bbs_img] => img [bbs_img_path] => ../../bbs/img [mcb] => 1 [mcb_path] => ../../bbs/mcb [shop] => 1 [shop_path] => ../../bbs/shop [doc] => 1 [doc_path] => ../../bbs/doc [book] => 1 [book_path] => ../../bbs/book [admin] => admin [admin_path] => ../../bbs/admin [super] => super [super_path] => ../../bbs/super [m_path] => ../../bbs/m [viewer_path] => ../../bbs/viewer [include] => include [include_path] => ../../include [design] => design [design_path] => ../../design [server_time] => 1673325384 [time_ymd] => 2023-01-10 [time_his] => 13:36:24 [time_ymdhis] => 2023-01-10 13:36:24 [table_prefix] => iw_ [setting_table] => iw_setting [defined_table] => iw_defined [category_table] => iw_category [point_table] => iw_point [exchange_table] => iw_exchange [account_table] => iw_account [enterprise_table] => iw_enterprise [member_table] => iw_member [group_table] => iw_group [group_member_table] => iw_group_member [group_invite_table] => iw_group_invite [group_level_table] => iw_group_level [notice_table] => iw_notice [comment_table] => iw_comment [lgd_table] => iw_lgd [lgd_cancel_table] => iw_lgd_cancel [charge_table] => iw_charge [shop_seller_table] => iw_shop_seller [shop_data_table] => iw_shop_data [shop_option_table] => iw_shop_option [shop_delivery_table] => iw_shop_delivery [shop_cart_table] => iw_shop_cart [shop_order_table] => iw_shop_order [shop_order_sub_table] => iw_shop_order_sub [shop_order_memo_table] => iw_shop_order_memo [doc_data_table] => iw_doc_data [doc_buy_table] => iw_doc_buy [doc_support_table] => iw_doc_support [book_data_table] => iw_book_data [book_main_table] => iw_book_main [book_media_table] => iw_book_media [book_media_detail_table] => iw_book_media_detail [book_thesis_table] => iw_book_thesis [book_blog_table] => iw_book_blog [book_buy_table] => iw_book_buy [book_support_table] => iw_book_support [mcb_data_table] => iw_mcb_data [mcb_support_table] => iw_mcb_support [publishing_author_table] => iw_publishing_author [publishing_books_table] => iw_publishing_books [publishing_books_author_table] => iw_publishing_books_author [publishing_books_ddc_table] => iw_publishing_books_ddc [publishing_exhibit_table] => iw_publishing_exhibit [publishing_exhibit_status_table] => iw_publishing_exhibit_status [publishing_lecture_table] => iw_publishing_lecture [about_data_table] => iw_about_data [rank_table] => iw_rank [rank_month_table] => iw_rank_month [rank_day_table] => iw_rank_day [country_table] => iw_country [paypal_table] => iw_paypal [access_count_table] => iw_access_count [recommend_table] => iw_recommend [home_menu_table] => iw_home_menu [home_scrap_table] => iw_home_scrap [master_table] => iw_master [total_data_table] => iw_total_data [theme_data_table] => iw_theme_data [theme_setting_table] => iw_theme_setting [alipay_table] => iw_alipay [popup_layer_table] => iw_popup_layer 
	[charset] => utf-8 
	[re_url] => %2Fbbs%2Fadmin%2Fpay_charge.php%3Ftype%3Dcharge%26ep%3Dep1822322763609cab5915c89%26gp%3Dall 
	[server_path] => /www/infoway/_infoway 
	[default_domain] => www.info-way.co.kr 
	[pay_site] => www.info-way.co.kr 
	[pay_platform] => service 
	[type] => charge 
	[store] => ep1822322763609cab5915c89 
	[group] => all 
	[cookie_domain] => .info-way.co.kr 
	[url] => http://www.info-way.co.kr 
	[https_url] => https://www.info-way.co.kr 
	[buy_rate] => 15.23 
	[sell_rate] => 15.23 
	[shop_rate] => 15.23 
	[language] => ko 
	[anonymity] => 0 
	[publishing] => 1 
	[exposed] => 0 
	[level] => admin 
	[gp_level] => gp_guest 
	[member] => mb338901927609cab5915cc6 
	[mb_level] => 9 )
*/
include_once("_head.php");

$form_action = "pay_charge_request.php?type=".$iw[type]."&ep=".$iw[store]."&gp=".$iw[group];

$esql = "select * from $iw[enterprise_table] where ep_code = '$iw[store]'";
$erow = sql_fetch($esql);
$ep_name = $erow['ep_corporate'];
$ep_amount = $erow['ep_charge'];
$ep_open = $erow['ep_datetime'];
$ep_expiry = $erow['ep_expiry_date'];

$sql = "select * from $iw[member_table] where mb_code = '$iw[member]' and ep_code = '$iw[store]'";
$row = sql_fetch($sql);
$mb_point = $row["mb_point"];
$mb_name = $row["mb_name"];
$mb_mail = $row["mb_mail"];

if(preg_match('/(iPad|iPod|iPhone|Mobile|UP.Browser|Android|BlackBerry|Windows CE|Nokia|webOS|Opera Mini|SonyEricsson|opera mobi|Windows Phone)/i', $_SERVER['HTTP_USER_AGENT'])){
	$pay_device = "mobile";
}else{
	$pay_device = "pc";
}

$protocol = "http://";
if ($_SERVER["HTTP_HOST"] == "www.aviation.co.kr" || $_SERVER["HTTP_HOST"] == "www.info-way.co.kr") {
	$protocol = "https://";
}
//echo "http host : ".$_SERVER["HTTP_HOST"].", server name : ".$_SERVER['SERVER_NAME'];
$URLTORETURN		= $protocol.$_SERVER["SERVER_NAME"]."/bbs/admin/pay_charge_res.php?type=".$iw[type]."&ep=".$iw[store]."&gp=".$iw[group];
$REQURL		= $protocol.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
?>
<div class="breadcrumbs" id="breadcrumbs">
	<ul class="breadcrumb">
		<li>
			<i class='fa fa-shopping-cart'></i> 사이트 관리
			<?php
				$category_title = "사이트 관리요금";
			?>
		</li>
		<li class="active"><?=$category_title?></li>
	</ul><!-- .breadcrumb -->
</div>
<div class="page-content">
	<div class="page-header">
		<h1>
			<?=$category_title?>
			<small>
				<i class="fa fa-angle-double-right"></i>
				결제
			</small>
		</h1>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<form class="form-horizontal" id="ep_form" name="ep_form" action="<?=$form_action?>" method="post">
					<input type="hidden" name="PAYMENT_DEVICE" value="<?=$pay_device?>" />
					<input type="hidden" name="LGD_BUYER" value="<?=$ep_name?>" />
					<input type="hidden" name="LGD_BUYERID" value="<?=$iw[member]?>" />
					<input type="hidden" name="LGD_PRODUCTINFO" id="LGD_PRODUCTINFO" value="사이트관리비" />
					<input type="hidden" name="LGD_BUYEREMAIL" value="<?=$mb_mail?>" />
					<input type="hidden" name="LGD_AMOUNT" value="<?=$ep_amount?>" />
					<input type="hidden" name="URL_TO_RETURN"	id="URL_TO_RETURN" value="<?= $URLTORETURN ?>">
					<input type="hidden" name="REQUEST_URL"	id="REQUEST_URL" value="<?= $REQURL ?>">

					<div class="form-group">
						<label class="col-sm-2 control-label">업체명</label>
						<div class="col-sm-8">
							<input type="text" class="col-xs-12 col-sm-12" name="ep_name" value="<?=$ep_name?>" disabled>
						</div>
					</div>
					<div class="space-4"></div>
					<div class="form-group">
						<label class="col-sm-2 control-label">사이트 개설일자</label>
						<div class="col-sm-8">
							<input type="text" class="col-xs-12 col-sm-12" name="ep_open" value="<?php echo substr($ep_open,0,10); ?>" disabled>
						</div>
					</div>
					<div class="space-4"></div>
					<div class="form-group">
						<label class="col-sm-2 control-label">사이트 만료일자</label>
						<div class="col-sm-8">
							<input type="text" class="col-xs-12 col-sm-12" name="ep_expiry" value="<?=$ep_expiry?>" disabled>
						</div>
					</div>
					<div class="space-4"></div>
					<div class="form-group">
						<label class="col-sm-2 control-label">이용료 금액</label>
						<div class="col-sm-8">
							<input type="text" class="col-xs-12 col-sm-12" name="ep_amount" value="<?=number_format($ep_amount)?>원" disabled>
						</div>
					</div>
					<div class="space-4"></div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label" for="card">신용카드</label>
						<div class="col-sm-1" style="width:1%;">
							<input type="radio" name="LGD_CUSTOM_FIRSTPAY" id="card" value="SC0010" checked>
						</div>
						<label class="col-sm-1 control-label" for="virtual" style="width:5%;">가상계좌</label>
						<div class="col-sm-1">
							<input type="radio" name="LGD_CUSTOM_FIRSTPAY" id="virtual" value="SC0040">
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-2 control-label"></label>
						<div class="col-sm-8">
							<button type="submit" class="btn btn-primary">결제하기</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>

<?php
include_once("_tail.php");
?>




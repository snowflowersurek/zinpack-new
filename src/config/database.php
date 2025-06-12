<?php
// 데이터베이스 접속 정보
// docker-compose.yml 또는 .env 파일에 정의된 환경 변수를 사용합니다.
$mysql_host = getenv('DB_HOST') ?: 'db';
$mysql_user = getenv('DB_USER') ?: 'root';
$mysql_password = getenv('DB_PASSWORD') ?: 'infoway@$db';
$mysql_db = getenv('DB_NAME') ?: 'infoway';

// 테이블 접두사
$iw['table_prefix']        = "iw_"; 

// 테이블 이름 정의
$iw['setting_table']       = $iw['table_prefix'] . "setting";
$iw['defined_table']       = $iw['table_prefix'] . "defined";
$iw['category_table']      = $iw['table_prefix'] . "category";
$iw['point_table']		   = $iw['table_prefix'] . "point";
$iw['exchange_table']	   = $iw['table_prefix'] . "exchange";
$iw['account_table']	   = $iw['table_prefix'] . "account";

$iw['enterprise_table']    = $iw['table_prefix'] . "enterprise";
$iw['member_table']        = $iw['table_prefix'] . "member";
$iw['group_table']		   = $iw['table_prefix'] . "group";
$iw['group_member_table']  = $iw['table_prefix'] . "group_member";
$iw['group_invite_table']  = $iw['table_prefix'] . "group_invite";
$iw['group_level_table']   = $iw['table_prefix'] . "group_level";

$iw['notice_table']		   = $iw['table_prefix'] . "notice";        
$iw['comment_table']	   = $iw['table_prefix'] . "comment";

$iw['lgd_table']		   = $iw['table_prefix'] . "lgd";
$iw['lgd_cancel_table']	   = $iw['table_prefix'] . "lgd_cancel";
$iw['charge_table']		   = $iw['table_prefix'] . "charge";

$iw['shop_seller_table']   = $iw['table_prefix'] . "shop_seller"; 
$iw['shop_data_table']     = $iw['table_prefix'] . "shop_data"; 
$iw['shop_option_table']   = $iw['table_prefix'] . "shop_option"; 
$iw['shop_delivery_table'] = $iw['table_prefix'] . "shop_delivery"; 
$iw['shop_cart_table']	   = $iw['table_prefix'] . "shop_cart";
$iw['shop_order_table']	   = $iw['table_prefix'] . "shop_order";
$iw['shop_order_sub_table']= $iw['table_prefix'] . "shop_order_sub";
$iw['shop_order_memo_table']= $iw['table_prefix'] . "shop_order_memo";

$iw['doc_data_table']     = $iw['table_prefix'] . "doc_data"; 
$iw['doc_buy_table']      = $iw['table_prefix'] . "doc_buy"; 
$iw['doc_support_table']  = $iw['table_prefix'] . "doc_support"; 

$iw['book_data_table']     = $iw['table_prefix'] . "book_data";
$iw['book_main_table']     = $iw['table_prefix'] . "book_main";
$iw['book_media_table']    = $iw['table_prefix'] . "book_media";
$iw['book_media_detail_table']= $iw['table_prefix'] . "book_media_detail";
$iw['book_thesis_table']   = $iw['table_prefix'] . "book_thesis";
$iw['book_blog_table']     = $iw['table_prefix'] . "book_blog";
$iw['book_buy_table']      = $iw['table_prefix'] . "book_buy";
$iw['book_support_table']  = $iw['table_prefix'] . "book_support";

$iw['mcb_data_table']	  = $iw['table_prefix'] . "mcb_data";
$iw['mcb_support_table']  = $iw['table_prefix'] . "mcb_support";

$iw['publishing_author_table']			= $iw['table_prefix'] . "publishing_author";
$iw['publishing_books_table']			= $iw['table_prefix'] . "publishing_books";
$iw['publishing_books_author_table']	= $iw['table_prefix'] . "publishing_books_author";
$iw['publishing_books_ddc_table']		= $iw['table_prefix'] . "publishing_books_ddc";
$iw['publishing_exhibit_table']			= $iw['table_prefix'] . "publishing_exhibit";
$iw['publishing_exhibit_status_table']	= $iw['table_prefix'] . "publishing_exhibit_status";
$iw['publishing_lecture_table']			= $iw['table_prefix'] . "publishing_lecture";

$iw['about_data_table']	  = $iw['table_prefix'] . "about_data";

$iw['rank_table']		  = $iw['table_prefix'] . "rank";
$iw['rank_month_table']   = $iw['table_prefix'] . "rank_month";
$iw['rank_day_table']	  = $iw['table_prefix'] . "rank_day";
$iw['country_table']	  = $iw['table_prefix'] . "country";
$iw['paypal_table']	      = $iw['table_prefix'] . "paypal";

$iw['access_count_table'] = $iw['table_prefix'] . "access_count";
$iw['recommend_table']	  = $iw['table_prefix'] . "recommend";

$iw['home_menu_table']			= $iw['table_prefix'] . "home_menu";
$iw['home_scrap_table']			= $iw['table_prefix'] . "home_scrap";

$iw['master_table']				= $iw['table_prefix'] . "master";
$iw['total_data_table']			= $iw['table_prefix'] . "total_data";

$iw['theme_data_table']				= $iw['table_prefix'] . "theme_data";
$iw['theme_setting_table']			= $iw['table_prefix'] . "theme_setting";

$iw['alipay_table']				= $iw['table_prefix'] . "alipay";
$iw['popup_layer_table']		= $iw['table_prefix'] . "popup_layer";
$iw['delivery_info_table']		= $iw['table_prefix'] . "delivery_info"; 
<?php
$iw_path = "..";

// 설치에 필요한 최소한의 설정 파일만 로드합니다.
require_once("{$iw_path}/config/app.php"); // $iw['charset'] 값 등을 위해 필요
require_once("{$iw_path}/config/database.php");

// DB 연결 및 쿼리 실행을 위한 라이브러리 로드
require_once("{$iw_path}/include/lib/lib_common.php"); 

// DB 연결
$db_conn = sql_connect($mysql_host, $mysql_user, $mysql_password, $mysql_db);
if (!$db_conn) {
	die("<meta http-equiv='content-type' content='text/html; charset={$iw['charset']}'><script type='text/javascript'> alert('DB 접속 오류'); </script>");
}

// 테이블 생성 스크립트들을 순차적으로 실행
include_once("{$iw_path}/install/db_setting_set.php");
include_once("{$iw_path}/install/db_category_set.php");
include_once("{$iw_path}/install/db_point_set.php");
include_once("{$iw_path}/install/db_exchange_set.php");
include_once("{$iw_path}/install/db_account_set.php");

include_once("{$iw_path}/install/db_enterprise_set.php");
include_once("{$iw_path}/install/db_member_set.php");
include_once("{$iw_path}/install/db_group_set.php");
include_once("{$iw_path}/install/db_group_member_set.php");
include_once("{$iw_path}/install/db_group_invite_set.php");
include_once("{$iw_path}/install/db_group_level_set.php");

include_once("{$iw_path}/install/db_notice_set.php");
include_once("{$iw_path}/install/db_comment_set.php");

include_once("{$iw_path}/install/db_lgd_set.php");
include_once("{$iw_path}/install/db_lgd_cancel_set.php");

include_once("{$iw_path}/install/db_shop_seller_set.php");
include_once("{$iw_path}/install/db_shop_data_set.php");
include_once("{$iw_path}/install/db_shop_option_set.php");
include_once("{$iw_path}/install/db_shop_delivery_set.php");
include_once("{$iw_path}/install/db_shop_cart_set.php");
include_once("{$iw_path}/install/db_shop_order_set.php");
include_once("{$iw_path}/install/db_shop_order_sub_set.php");
include_once("{$iw_path}/install/db_shop_order_memo_set.php");

include_once("{$iw_path}/install/db_doc_data_set.php");
include_once("{$iw_path}/install/db_doc_buy_set.php");
include_once("{$iw_path}/install/db_doc_support_set.php");

include_once("{$iw_path}/install/db_book_data_set.php");
include_once("{$iw_path}/install/db_book_main_set.php");
include_once("{$iw_path}/install/db_book_media_set.php");
include_once("{$iw_path}/install/db_book_media_detail_set.php");
include_once("{$iw_path}/install/db_book_blog_set.php");
include_once("{$iw_path}/install/db_book_thesis_set.php");
include_once("{$iw_path}/install/db_book_buy_set.php");
include_once("{$iw_path}/install/db_book_support_set.php");

include_once("{$iw_path}/install/db_mcb_data_set.php");
include_once("{$iw_path}/install/db_mcb_support_set.php");

include_once("{$iw_path}/install/db_about_data_set.php");

include_once("{$iw_path}/install/db_rank_set.php");
include_once("{$iw_path}/install/db_country_set.php");
include_once("{$iw_path}/install/db_paypal_set.php");

include_once("{$iw_path}/install/db_access_count_set.php");
include_once("{$iw_path}/install/db_recommend_set.php");

include_once("{$iw_path}/install/db_home_menu_set.php");
include_once("{$iw_path}/install/db_home_scrap_set.php");

include_once("{$iw_path}/install/db_master_set.php");
include_once("{$iw_path}/install/db_total_data_set.php");

include_once("{$iw_path}/install/db_theme_data_set.php");
include_once("{$iw_path}/install/db_theme_setting_set.php");

include_once("{$iw_path}/install/db_alipay_set.php");
include_once("{$iw_path}/install/db_popup_layer_set.php");

// 설치 완료 후 관리자 계정 생성
include_once("{$iw_path}/install/super_admin_set.php");

echo "<script>alert('테이블 생성이 완료되었습니다. 이제 install 디렉터리를 삭제하거나 접근할 수 없도록 변경하십시오.');</script>";
?>
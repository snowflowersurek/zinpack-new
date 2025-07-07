<?php
include_once("_common.php");
//if ($iw[level]=="guest") alert(national_language($iw[language],"a0003","로그인 해주시기 바랍니다."),"$iw[m_path]/all_login.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]");
	
	$LGD_RESPCODE = $_POST[LGD_RESPCODE];					//응답코드
	$LGD_RESPMSG = $_POST[LGD_RESPMSG];					//응답메세지
	$LGD_MID = $_POST[LGD_MID];							//LG유플러스 발급 아이디
	$sr_code = $_POST[LGD_OID];							//이용업체 거래번호(주문번호)
	$LGD_TID = $_POST[LGD_TID];							//LG유플러스 거래번호
        
	if($LGD_RESPCODE == "0000" || $LGD_RESPCODE == "RF00" || $LGD_RESPCODE == "200"){
		$row = sql_fetch(" select sr_point, mb_code from $iw[shop_order_table] where ep_code = '$iw[store]' and sr_code = '$sr_code' ");
		$sr_point = $row["sr_point"];
		$mbcode = $row["mb_code"];
		if($sr_point > 0){
			$pt_datetime = date("Y-m-d H:i:s");
			$row = sql_fetch(" select mb_point from $iw[member_table] where ep_code = '$iw[store]' and mb_code = '$mbcode' and ep_code = '$iw[store]' ");
			$mb_point = $row["mb_point"];
			//포인트 차감취소 내역
			$sql = "insert into $iw[point_table] set
					ep_code = '$iw[store]',
					mb_code = '$mbcode',
					pt_deposit = '$sr_point',
					pt_balance = $mb_point+$sr_point,
					pt_content = '$lgd_productinfo',
					pt_datetime = '$pt_datetime'
					";
			sql_query($sql);
			
			//회원정보 포인트 차감취소
			$sql = "update $iw[member_table] set
					mb_point = mb_point+$sr_point
					where ep_code = '$iw[store]' and mb_code = '$mbcode' and ep_code = '$iw[store]'
					";
			sql_query($sql);
		}
	
		$sql = "update $iw[shop_order_table] set
				sr_display = 2
				where ep_code = '$iw[store]' and mb_code = '$mbcode' and sr_code = '$sr_code'
				";
		sql_query($sql);

		$sql = "update $iw[shop_order_sub_table] set
				srs_display = 7
				where ep_code = '$iw[store]' and mb_code = '$mbcode' and sr_code = '$sr_code'
				";
		sql_query($sql);

		$sql = "update $iw[lgd_table] set
				lgd_display = 2
				where ep_code = '$iw[store]' and mb_code = '$mbcode' and lgd_oid = '$sr_code' and lgd_tid = '$LGD_TID'
				";
		sql_query($sql);

		$sql = "select * from $iw[shop_order_sub_table] where ep_code = '$iw[store]' and mb_code = '$mbcode' and sr_code = '$sr_code'";
		$result = sql_query($sql);

		while($row = @sql_fetch_array($result)){
			$sd_code = $row["sd_code"];
			$so_no = $row["so_no"];
			$srs_amount = $row["srs_amount"];

			$sql = "update $iw[shop_option_table] set
					so_amount = so_amount+$srs_amount
					where ep_code = '$iw[store]' and so_no = '$so_no' and sd_code = '$sd_code'
					";
			sql_query($sql);

			$sql = "update $iw[shop_data_table] set
					sd_sell = sd_sell-$srs_amount
					where ep_code = '$iw[store]' and sd_code = '$sd_code'
					";
			sql_query($sql);
		}
		$lgdc_datetime = date("Y-m-d H:i:s");
		$sql = "insert into $iw[lgd_cancel_table] set
				ep_code = '$iw[store]',
				mb_code = '$mbcode',
				state_sort = '$iw[type]',
				lgd_oid = '$sr_code',
				lgdc_tid = '$LGD_TID',
				lgdc_respcode = '$LGD_RESPCODE',
				lgdc_respmsg = '$LGD_RESPMSG',
				lgdc_mid = '$LGD_MID',
				lgdc_datetime = '$lgdc_datetime',
				lgdc_display = 1
				";
		sql_query($sql);

		alert("취소가 정상적으로 처리되었습니다.","$iw[m_path]/shop_buy_list.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]");
	}else{
		alert("결제 취소정보 등록을 실패하였습니다.\n[오류코드] $Response_Code\n[오류메세지] $Response_Msg","$iw[m_path]/shop_buy_list.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]");
	}
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">




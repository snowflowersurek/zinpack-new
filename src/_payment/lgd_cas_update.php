<?php
//include_once("_common.php");

	$ep_code = "ep136619553152f0a2d5928db";
	$mb_code = "mb1574341829547ebd523a4c4";
	$LGD_OID = "7572480014011216";
	$LGD_TID = "infow2014120316393724722";
	$LGD_CASTAMOUNT = "9500";
	$LGD_CASSEQNO = "001";

	sql_select_db("infoway", $connect_db);
	$sql = " select * from iw_lgd where ep_code = '$ep_code' and mb_code = '$mb_code' and lgd_oid = '$LGD_OID' and lgd_tid = '$LGD_TID'";
	$row = sql_fetch($sql);
	$chk_castamount = $row["lgd_castamount"];
	$chk_cascamount = $row["lgd_cascamount"].";".$LGD_CASCAMOUNT;
	$chk_casflag = $row["lgd_casflag"].";".$LGD_CASFLAG;
	$chk_display = $row["lgd_display"];
	
	if($chk_castamount != $LGD_CASTAMOUNT){
		
		sql_select_db("infoway", $connect_db);
		$sql = "update iw_lgd set
				lgd_castamount = '$LGD_CASTAMOUNT',
				lgd_cascamount = '$chk_cascamount',
				lgd_casseqno = '$LGD_CASSEQNO',
				lgd_casflag = '$chk_casflag'
				where ep_code = '$ep_code' and mb_code = '$mb_code' and lgd_oid = '$LGD_OID' and lgd_tid = '$LGD_TID'
				";
		sql_query($sql);

		if($LGD_AMOUNT == $LGD_CASTAMOUNT){
			$sql = "update iw_lgd set
					lgd_display = 1
					where ep_code = '$ep_code' and mb_code = '$mb_code' and lgd_oid = '$LGD_OID' and lgd_tid = '$LGD_TID'
					";
			sql_query($sql);

			$sql = "update iw_shop_order set
					sr_display = 1
					where ep_code = '$ep_code' and mb_code = '$mb_code' and sr_code = '$LGD_OID'
					";
			sql_query($sql);

			$sql = "update iw_shop_order_sub set
					srs_display = 1
					where ep_code = '$ep_code' and mb_code = '$mb_code' and sr_code = '$LGD_OID'
					";
			sql_query($sql);
			
		}
	}
?>




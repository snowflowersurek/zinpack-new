<?php
include_once("_common.php");
include_once("_check_parameter.php");
include_once "../include/mailer.php";

    /*
     * [상점 결제결과처리(DB) 페이지]
     *
     * 1) 위변조 방지를 위한 hashdata값 검증은 반드시 적용하셔야 합니다.
     *
     */
    $LGD_RESPCODE            = $HTTP_POST_VARS["LGD_RESPCODE"];             // 응답코드: 0000(성공) 그외 실패
    $LGD_RESPMSG             = $HTTP_POST_VARS["LGD_RESPMSG"];              // 응답메세지
    $LGD_MID                 = $HTTP_POST_VARS["LGD_MID"];                  // 상점아이디
    $LGD_OID                 = $HTTP_POST_VARS["LGD_OID"];                  // 주문번호
    $LGD_AMOUNT              = $HTTP_POST_VARS["LGD_AMOUNT"];               // 거래금액
    $LGD_TID                 = $HTTP_POST_VARS["LGD_TID"];                  // LG유플러스에서 부여한 거래번호
    $LGD_PAYTYPE             = $HTTP_POST_VARS["LGD_PAYTYPE"];              // 결제수단코드
    $LGD_PAYDATE             = $HTTP_POST_VARS["LGD_PAYDATE"];              // 거래일시(승인일시/이체일시)
    $LGD_HASHDATA            = $HTTP_POST_VARS["LGD_HASHDATA"];             // 해쉬값
    $LGD_FINANCECODE         = $HTTP_POST_VARS["LGD_FINANCECODE"];          // 결제기관코드(은행코드)
    $LGD_FINANCENAME         = $HTTP_POST_VARS["LGD_FINANCENAME"];          // 결제기관이름(은행이름)
    $LGD_ESCROWYN            = $HTTP_POST_VARS["LGD_ESCROWYN"];             // 에스크로 적용여부
    $LGD_TIMESTAMP           = $HTTP_POST_VARS["LGD_TIMESTAMP"];            // 타임스탬프
    $LGD_ACCOUNTNUM          = $HTTP_POST_VARS["LGD_ACCOUNTNUM"];           // 계좌번호(무통장입금)
    $LGD_CASTAMOUNT          = $HTTP_POST_VARS["LGD_CASTAMOUNT"];           // 입금총액(무통장입금)
    $LGD_CASCAMOUNT          = $HTTP_POST_VARS["LGD_CASCAMOUNT"];           // 현입금액(무통장입금)
    $LGD_CASFLAG             = $HTTP_POST_VARS["LGD_CASFLAG"];              // 무통장입금 플래그(무통장입금) - 'R':계좌할당, 'I':입금, 'C':입금취소
    $LGD_CASSEQNO            = $HTTP_POST_VARS["LGD_CASSEQNO"];             // 입금순서(무통장입금)
    $LGD_CASHRECEIPTNUM      = $HTTP_POST_VARS["LGD_CASHRECEIPTNUM"];       // 현금영수증 승인번호
    $LGD_CASHRECEIPTSELFYN   = $HTTP_POST_VARS["LGD_CASHRECEIPTSELFYN"];    // 현금영수증자진발급제유무 Y: 자진발급제 적용, 그외 : 미적용
    $LGD_CASHRECEIPTKIND     = $HTTP_POST_VARS["LGD_CASHRECEIPTKIND"];      // 현금영수증 종류 0: 소득공제용 , 1: 지출증빙용
	$LGD_PAYER     			 = $HTTP_POST_VARS["LGD_PAYER"];      			// 입금자명
	
    /*
     * 구매정보
     */
    $LGD_BUYER               = $HTTP_POST_VARS["LGD_BUYER"];                // 구매자
    $LGD_PRODUCTINFO         = $HTTP_POST_VARS["LGD_PRODUCTINFO"];          // 상품명
    $LGD_BUYERID             = $HTTP_POST_VARS["LGD_BUYERID"];              // 구매자 ID
    $LGD_BUYERADDRESS        = $HTTP_POST_VARS["LGD_BUYERADDRESS"];         // 구매자 주소
    $LGD_BUYERPHONE          = $HTTP_POST_VARS["LGD_BUYERPHONE"];           // 구매자 전화번호
    $LGD_BUYEREMAIL          = $HTTP_POST_VARS["LGD_BUYEREMAIL"];           // 구매자 이메일
    $LGD_BUYERSSN            = $HTTP_POST_VARS["LGD_BUYERSSN"];             // 구매자 주민번호
    $LGD_PRODUCTCODE         = $HTTP_POST_VARS["LGD_PRODUCTCODE"];          // 상품코드
    $LGD_RECEIVER            = $HTTP_POST_VARS["LGD_RECEIVER"];             // 수취인
    $LGD_RECEIVERPHONE       = $HTTP_POST_VARS["LGD_RECEIVERPHONE"];        // 수취인 전화번호
    $LGD_DELIVERYINFO        = $HTTP_POST_VARS["LGD_DELIVERYINFO"];         // 배송지
    

	$sql = " select * from $payment[lgd_response_table] where lgd_oid = '$LGD_OID' and lgd_tid = '$LGD_TID'";
	$row = sql_fetch($sql);
	$payment_domain = $row["payment_domain"];
	$payment_device = $row["payment_device"];
	$payment_type = $row["payment_type"];
	$lgd_datetime = $row["lgd_datetime"];
	$ep_code = $row["ep_code"];
	$mb_code = $row["mb_code"];

	$LGD_MERTKEY = "50a2213850df98c1a75a48d26f18abb5";	//LG유플러스에서 발급한 상점키로 변경해 주시기 바랍니다.
    $LGD_HASHDATA2 = md5($LGD_MID.$LGD_OID.$LGD_AMOUNT.$LGD_RESPCODE.$LGD_TIMESTAMP.$LGD_MERTKEY);
    
    /*
     * 상점 처리결과 리턴메세지
     *
     * OK  : 상점 처리결과 성공
     * 그외 : 상점 처리결과 실패
     *
     * ※ 주의사항 : 성공시 'OK' 문자이외의 다른문자열이 포함되면 실패처리 되오니 주의하시기 바랍니다.
     */
    $resultMSG = "결제결과 상점 DB처리(LGD_CASNOTEURL) 결과값을 입력해 주시기 바랍니다.";
	
    if ( $LGD_HASHDATA2 == $LGD_HASHDATA ) { //해쉬값 검증이 성공이면
        if ( "0000" == $LGD_RESPCODE ){ //결제가 성공이면
        	if( "R" == $LGD_CASFLAG ) {
                /*
                 * 무통장 할당 성공 결과 상점 처리(DB) 부분
                 * 상점 결과 처리가 정상이면 "OK"
                 */
                //if( 무통장 할당 성공 상점처리결과 성공 )
                $resultMSG = "OK";   
        	}else if( "I" == $LGD_CASFLAG ) {
 	            /*
    	         * 무통장 입금 성공 결과 상점 처리(DB) 부분
        	     * 상점 결과 처리가 정상이면 "OK"
            	 */
				
				sql_select_db("infoway", $connect_db);
				$sql = " select * from iw_lgd where ep_code = '$ep_code' and mb_code = '$mb_code' and lgd_oid = '$LGD_OID' and lgd_tid = '$LGD_TID'";
				$row = sql_fetch($sql);
				$chk_castamount = $row["lgd_castamount"];
				$chk_cascamount = $row["lgd_cascamount"].";".$LGD_CASCAMOUNT;
				$chk_casflag = $row["lgd_casflag"].";".$LGD_CASFLAG;
				$chk_display = $row["lgd_display"];
				
				if($chk_castamount != $LGD_CASTAMOUNT){
					
					sql_select_db("payment", $connect_db);
					$sql = "update $payment[lgd_response_table] set
							lgd_castamount = '$LGD_CASTAMOUNT',
							lgd_cascamount = '$chk_cascamount',
							lgd_casseqno = '$LGD_CASSEQNO',
							lgd_casflag = '$chk_casflag'
							where ep_code = '$ep_code' and mb_code = '$mb_code' and lgd_oid = '$LGD_OID' and lgd_tid = '$LGD_TID'
							";
					sql_query($sql);
					
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

						if($payment_type == "point"){
							$pt_datetime = date("Y-m-d H:i:s");

							$sql = "update iw_member set
									mb_point = mb_point+$LGD_PRODUCTINFO
									where ep_code = '$ep_code' and mb_code = '$mb_code'
									";
							sql_query($sql);
							
							$sql = " select mb_point from iw_member where ep_code = '$ep_code' and mb_code = '$mb_code'";
							$row = sql_fetch($sql);
							$mb_point = $row["mb_point"];
							
							$sql = "update iw_point set
									pt_balance = '$mb_point',
									pt_display = 1
									where ep_code = '$ep_code' and mb_code = '$mb_code' and lgd_oid = '$LGD_OID'
									";
							sql_query($sql);
						}else{
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

						//관리자에게 메일
						$row2 = sql_fetch("select st_title from iw_setting where ep_code = '$ep_code' and gp_code = 'all'");
						$st_title = $row2["st_title"];
						$row2 = sql_fetch("select mb_code from iw_enterprise where ep_code = '$ep_code'");
						$st_mb_code = $row2["mb_code"];
						$row2 = sql_fetch("select mb_mail, mb_sub_mail from iw_member where ep_code = '$ep_code' and mb_code = '$st_mb_code'");
						
						$to = $row2["mb_mail"];
						$from = "no-reply@wizwindigital.com";
						$fromName = $st_title;
						$subject = $st_title.' 주문이 접수되었습니다.';
						$content = '
							<div style="padding:20px;font-family:Arial,\'Apple SD Gothic Neo\',\'맑은 고딕\',\'Malgun Gothic\',\'돋움\',dotum;">
								<table width="100%" align="center" border="0" cellpadding="0" cellspacing="0" style="max-width:600px;text-align:left;">
									<tbody>
										<tr>
											<td style="font-size:24px;line-height:34px;color:#333;"><strong>'.$st_title.'</strong><br>주문이 접수되었습니다.</td>
										</tr>
										<tr>
											<td height="32"></td>
										</tr>
										<tr>
											<td style="border-top:1px solid #333;">
												<table width="100%" border="0" cellpadding="0" cellspacing="0">
													<tbody>
														<tr>
															<td width="100" style="padding:16px 0 16px 8px;font-size:13px;line-height:20px;color:#999;vertical-align:top;border-bottom:1px solid #eee;">주문일시</td>
															<td style="padding:16px 0;font-size:14px;line-height:20px;color:#333;vertical-align:top;border-bottom:1px solid #eee;">'.$lgd_datetime.'</td>
														</tr>
														<tr>
															<td width="100" style="padding:16px 0 16px 8px;font-size:13px;line-height:20px;color:#999;vertical-align:top;border-bottom:1px solid #eee;">주문상품</td>
															<td style="padding:16px 0;font-size:14px;line-height:20px;color:#333;vertical-align:top;border-bottom:1px solid #eee;">'.$LGD_PRODUCTINFO.'</td>
														</tr>
														<tr>
															<td width="100" style="padding:16px 0 16px 8px;font-size:13px;line-height:20px;color:#999;vertical-align:top;border-bottom:1px solid #eee;">주문회원</td>
															<td style="padding:16px 0;font-size:14px;line-height:20px;color:#333;vertical-align:top;border-bottom:1px solid #eee;">'.$LGD_BUYER.'</td>
														</tr>
														<tr>
															<td width="100" style="padding:16px 0 16px 8px;font-size:13px;line-height:20px;color:#999;vertical-align:top;border-bottom:1px solid #eee;">결제금액</td>
															<td style="padding:16px 0;font-size:14px;line-height:20px;color:#333;vertical-align:top;border-bottom:1px solid #eee;">
																<span style="font-size:18px;font-weight:bold;color:#df3926;">'.number_format($LGD_AMOUNT).'원</span>
																<span style="margin:0 4px;font-size:13px;">'.$LGD_FINANCENAME.' 가상계좌</span>
															</td>
														</tr>
													</tbody>
												</table>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						';
						
						mailer($fromName, $from, $to, $subject, $content, true);

						//판매자에게 메일
						$sql = "select DISTINCT(seller_mb_code) from iw_shop_order_sub where ep_code = '$ep_code' and mb_code = '$mb_code' and sr_code = '$LGD_OID'";
						$result = sql_query($sql);
						
						$to = "";
						while($row = @sql_fetch_array($result)){
							$seller_mb_code = $row["seller_mb_code"];
							$row2 = sql_fetch("select mb_mail from iw_member where ep_code = '$ep_code' and mb_code = '$seller_mb_code'");
							$to .= $row2["mb_mail"].",";
						}
						
						mailer($fromName, $from, $to, $subject, $content, true);
					}
				}
            	//if( 무통장 입금 성공 상점처리결과 성공 ) 
            	$resultMSG = "OK";
        	}else if( "C" == $LGD_CASFLAG ) {
 	            /*
    	         * 무통장 입금취소 성공 결과 상점 처리(DB) 부분
        	     * 상점 결과 처리가 정상이면 "OK"
            	 */
				
				sql_select_db("infoway", $connect_db);
				$sql = " select * from iw_lgd where ep_code = '$ep_code' and mb_code = '$mb_code' and lgd_oid = '$LGD_OID' and lgd_tid = '$LGD_TID'";
				$row = sql_fetch($sql);
				$chk_castamount = $row["lgd_castamount"];
				$chk_cascamount = $row["lgd_cascamount"].";-".$LGD_CASCAMOUNT;
				$chk_casflag = $row["lgd_casflag"].";".$LGD_CASFLAG;
				$chk_display = $row["lgd_display"];
				
				if($chk_castamount != $LGD_CASTAMOUNT){
					sql_select_db("payment", $connect_db);
					$sql = "update $payment[lgd_response_table] set
							lgd_castamount = '$LGD_CASTAMOUNT',
							lgd_cascamount = '$chk_cascamount',
							lgd_casseqno = '$LGD_CASSEQNO',
							lgd_casflag = '$chk_casflag'
							where ep_code = '$ep_code' and mb_code = '$mb_code' and lgd_oid = '$LGD_OID' and lgd_tid = '$LGD_TID'
							";
					sql_query($sql);
					
					sql_select_db("infoway", $connect_db);
					$sql = "update iw_lgd set
							lgd_castamount = '$LGD_CASTAMOUNT',
							lgd_cascamount = '$chk_cascamount',
							lgd_casseqno = '$LGD_CASSEQNO',
							lgd_casflag = '$chk_casflag'
							where ep_code = '$ep_code' and mb_code = '$mb_code' and lgd_oid = '$LGD_OID' and lgd_tid = '$LGD_TID'
							";
					sql_query($sql);

					if($chk_display == 1){
						$sql = "update iw_lgd set
								lgd_display = 5
								where ep_code = '$ep_code' and mb_code = '$mb_code' and lgd_oid = '$LGD_OID' and lgd_tid = '$LGD_TID'
								";
						sql_query($sql);

						if($payment_type == "point"){
							$pt_datetime = date("Y-m-d H:i:s");

							$sql = "update iw_member set
									mb_point = mb_point-$LGD_PRODUCTINFO
									where ep_code = '$ep_code' and mb_code = '$mb_code'
									";
							sql_query($sql);
						
							$sql = " select mb_point from iw_member where ep_code = '$ep_code' and mb_code = '$mb_code'";
							$row = sql_fetch($sql);
							$mb_point = $row["mb_point"];
						
							$sql = "update iw_point set
									pt_balance = '$mb_point',
									pt_display = 5
									where ep_code = '$ep_code' and mb_code = '$mb_code' and lgd_oid = '$LGD_OID'
									";
							sql_query($sql);
						}else{
							$sql = "update iw_shop_order set
									sr_display = 5
									where ep_code = '$ep_code' and mb_code = '$mb_code' and sr_code = '$LGD_OID'
									";
							sql_query($sql);

							$sql = "update iw_shop_order_sub set
									srs_display = 5
									where ep_code = '$ep_code' and mb_code = '$mb_code' and sr_code = '$LGD_OID'
									";
							sql_query($sql);
						}
					}
				}
            	//if( 무통장 입금취소 성공 상점처리결과 성공 ) 
            	$resultMSG = "OK";
        	}
        } else { //결제가 실패이면
            /*
             * 거래실패 결과 상점 처리(DB) 부분
             * 상점결과 처리가 정상이면 "OK"
             */  
            //if( 결제실패 상점처리결과 성공 ) 
            $resultMSG = "OK";     
        }
    } else { //해쉬값이 검증이 실패이면
        /*
         * hashdata검증 실패 로그를 처리하시기 바랍니다. 
         */      
        $resultMSG = "결제결과 상점 DB처리(LGD_CASNOTEURL) 해쉬값 검증이 실패하였습니다.";     
    }
    
    echo $resultMSG;
?>
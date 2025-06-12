<?php
include_once("_common.php");
if ($iw[level]=="guest") alert(national_language($iw[language],"a0003","로그인 해주시기 바랍니다."),"$iw[m_path]/all_login.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&re_url=$iw[re_url]");
include_once("_head.php");

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
if($iw[language]=="ko"){
	$form_action = "all_point_req.php?type=".$iw[type]."&ep=".$iw[store]."&gp=".$iw[group];
	$arrPrice = array("9,900 원", "19,900 원", "29,900 원", "39,900 원", "49,900 원", "99,900 원", "199,900 원");
	$arrPriceValue = array("9900", "19900", "29900", "39900", "49900", "99900", "199900");
}else if($iw[language]=="en"){
	$form_action = "all_point_pay_load.php?type=".$iw[type]."&ep=".$iw[store]."&gp=".$iw[group];
	$arrPrice = array("US$ 9.99", "US$ 19.99", "US$ 29.99", "US$ 39.99", "US$ 49.99", "US$ 99.99", "US$ 199.99");
	$arrPriceValue = array("9.99", "19.99", "29.99", "39.99", "49.99", "99.99", "199.99");
}

$protocol = "http://";
if ($_SERVER["HTTP_HOST"] == "www.aviation.co.kr" || $_SERVER["HTTP_HOST"] == "www.info-way.co.kr") {
	$protocol = "https://";
}

$URLTORETURN		= $protocol.$_SERVER["SERVER_NAME"]."/bbs/m/all_point_res.php?type=".$iw[type]."&ep=".$iw[store]."&gp=".$iw[group];
$REQURL		= $protocol.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
?>
<?if($iw[language]=="ko"){?><div id="LGD_ACTIVEX_DIV"></div><?}?> <!-- ActiveX 설치 안내 Layer 입니다. 수정하지 마세요. -->
<div class="content">
	<div class="breadcrumb-box input-group">
		<ol class="breadcrumb">
			<li><a href="http://<?=$_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]?>">포인트 충전</a></li>
		</ol>
	</div>

	<div class="masonry">
	<form id="LGD_PAYINFO" name="LGD_PAYINFO" action="<?=$form_action?>" method="post">
		<input type="hidden" name="PAYMENT_DEVICE" value="<?=$pay_device?>" />
		<input type="hidden" name="LGD_BUYER" value="<?=$mb_name?>" />
		<input type="hidden" name="LGD_BUYERID" value="<?=$iw[member]?>" />
		<input type="hidden" name="LGD_PRODUCTINFO" id="LGD_PRODUCTINFO" value="650" />
		<input type="hidden" name="LGD_BUYEREMAIL" value="<?=$mb_mail?>" />
		<input type="hidden" name="URL_TO_RETURN"				id="URL_TO_RETURN"		value="<?= $URLTORETURN ?>">
		<input type="hidden" name="REQUEST_URL"				id="REQUEST_URL"		value="<?= $REQURL ?>">
		<div class="grid-sizer"></div>

		<div class="masonry-item w-full h-full">
			<div class="box br-theme">
				<ul class="list-inline">
					<li><strong><?=national_language($iw[language],"a0131","보유 포인트");?>:</strong> <?=$mb_point?> Point</li>
					<li>
						<strong><?=national_language($iw[language],"a0132","현재 Point 환율");?>:</strong> 
						<?if($iw[language]=="ko"){?>
							1 Point = <?=$iw['buy_rate']?> 원
						<?}else if($iw[language]=="en"){?>
							1000 Point = US$ <?=$iw['buy_rate']?>
						<?}?>
					</li>
				</ul>
			</div> <!-- /.box -->
		</div> <!-- /.masonry-item -->
		
		<div class="masonry-item w-6 h-4">
			<div class="box br-theme">
				<h4 class="media-heading"><?=national_language($iw[language],"a0133","충전하실 금액");?></h4>
				<div class="clearfix"></div>
				<div class="well well-sm">
					<div class="radio">
						<label for="일만원"><input type="radio" name="LGD_AMOUNT" value="<?=$arrPriceValue[0]?>" id="일만원" onClick="amount_change('<?=$arrPrice[0]?>','650')" checked /> <?=$arrPrice[0]?> ( 650 Point )</label>
					</div>
					<div class="radio">
						<label for="이만원"><input type="radio" name="LGD_AMOUNT" value="<?=$arrPriceValue[1]?>" id="이만원" onClick="amount_change('<?=$arrPrice[1]?>','1310')" /> <?=$arrPrice[1]?> ( 1,310 Point )</label>
					</div>
					<div class="radio">
						<label for="삼만원"><input type="radio" name="LGD_AMOUNT" value="<?=$arrPriceValue[2]?>" id="삼만원" onClick="amount_change('<?=$arrPrice[2]?>','2030')" /> <?=$arrPrice[2]?> ( 2,030 Point )</label>
					</div>
					<div class="radio">
						<label for="사만원"><input type="radio" name="LGD_AMOUNT" value="<?=$arrPriceValue[3]?>" id="사만원" onClick="amount_change('<?=$arrPrice[3]?>','2690')" /> <?=$arrPrice[3]?> ( 2,690 Point )</label>
					</div>
					<div class="radio">
						<label for="오만원"><input type="radio" name="LGD_AMOUNT" value="<?=$arrPriceValue[4]?>" id="오만원" onClick="amount_change('<?=$arrPrice[4]?>','3360')" /> <?=$arrPrice[4]?> ( 3,360 Point )</label>
					</div>
					<div class="radio">
						<label for="육만원"><input type="radio" name="LGD_AMOUNT" value="<?=$arrPriceValue[5]?>" id="육만원" onClick="amount_change('<?=$arrPrice[5]?>','6800')" /> <?=$arrPrice[5]?> ( 6,800 Point )</label>
					</div>
					<div class="radio">
						<label for="칠만원"><input type="radio" name="LGD_AMOUNT" value="<?=$arrPriceValue[6]?>" id="칠만원" onClick="amount_change('<?=$arrPrice[6]?>','13500')" /> <?=$arrPrice[6]?> ( 13,500 Point )</label>
					</div>
				</div>
			</div> <!-- /.box -->
		</div> <!-- /.masonry-item -->
		
		<div class="masonry-item w-6 h-4">
			<div class="box br-theme">
				<h4 class="media-heading"><?=national_language($iw[language],"a0134","결제금액");?><span class="pull-right" id="priceTotal"><?=$arrPrice[0]?></span></h4>
				<div class="clearfix"></div>
				<br>
				<h4 class="media-heading"><?=national_language($iw[language],"a0135","결제방법");?></h4>
				<div class="clearfix"></div>
				<div class="well well-sm">
					<?if($iw[language]=="ko"){?>
						<div class="radio">
							<label for="신용카드"><input type="radio" name="LGD_CUSTOM_FIRSTPAY" value="SC0010" id="신용카드" checked/> 신용카드</label>
						</div>
						<div class="radio">
							<label for="가상계좌"><input type="radio" name="LGD_CUSTOM_FIRSTPAY" value="SC0040" id="가상계좌" /> 무통장(가상계좌)</label>
						</label>
					<?}else if($iw[language]=="en"){?>
						<div class="radio">
							<label for="PAYPAL"><input type="radio" name="LGD_CUSTOM_FIRSTPAY" value="PAYPAL" id="PAYPAL" checked /> Paypal <img src="<?=$iw[design_path]?>/img/pay_paypal.jpg"></label>
						</div>
						<div class="radio">
							<label for="ALIPAY"><input type="radio" name="LGD_CUSTOM_FIRSTPAY" value="ALIPAY" id="ALIPAY" /> Alipay <img src="<?=$iw[design_path]?>/img/pay_alipay.jpg"></label>
						</label>
					<?}?>
				</div>
				<a href="javascript:LGD_PAYINFO.submit();" class="btn btn-theme"><?=national_language($iw[language],"a0136","결제하기");?></a>
			</div> <!-- /.box -->
		</div> <!-- /.masonry-item -->
	</form>
	</div> <!-- /.masonry -->
</div>

<script type="text/javascript">
	function amount_change(amount, point) {
		document.getElementById('priceTotal').innerHTML = amount;
		document.getElementById('LGD_PRODUCTINFO').value = point;
	}
</script>
<!--  UTF-8 인코딩 사용 시는 xpay.js 대신 xpay_utf-8.js 을  호출하시기 바랍니다.-->
<script language="javascript" src="<?= $_SERVER['SERVER_PORT']!=443?"http":"https" ?>://xpay.uplus.co.kr<?=($CST_PLATFORM == "test")?($_SERVER['SERVER_PORT']!=443?":7080":":7443"):""?>/xpay/js/xpay_utf-8.js" type="text/javascript"></script>

<?
include_once("_tail.php");
?>
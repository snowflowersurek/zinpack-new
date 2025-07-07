<?php
include_once("_common.php");
if ($iw[level]=="guest") alert("로그인 해주시기 바랍니다. ","$iw[m_path]/all_login.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&re_url=$iw[re_url]");
include_once("_head.php");

$sql = "select * from $iw[member_table] where mb_code = '$iw[member]' and ep_code = '$iw[store]'";
$row = sql_fetch($sql);
$mb_point = $row["mb_point"];
?>
<div class="content">
	<div class="breadcrumb-box input-group">
		<ol class="breadcrumb">
			<li><a href="<?=$iw[m_path]?>/all_point_list.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>"><?=national_language($iw[language],"a0262","포인트 내역");?></a></li>
		</ol>
	</div>
	<div class="box br-theme">
		<ul class="list-inline pull-right">
			<li><?=national_language($iw[language],"a0137","잔여포인트");?>: <?=$mb_point?> Point</li>
			<li><a href="<?=$iw[m_path]?>/all_point_charge.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" class="btn btn-theme"><?=national_language($iw[language],"a0138","충전하기");?></a></li>
		</ul>
		<div class="clearfix"></div>
		
		<table class="table responsive-table">
			<colgroup>
			</colgroup>
			<thead>
				<tr>
					<th><?=national_language($iw[language],"a0127","날짜");?></th>
					<th><?=national_language($iw[language],"a0261","내역");?></th>
					<th><?=national_language($iw[language],"a0139","적립");?></th>
					<th class="text-right"><?=national_language($iw[language],"a0140","사용");?></th>
					<th class="text-right"><?=national_language($iw[language],"a0141","잔여");?></th>
					<th class="text-right">결제</th>
				</tr>
			</thead>
			<tbody>
			<?php
				$sql = "select * from $iw[point_table] where ep_code = '$iw[store]' and mb_code = '$iw[member]'";
				$result = sql_query($sql);
				$total_line = mysqli_num_rows($result);

				$max_line = 10;
				$max_page = 5;
					
				$page = $_GET["page"];
				if(!$page) $page=1;
				$start_line = ($page-1)*$max_line;
				$total_page = ceil($total_line/$max_line);
				
				if($total_line < $max_line) {
					$end_line = $total_line;
				} else if($page==$total_page) {
					$end_line = $total_line - ($max_line*($total_page-1));
				} else {
					$end_line = $max_line;
				}

				$sql = "select * from $iw[point_table] where ep_code = '$iw[store]' and mb_code = '$iw[member]' order by pt_no desc limit $start_line, $end_line";
				$result = sql_query($sql);

				$i=0;
				while($row = @sql_fetch_array($result)){
					$pt_deposit = $row["pt_deposit"];
					$pt_withdraw = $row["pt_withdraw"];
					$pt_balance = $row["pt_balance"];
					$pt_content = $row["pt_content"];
					$lgd_oid = $row["lgd_oid"];
					$pt_display = $row["pt_display"];
					if(date("Y", strtotime($row["pt_datetime"]))==date("Y")){
						$pt_datetime = date("m.d", strtotime($row["pt_datetime"]));
					}else{
						$pt_datetime = date("Y.m.d", strtotime($row["pt_datetime"]));
					}

					$sql2 = "select * from $iw[lgd_table] where ep_code = '$iw[store]' and mb_code = '$iw[member]' and lgd_oid = '$lgd_oid' and lgd_display <> 0";
					$row2 = sql_fetch($sql2);
					$lgd_mid = $row2["lgd_mid"];
					$lgd_tid = $row2["lgd_tid"];
					$lgd_financename = $row2["lgd_financename"];
					$lgd_payer = $row2["lgd_payer"];
					$lgd_accountnum = $row2["lgd_accountnum"];
					$lgd_amount = number_format($row2["lgd_amount"]);
			?>
				<tr>
					<td data-th="<?=national_language($iw[language],"a0127","날짜");?>"><?=$pt_datetime?></td>
					<td data-th="<?=national_language($iw[language],"a0261","내역");?>"><?=$pt_content?></td>
					<td data-th="<?=national_language($iw[language],"a0139","적립");?>" class="text-right"><?=$pt_deposit?></td>
					<td data-th="<?=national_language($iw[language],"a0140","사용");?>" class="text-right"><?=$pt_withdraw?></td>
					<td data-th="<?=national_language($iw[language],"a0141","잔여");?>" class="text-right"><?=$pt_balance?></td>
					<td data-th="결제" class="text-right">
					<?php if($pt_display==5){?>
						<a class="label label-warning" href="javascript:pay_cas_confirm('<?=$lgd_financename?>', '<?=$lgd_accountnum?>', '<?=$lgd_payer?>', '<?=$lgd_amount?> 원')">입금대기</a>
					<?php }else if($lgd_oid){
							$mid_pc = "wiz2016";
							$mid_mobile = "wiz2016";
							$pay_receipt = "";
							if($iw['pay_platform']=="test"){
								$mid_pc = "t".$mid_pc;
								$mid_mobile = "t".$mid_mobile;
								$pay_receipt = ":7085";
							}
							$mertkey = "50a2213850df98c1a75a48d26f18abb5";
							$authdata = md5($lgd_mid.$lgd_tid.$mertkey);
						?>
						<script language="JavaScript" src="http://pgweb.uplus.co.kr<?=$pay_receipt?>/WEB_SERVER/js/receipt_link.js"></script>
						<a class="label label-success" href="javascript:showReceiptByTID('<?=$lgd_mid?>', '<?=$lgd_tid?>', '<?=$authdata?>')">영수증</a>
					<?php }?>
					</td>
				</tr>
			<?php
				$i++;
				}
				if($i==0) echo "<tr><td colspan='6'>".national_language($iw[language],'a0142','포인트 내역이 없습니다.')."</td></tr>";
			?>
			</tbody>
		</table>
	</div> <!-- /.box -->

	<div class="pagContainer text-center">
		<ul class="pagination">
			<?php
				if($total_page!=0){
					if($page>$total_page) { $page=$total_page; }
					$start_page = ((ceil($page/$max_page)-1)*$max_page)+1;
					$end_page = $start_page+$max_page-1;
				 
					if($end_page>$total_page) {$end_page=$total_page;}
				 
					if($page>$max_page) {
						$pre = $start_page - 1;
						echo "<li><a href='$PHP_SELF?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&page=$pre'>&laquo;</a></li>";
					} else {
						echo "<li><a href='#'>&laquo;</a></li>";
					}
					
					for($i=$start_page;$i<=$end_page;$i++) {
						if($i==$page) echo "<li class='active'><a href='#'>$i</a></li>";
						else          echo "<li><a href='$PHP_SELF?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&page=$i'>$i</a></li>";
					}
				 
					if($end_page<$total_page) {
						$next = $end_page + 1;
						echo "<li><a href='$PHP_SELF?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&page=$next'>&raquo;</a></li>";
					} else {
						echo "<li><a href='#'>&raquo;</a></li>";
					}
				}
			?>
		</ul>
	</div>
</div>

<script type="text/javascript">
	function win_open(url, name, option)
    {
        var popup = window.open(url, name, option);
        popup.focus();
    }

    // 우편번호 창
    function delivery_check(num)
    {
        url = "http://service.epost.go.kr/trace.RetrieveRegiPrclDeliv.postal?ems_gubun=E&sid1="+num+"&POST_CODE=&mgbn=trace&traceselect=1&postNum="+num+"&x=27&y=1";
        win_open(url, "배송조회", "left=50,top=50,width=616,height=460,scrollbars=1");
    }

	function pay_cas_confirm(bank,account,user,price)
	{
		alert("은행: "+bank+"\n계좌번호: "+account+"\n입금자명: "+user+"\n입금액: "+price);
	}
</script>
<?php
include_once("_tail.php");
?>




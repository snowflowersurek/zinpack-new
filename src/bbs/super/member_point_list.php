<?php
include_once("_common.php");
include_once("_head.php");
?>

<div class="adminTopTitle">
	<div class="simbol"></div>포인트 내역
</div>

<div class="adminView">
	<?
		if($_POST['search']){
			$search = $_POST['search'];
			$searchs = $_POST['searchs'];
		}else{
			$search = $_GET['search'];
			$searchs = $_GET['searchs'];
		}
		if($search =="e"){
			$search_sql = "where ep_code like '%$searchs%'";
		}else if($search =="m"){
			$search_sql = "where mb_code like '%$searchs%'";
		}else if($search =="i"){
			$search_sql = "where pt_content like '%$searchs%'";
		}

	$row = sql_fetch(" select count(*) as cnt from $iw[point_table] $search_sql ");
	$total_count = $row[cnt];
	$row = sql_fetch(" select sum(pt_deposit) as cnt from $iw[point_table] $search_sql ");
	$total_deposit = $row[cnt];
	$row = sql_fetch(" select sum(pt_withdraw) as cnt from $iw[point_table] $search_sql ");
	$total_withdraw = $row[cnt];
	?>
	(건수 : <?=number_format($total_count)?>) (적립 : <?=number_format($total_deposit)?>) (사용 : <?=number_format($total_withdraw)?>) (잔액 : <?=number_format($total_deposit - $total_withdraw)?>)
</div>
<div class="adminView">
	<table class="adminListTable">
		<tr class="tbTitle">
			<td width="80px">날짜</td>
				<td width="200px">업체명<br/>(회원코드)</td>
				<td width="400px">내용</td>
				<td width="70px">적립</td>
				<td width="70px">사용</td>
		</tr>
		<?
			$sql = "select * from $iw[point_table] $search_sql";
			$result = sql_query($sql);
			$total_line = mysql_num_rows($result);

			$max_line = 10;
			$max_page = 10;
				
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

			$sql = "select * from $iw[point_table] $search_sql order by pt_no desc limit $start_line, $end_line";
			$result = sql_query($sql);

			$i=0;
			while($row = @sql_fetch_array($result)){
				$pt_deposit = number_format($row["pt_deposit"]);
				$pt_withdraw = number_format($row["pt_withdraw"]);
				$pt_balance = number_format($row["pt_balance"]);
				$pt_content = $row["pt_content"];
				$pt_datetime = date("Y.m.d", strtotime($row["pt_datetime"]));
				$mb_code = $row["mb_code"];
				$ep_code = $row["ep_code"];

				$row2 = sql_fetch("select ep_corporate from $iw[enterprise_table] where ep_code = '$ep_code'");
				$ep_corporate = $row2["ep_corporate"];
		?>
			<tr>
				<td align="center"><?=$pt_datetime?></td>
				<td align="center"><a href="<?=$PHP_SELF?>?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&search=e&searchs=<?=$ep_code?>" ><?=$ep_corporate?></a><br/>(<a href="<?=$PHP_SELF?>?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&search=m&searchs=<?=$mb_code?>" ><?=$mb_code?></a>)</td>
				<td><?=$pt_content?></td>
				<td align="right"><?=$pt_deposit?></td>
				<td align="right"><?=$pt_withdraw?></td>
			</tr>
		<?
			$i++;
			}
			if($i==0) echo "<tr><td colspan='6' align='center' height='200px'>포인트 내역이 없습니다.</td></tr>";
		?>
	</table>
</div>

<div class="adminListPage">
	<table align="center">
		<tr>
			<td>
	<?
		if($total_page!=0){
			if($page>$total_page) { $page=$total_page; }
			$start_page = ((ceil($page/$max_page)-1)*$max_page)+1;
			$end_page = $start_page+$max_page-1;
		 
			if($end_page>$total_page) {$end_page=$total_page;}
		 
			if($page>$max_page) {
				$pre = $start_page - 1;
				echo "<a href='$PHP_SELF?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&page=$pre&search=$search&searchs=$searchs'><div class='prev'></div></a>";
			} else {
				echo "<div class='prev'></div>";
			}
			
			for($i=$start_page;$i<=$end_page;$i++) {
				if($i==$page) echo "<div class='nowPage'>$i</div>";
				else          echo "<a href='$PHP_SELF?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&page=$i&search=$search&searchs=$searchs'><div class='pageNum'>$i</div></a>";
			}
		 
			if($end_page<$total_page) {
				$next = $end_page + 1;
				echo "<a href='$PHP_SELF?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&page=$next&search=$search&searchs=$searchs'><div class='next'></div></a>";
			} else {
				echo "<div class='next'></div>";
			}
		}
	?>
			</td>
		</tr>
	</table>
</div>

<div class="adminListSearch">
	<form name="search_form" id="search_form" action="<?=$PHP_SELF?>?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" method="post">
	<table align="center">
		<tr>
			<td>	
			<select class="selectBox" name="search">
				<option value="e" <?if($search == "e"){?>selected<?}?>>업체코드</option>
				<option value="m" <?if($search == "m"){?>selected<?}?>>회원코드</option>
				<option value="i" <?if($search == "i"){?>selected<?}?>>내용</option>
			</select>
			<input type="text" class="inputText" name="searchs" size="40" value="<?=$searchs?>" />
			<a href="javascript:search_form.submit();"><div class="btn">검색</div></a>
		</td>
	</tr>
	</table>
	</form>
</div>
<?
include_once("_tail.php");
?>
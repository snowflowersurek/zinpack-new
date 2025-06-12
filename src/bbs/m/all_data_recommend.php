<?php
include_once("_common.php");
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?
//1일 지난 데이터 삭제
$recommend_confirm_date = date('Y-m-d H:i:s', strtotime('-1 days'));
$sql = "delete from $iw[recommend_table] where date(rc_date) < '$recommend_confirm_date'";
sql_query($sql);

$board = $_GET[board];
$idx = $_GET[idx];

if($board == "board"){
	$rc_type = 1;
	if($iw[type]=="shop"){
		$row = sql_fetch(" select count(*) as cnt from $iw[shop_data_table] where ep_code = '$iw[store]' and sd_display = 1 and sd_no = '$idx' ");
	}else if($iw[type]=="doc"){
		$row = sql_fetch(" select count(*) as cnt from $iw[doc_data_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and dd_display = 1 and dd_no = '$idx' ");
	}else if($iw[type]=="mcb"){
		$row = sql_fetch(" select count(*) as cnt from $iw[mcb_data_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and md_display = 1 and md_no = '$idx' ");
	}else if($iw[type]=="publishing"){
		$row = sql_fetch(" select count(*) as cnt from $iw[publishing_books_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and book_display = 1 and BookID = '$idx' ");
	}else if($iw[type]=="book"){
		$row = sql_fetch(" select count(*) as cnt from $iw[book_data_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and bd_display = 1 and bd_no = '$idx' ");
	}
}else{
	$rc_type = 2;
	$row = sql_fetch(" select count(*) as cnt from $iw[comment_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and state_sort = '$iw[type]' and cm_display = 1 and cm_no = '$idx' ");
}

$row2 = sql_fetch(" select count(*) as cnt from $iw[recommend_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and state_sort = '$iw[type]' and mb_code = '$iw[member]' and rc_type = '$rc_type' and rc_code = '$idx' ");

if ($iw[level] != "guest" && $row[cnt] && !$row2[cnt]) {
	$rc_datetime = date("Y-m-d H:i:s");

	$sql = "insert into $iw[recommend_table] set
			ep_code = '$iw[store]',
			gp_code = '$iw[group]',
			state_sort = '$iw[type]',
			mb_code = '$iw[member]',
			rc_type = '$rc_type',
			rc_code = '$idx',
			rc_date = '$rc_datetime'
			";

	sql_query($sql);

	if($board == "board"){
		if($iw[type]=="shop"){
			$sql = "update $iw[shop_data_table] set sd_recommend = sd_recommend+1 where ep_code = '$iw[store]' and sd_display = 1 and sd_no = '$idx'";
		}else if($iw[type]=="doc"){
			$sql = "update $iw[doc_data_table] set dd_recommend = dd_recommend+1 where ep_code = '$iw[store]' and gp_code='$iw[group]' and dd_display = 1 and dd_no = '$idx'";
		}else if($iw[type]=="mcb"){
			$sql = "update $iw[mcb_data_table] set md_recommend = md_recommend+1 where ep_code = '$iw[store]' and gp_code='$iw[group]' and md_display = 1 and md_no = '$idx'";
		}else if($iw[type]=="publishing"){
			$sql = "update $iw[publishing_books_table] set book_recommend = book_recommend+1 where ep_code = '$iw[store]' and gp_code='$iw[group]' and book_display = 1 and BookID = '$idx'";
		}else if($iw[type]=="book"){
			$sql = "update $iw[book_data_table] set bd_recommend = bd_recommend+1 where ep_code = '$iw[store]' and gp_code='$iw[group]' and bd_display = 1 and bd_no = '$idx'";
		}
	}else{
		$sql = "update $iw[comment_table] set cm_recommend = cm_recommend+1 where ep_code = '$iw[store]' and gp_code='$iw[group]' and state_sort = '$iw[type]' and cm_display = 1 and cm_no = '$idx'";
	}
	sql_query($sql);
}

if($board == "board"){
	if($iw[type]=="shop"){
		$row = sql_fetch(" select sd_recommend as recommend from $iw[shop_data_table] where ep_code = '$iw[store]' and sd_display = 1 and sd_no = '$idx' ");
	}else if($iw[type]=="doc"){
		$row = sql_fetch(" select dd_recommend as recommend from $iw[doc_data_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and dd_display = 1 and dd_no = '$idx' ");
	}else if($iw[type]=="mcb"){
		$row = sql_fetch(" select md_recommend as recommend from $iw[mcb_data_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and md_display = 1 and md_no = '$idx' ");
	}else if($iw[type]=="publishing"){
		$row = sql_fetch(" select book_recommend as recommend from $iw[publishing_books_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and book_display = 1 and BookID = '$idx' ");
	}else if($iw[type]=="book"){
		$row = sql_fetch(" select bd_recommend as recommend from $iw[book_data_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and bd_display = 1 and bd_no = '$idx' ");
	}
}else{
	$row = sql_fetch(" select cm_recommend as recommend from $iw[comment_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and state_sort = '$iw[type]' and cm_display = 1 and cm_no = '$idx' ");
}

echo $row[recommend];
?>
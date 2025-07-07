<?php
header('Content-Type: text/xml');

include_once("_common.php");

$row = sql_fetch("select * from $iw[enterprise_table] where ep_code = '$iw[store]'");
if(!$row[ep_no]) exit;
$ep_nick = $row[ep_nick];
if($row[ep_domain]!=""){
	$ep_domain = $row[ep_domain];
}else{
	$ep_domain = $iw[default_domain];
}

if($iw['group'] != "all"){
	$row = sql_fetch("select * from $iw[group_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and gp_display = 1");
	if(!$row[gp_no]) exit;
	$gp_nick = $row[gp_nick];
	if($ep_domain == $iw[default_domain]){
		$rss_link = "http://".$ep_domain."/main/".$ep_nick."/".$gp_nick;
	}else{
		$rss_link = "http://".$gp_nick.".".$ep_domain;
	}
	$upload_path = "http://www".$iw[cookie_domain]."/mcb/".$ep_nick."/".$gp_nick;
}else{
	if($ep_domain == $iw[default_domain]){
		$rss_link = "http://".$ep_domain."/main/".$ep_nick;
	}else{
		$rss_link = "http://www.".$ep_domain;
	}
	$upload_path = "http://www".$iw[cookie_domain]."/mcb/".$ep_nick."/all";
}

$row = sql_fetch("select * from $iw[setting_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]'");
if(!$row[st_no]) exit;
$rss_title = $row[st_title];


if($iw['category'] != "all"){
	$row = sql_fetch("select * from $iw[home_menu_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and hm_code = '$iw[category]'");
	if (!$row[hm_no]){
		exit;
	}else{
		$rss_title .= " - ".$row[hm_name];
		$state_sort = $row[state_sort];
		$hm_deep = $row[hm_deep];
		$hm_upper_code = "and hm_upper_code = '".$row[hm_code]."'";

		$sql_cg_code = "";
		if($state_sort=="mcb"){
			$sql_cg_code = "and cg_code in('".$row[cg_code]."'";
		}
		
		for ($i=$hm_deep+1; $i<5; $i++) {
			$sql2 = "select * from $iw[home_menu_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and hm_deep = '$i' $hm_upper_code order by hm_no asc";
			$result2 = sql_query($sql2);
			$hm_upper_code = "";
			while($row2 = @sql_fetch_array($result2)){
				$state_sort2 = $row2[state_sort];
				if(strlen($hm_upper_code) == 0){
					$hm_upper_code = "and hm_upper_code in ('".$row2[hm_code]."'";
				}else{
					$hm_upper_code .= ", '".$row2[hm_code]."'";
				}
				if($state_sort2=="mcb" || $state_sort2=="book" || $state_sort2=="doc" || $state_sort2=="shop"){
					if(strlen($sql_cg_code) == 0){
						$sql_cg_code = "and cg_code in('".$row2[cg_code]."'";
					}else{
						$sql_cg_code .= ", '".$row2[cg_code]."'";
					}
				}
			}
			if(strlen($hm_upper_code) == 0){
				$hm_upper_code = "and hm_upper_code = null";
			}else{
				$hm_upper_code .= ")";
			}
		}

		if(strlen($sql_cg_code) == 0){
			exit;
		}else{
			$sql_cg_code .= ")";
		}
	}
}
$rss_title = preg_replace("/[ #\&\+\-%@=\/\\\:;,\.'\"\^`~\_|\!\?\*$#<>()\[\]\{\}]/i", "", $rss_title);
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<rss version="2.0">
<channel>
<title><?=$rss_title?></title>
<link><?=$rss_link?></link>
<description><?=$rss_title?></description>
<language>ko</language>
<generator>ZINPACK</generator>
<pubDate><?=date("Y-m-d H:i:s")?></pubDate>
<?php if($iw['category'] != "all"){
		$sql2 = "select * from $iw[total_data_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and td_display=1 and state_sort = 'mcb' $sql_cg_code order by td_datetime desc limit $start_line, $end_line";
	}else{
		$sql2 = "select * from $iw[total_data_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and td_display=1 and state_sort = 'mcb' order by td_datetime desc limit $start_line, $end_line";
	}

	$result2 = sql_query($sql2);

	$i=0;
	while($row2 = @sql_fetch_array($result2)){
		$td_code = $row2["td_code"];
		$cg_code = $row2["cg_code"];

		$row = sql_fetch("select * from $iw[mcb_data_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and cg_code = '$cg_code' and md_code = '$td_code' and md_display=1");
		$md_code = $row["md_code"];
		$mb_code = $row["mb_code"];
		$cg_group_large = $row["cg_group_large"];
		$cg_group_middle = $row["cg_group_middle"];
		$cg_group_small = $row["cg_group_small"];
		$md_type = $row["md_type"];
		$md_subject = stripslashes($row["md_subject"]);
		$md_content = $row["md_content"];
		$md_youtube = $row["md_youtube"];
		$md_file_1 = $row["md_file_1"];
		$md_datetime = $row["md_datetime"];

		if($md_type == 1){
			$pattern = "/(mms|http|HTTP|https|HTTPS|ftp|FTP|telnet|TELNET)\:\/\/(.[^ \n\<\"\']+)/"; 
			$youtube_code="";
			if(strstr($md_youtube, "youtu.be")){
				$youtube = explode("youtu.be/",$md_youtube);
				if(strstr($youtube[1], "?")){
					$youtube2 = explode("?",$youtube[1]);
					$youtube_code = $youtube2[0];
				}else{
					$youtube_code = $youtube[1];
				}
			}else if(strstr($md_youtube, "youtube.com")){
				$youtube = explode("v=",$md_youtube);
				if(strstr($youtube[1], "&")){
					$youtube2 = explode("&",$youtube[1]);
					$youtube_code = $youtube2[0];
				}else{
					$youtube_code = $youtube[1];
				}
			}
		}else if($md_type == 2){
			$pattern = "!<(.*?)\>!is";
			preg_match_all("/<img[^>]*src=[\"']?([^>\"']+)[\"']?[^>]*>/i",stripslashes($md_content),$md_images);
		}
		$md_content = preg_replace($pattern, "", $md_content);
		$md_content = preg_replace("/[^A-Za-z90-9가-힣]/i"," ",str_replace("\r\n", "",$md_content));
		
		$row2 = sql_fetch(" select * from $iw[category_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and state_sort = 'mcb' and cg_code = '$cg_group_large'");
		$rss_category = $row2[cg_group_large];

		if ($cg_group_middle != ""){
			$row2 = sql_fetch(" select * from $iw[category_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and state_sort = 'mcb' and cg_code = '$cg_group_middle'");
			$rss_category .= " - ".$row2[cg_group_middle];
		}
		if ($cg_group_small != ""){
			$row2 = sql_fetch(" select * from $iw[category_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and state_sort = 'mcb' and cg_code = '$cg_group_small'");
			$rss_category .= " - ".$row2[cg_group_small];
		}

		$row2 = sql_fetch("select mb_nick from $iw[member_table] where ep_code = '$iw[store]' and mb_code = '$mb_code'");
		$mb_nick = $row2["mb_nick"];


		if($ep_domain == $iw[default_domain]){
			$rss_guid = "http://".$ep_domain."/bbs/m/mcb_data_view.php?type=mcb&ep=".$iw[store]."&gp=".$iw[group]."&item=".$md_code;
		}else{
			$rss_guid = "http://www.".$ep_domain."/bbs/m/mcb_data_view.php?type=mcb&ep=".$iw[store]."&gp=".$iw[group]."&item=".$md_code;
		}
?>
<item> 
<author><![CDATA[ <?=$mb_nick?> ]]></author>
<category><![CDATA[ <?=$rss_category?> ]]></category>
<title><![CDATA[ <?=$md_subject?> ]]></title>
<link><![CDATA[<?=$rss_guid?>]]></link>
<guid><![CDATA[<?=$rss_guid?>]]></guid>
<description><![CDATA[<?=$md_content?>]]></description>
<pubDate><?=$md_datetime?></pubDate>
<?php if($row["md_file_1"]){?>
<enclosure url="<?=$upload_path."/".$md_code."/".$md_file_1?>" length="" type="image/jpeg"/>
<?php }else if($row["md_youtube"]){?>
<enclosure url="http://img.youtube.com/vi/<?=$youtube_code?>/0.jpg" length="" type="image/jpeg"/>
<?php }else if($md_type == 2 && $md_images[1][0]){?>
<enclosure url="http://www<?=$iw[cookie_domain].htmlspecialchars($md_images[1][0]);?>" length="" type="image/jpeg"/>
<?php }?>
</item>
<?php }?>
</channel>
</rss>




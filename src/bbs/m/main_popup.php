<?
if (!defined("_INFOWAY_")) exit; // 개별 페이지 접근 불가

$pl_now = date("Y-m-d H:i:s");
$sql = "SELECT * FROM ".$iw['popup_layer_table']." WHERE ep_code = '".$iw['store']."' AND gp_code = '".$iw['group']."' AND pl_state = 1 AND pl_stime <= '".$pl_now."' AND pl_etime >= '".$pl_now."' ORDER BY pl_no DESC";
$result = sql_query($sql);

$i = 0;
while($row = @sql_fetch_array($result)){
	$pl_no = $row['pl_no'];
	$pl_width = $row["pl_width"];
	$pl_height = $row["pl_height"]+32;
	$pl_top = $row["pl_top"];
	$pl_left = $row["pl_left"];
	$pl_dayback = $row["pl_dayback"];
	$pl_dayfont = $row["pl_dayfont"];
	$pl_line = $row["pl_line"];
	$pl_content = stripslashes($row["pl_content"]);
?>
	<div class="poplay" id="divpop_<?echo $pl_no;?>" style="display:none;width:<?echo $pl_width;?>px;height:<?echo $pl_height;?>px;background:#ffffff;position:absolute;overflow:hidden;border:<?echo $pl_line;?> 1px solid;z-index:10000;top:<?echo $pl_top;?>px;left:<?echo $pl_left;?>px;">
		<table>
			<tr>
				<td><?echo $pl_content;?></td>
			</tr>
		</table>
		<div class="todayclose" style="height:32px;line-height:32px;font-size:12px;background:<?echo $pl_dayback;?>;color:<?echo $pl_dayfont;?>;text-align:center">
			<span class="popDel"  style="cursor:pointer" popname="divpop_<?echo $pl_no;?>">오늘 하루 이 창을 열지 않음</span>
			&nbsp;&nbsp;|&nbsp;&nbsp;
			<span class="popClose" style="cursor:pointer" popname="divpop_<?echo $pl_no;?>">[닫기]</span>
		</div>
	</div>

	<script>
		$(document).ready(function(){
			var pid = <?echo $pl_no;?>;
			if($.cookie('divpop_'+pid) == "done"){
				$("#divpop_"+pid).hide();
			}else{
				$("#divpop_"+pid).hide();
				$("#divpop_"+pid).show();
			}
		});
	</script>
<?
	$i ++;
}
?>
<script>
	$(document).ready(function(){
		$(".popClose").click(function(){
			var tname = $(this).attr('popname');
			$("#"+tname).hide();
		});
		$(".popDel").click(function(){
			var PDname = $(this).attr('popname');
			$.cookie(PDname,'done',{path:'/',expires:1});
			$("#"+PDname).hide();
		});
	});
</script>
<script src="<?echo $iw['include_path'];?>/js/jq_cookie.js"></script>
<?php
include_once("_common.php");
include_once("_head.php");

if(isset($_GET["sta"])){
	$get_stat = $_GET["sta"];
}else{
	$get_stat = "0";
}

$get_status = $_GET["status"];
if($get_status == "" || $get_status == "0"){
	$get_status = "0";
	$st_text = "Start";
}else{
	$st_text = "Stop";
}
 ?>
 <div style="margin:0 50px;">
 	<div id="waiting"></div>
	<h2 style="display:inline-block;">Processes : </h2> <button id="status_btn" onclick='chg_status()'><?=$st_text?></button> <center>
	<div id="container"></div>
</div>
 </body>
 <script>
  	var stat = '<?=$get_stat?>';
	if(stat=="0"){
		$("#waiting").html('<font color="red">잠시만 기다려주세요...</font>');
	}
 	var topstatus = '<?=$get_status?>';

	if(topstatus == '1'){
		setInterval(function() {
			$.ajax({
				type: "GET", 
				url: "./ajax/top_cmd.php?status=topstatus", 
				success: function(resData){
					$("#container").html(resData);
					$("#waiting").empty();
				},
				error: function(response){
					return false;
				}
			});
		}
		, 3000);
	}

	function chg_status(){
		if(topstatus=="1"){
			topstatus = '0';
			$("#status_btn").text('Start');
		}else{
			topstatus = '1';
			$("#status_btn").text('Stop');
		}

		location.href="http://www.info-way.co.kr/bbs/super/show_top_cmd.php?sta=1&type=dashboard&ep=infowayglobal&gp=all&status=" + topstatus;
	}
 </script>
</html>

<?php

class alipay_notify {
	var $gateway;
	var $security_code;  	//보안코드
	var $partner;
	var $sign_type;
	var $mysign;
	var $_input_charset ;
	var $transport;
	function alipay_notify($partner,$security_code,$sign_type = "MD5",$_input_charset = "GBK",$transport= "http") {
		$this->partner     =   $partner;
		$this->security_code = $security_code;
		$this->sign_type = $sign_type;
		$this->mysign = "";
		$this->_input_charset = $_input_charset ;
		$this->transport = $transport;
		if($this->transport == "https") {
			$this->gateway = "https://mapi.alipay.com/gateway.do?";
		
		} else $this->gateway = "https://mapi.alipay.com/gateway.do?";
		

	}
	function notify_verify() {   //notify_url에 대한 인증
		if($this->transport == "https") {
			$veryfy_url = $this->gateway. "service=notify_verify" ."&partner=" .$this->partner. "&notify_id=".$_POST["notify_id"];
		} else {
			$veryfy_url = $this->gateway. "notify_id=".$_POST["notify_id"]."&partner=" .$this->partner;
		}
		$veryfy_result = $this->get_verify($veryfy_url);
		$post = $this->para_filter($_POST);
		$sort_post = $this->arg_sort($post);
		while (list ($key, $val) = each ($sort_post)) {
			$arg.=$key."=".$val."&";
		}
		$prestr = substr($arg,0,count($arg)-2);  //마지막 앰퍼샌드 (&)를 제거
		$this->mysign = $this->sign($prestr.$this->security_code);

    //**********************************�舅㎱� 로그를 작성
	log_result("sign_log=".$_POST["sign"]."&".$this->mysign."&".$this->charset_decode(implode(",",$_GET),$this->_input_charset ));
	//********************************** 위의 로그를 작성

		if (eregi("true$",$veryfy_result) && $this->mysign == $_POST["sign"])  {
			return true;
		} else return false;
	}
	

	function get_verify($url,$time_out = "60") {
		$urlarr = parse_url($url);
		$errno = "";
		$errstr = "";
		$transports = "";
		if($urlarr["scheme"] == "https") {
			$transports = "ssl://";
			$urlarr["port"] = "443";
		} else {
			$transports = "tcp://";
			$urlarr["port"] = "80";
		}
		$fp=@fsockopen($transports . $urlarr['host'],$urlarr['port'],$errno,$errstr,$time_out);
		if(!$fp) {
			die("ERROR: $errno - $errstr<br />\n");
		} else {
			fputs($fp, "POST ".$urlarr["path"]." HTTP/1.1\r\n");
			fputs($fp, "Host: ".$urlarr["host"]."\r\n");
			fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
			fputs($fp, "Content-length: ".strlen($urlarr["query"])."\r\n");
			fputs($fp, "Connection: close\r\n\r\n");
			fputs($fp, $urlarr["query"] . "\r\n\r\n");
			while(!feof($fp)) {
				$info[]=@fgets($fp, 1024);
			}

			fclose($fp);
			$info = implode(",",$info);
			while (list ($key, $val) = each ($_POST)) {
				$arg.=$key."=".$val."&";
			}

//**********************************위의 로그를 작성

			log_result("log=".$url.$this->charset_decode($info,$this->_input_charset));
			log_result("log=".$this->charset_decode($arg,$this->_input_charset));
			return $info;
//**********************************위의 로그를 작성
		}

	}

	function arg_sort($array) {
		ksort($array);
		reset($array);
		return $array;

	}

	function sign($prestr) {
		$sign='';
		if($this->sign_type == 'MD5') {
			$sign = md5($prestr);
		}elseif($this->sign_type =='DSA') {
			//DSA 서명 방법은 이후 개발 될
			die("DSA 서명 방법은 후속 개발로, MD5 서명 방식을 사용하시기 바랍니다");
		}else {
			die("페이팔은 지원하지 않습니다".$this->sign_type."서명 방식의 종류");
		}
		return $sign;

	}
	function para_filter($parameter) { //배열 널 및 서명 패턴을 제거
		$para = array();
		while (list ($key, $val) = each ($parameter)) {
			if($key == "sign" || $key == "sign_type" || $val == "")continue;
			else	$para[$key] = $parameter[$key];

		}
		return $para;
	}

	//문자 인코딩 다양한 달성
	function charset_encode($input,$_output_charset ,$_input_charset ="GBK" ) {
		$output = "";
		if(!isset($_output_charset) )$_output_charset  = $this->parameter['_input_charset '];
		if($_input_charset == $_output_charset || $input ==null ) {
			$output = $input;
		} elseif (function_exists("mb_convert_encoding")){
			$output = mb_convert_encoding($input,$_output_charset,$_input_charset);
		} elseif(function_exists("iconv")) {
			$output = iconv($_input_charset,$_output_charset,$input);
		} else die("sorry, you have no libs support for charset change.");
		return $output;
	}

	//문자 복호화 다양한 달성
	function charset_decode($input,$_input_charset ,$_output_charset="GBK"  ) {
		$output = "";
		if(!isset($_input_charset) )$_input_charset  = $this->_input_charset ;
		if($_input_charset == $_output_charset || $input ==null ) {
			$output = $input;
		} elseif (function_exists("mb_convert_encoding")){
			$output = mb_convert_encoding($input,$_output_charset,$_input_charset);
		} elseif(function_exists("iconv")) {
			$output = iconv($_input_charset,$_output_charset,$input);
		} else die("sorry, you have no libs support for charset changes.");
		return $output;
	}
}

?>

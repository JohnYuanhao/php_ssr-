<?php

error_reporting(E_ALL & ~E_NOTICE);
//错误信息等级降级
//$url = "http://localhost/iShadow.htm";
$url = "http://ss.ishadowx.com/";
//$url=$_GET["url"];

//编码
function urlsafe_b64encode($string) {
	$data = base64_encode($string);
	$data = str_replace(array('+', '/'), array('-', '_'), $data);
	return rtrim($data, "=");
}

//解码
function urlsafe_b64decode($string) {
	$data = str_replace(array('-', '_'), array('+', '/'), $string);
	$mod4 = strlen($data) % 4;
	if ($mod4) {
		$data .= substr('====', $mod4);
	}
	return base64_decode($data);
	//return $data;
}

function array2object($array) {
	if (is_array($array)) {
		$obj = new StdClass();
		foreach ($array as $key => $val) {
			$obj -> $key = $val;
		}
	} else { $obj = $array;
	}
	return $obj;
}

function object2array($object) {
	if (is_object($object)) {
		foreach ($object as $key => $value) {
			$array[$key] = $value;
		}
	} else {
		$array = $object;
	}
	return $array;
}

//include("main.php");
require_once "php_thief.php";

//加密aes-256-cfb  协议origin 混淆plain
class shadows {
	var $server;
	//服务器地址
	var $server_port;
	//服务器端口
	var $password;
	//密码
	var $protocol;
	//协议
	var $method;
	//加密
	var $obfs;
	//混淆
	var $remarks;
	//备注
	var $group;
	//组名
}

// print_r($arr);
array_unshift($arr,array('server'=>"127.0.0.1",'server_port'=>"1080",'password' => "0" ,'method' => "none",'remarks' =>"本组服务器来源-ss.ishadowx.com")); 
// $arr[] = array('server'=>"127.0.0.1",'server_port'=>"1080",'password' => "0" ,'method' => "none",'remarks' =>"本组服务器来源-ss.ishadowx.com");

$myfile = fopen("ishadow.txt", "w") or die("Unable to open file!");
fwrite($myfile,"MAX=99\n");
foreach ($arr as $i => $val) {
	$string = $arr[$i][server] . ":" . $arr[$i][server_port] . ":origin:" . $arr[$i][method] . ":plain:" . urlsafe_b64encode($arr[$i][password]);
	$string = $string . "/?obfsparam=&remarks=" . urlsafe_b64encode($arr[$i][remarks]) . "&group=" . urlsafe_b64encode("@Shadow_影:1");
	fwrite($myfile, "ssr://" . urlsafe_b64encode($string) . "\n");
}
fclose($myfile);

$file_path = "ishadow.txt";
if(file_exists($file_path)){
$fp = fopen($file_path,"r");
$str = fread($fp,filesize($file_path));//指定读取大小，这里把整个文件内容读取出来
echo $str;
// echo urlsafe_b64encode("@Shadow_影:2");
}
?>
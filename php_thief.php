<?php

error_reporting(E_ALL & ~E_NOTICE);//错误信息等级降级

function fetch_urlpage_contents($url) {
	$c = file_get_contents($url);
	return $c;
}

//获取匹配内容
function fetch_match_contents($begin, $end, $c) {
	$begin = change_match_string($begin);
	$end = change_match_string($end);
	$p = "#{$begin}(.*){$end}#iU";
	//i表示忽略大小写，U禁止贪婪匹配
	if (preg_match_all($p, $c, $rs)) {
		return $rs;
	} else {
		return "";
	}
}//转义正则表达式字符串

function change_match_string($str) {
	//注意，以下只是简单转义
	$old = array("/", "$", '?');
	$new = array("/", "$", '?');
	$str = str_replace($old, $new, $str);
	return $str;
}

//采集网页
function pick($url, $ft, $th) {
	$c = fetch_urlpage_contents($url);
	foreach ($ft as $key => $value) {
		$rs[$key] = fetch_match_contents($value["begin"], $value["end"], $c);
		if (is_array($th[$key])) {
			foreach ($th[$key] as $old => $new) {
				$rs[$key] = str_replace($old, $new, $rs[$key]);
			}
		}
	}
	return $rs;
}

//删除指定内容
function delByValue($arr, $value){
    if(!is_array($arr)){
        return $arr;
    }
	$k=array_search("$value",$arr);//搜索数组中指定的元素并返回位置
	while ($k>-1) {
		array_splice($arr,$k,1);//移除数组指定位置的元素
		$k=array_search("$value",$arr);
	}
	//删除查看图片链接
//	foreach($arr as $k=>$v){
//		if(($k+1)%5==0){
//			unset($arr[$k]);
//		}
//	}
	$arr=array_merge($arr);//重新排序
    return $arr;
}

//改数组为键值对象
function changeArray($arr){
	$arr=array_chunk($arr,5,FALSE);
	foreach ($arr as $key => $val) {
		//$arr[$key][0]=str_replace("IP Address:", "", $arr[$key][0]);
		$arr[$key][0]=str_replace(['">','</span>'],"",fetch_match_contents('">','</span>',$arr[$key][0])[0][0]);
		$arr[$key][1]=str_replace("Port：", "", $arr[$key][1]);
		//$arr[$key][2]=str_replace("Password:", "", $arr[$key][2]);
		$arr[$key][2]=str_replace(['">','</span>'],"",fetch_match_contents('">','</span>',$arr[$key][2])[0][0]);
		if(trim($arr[$key][2])==null){
			unset($arr[$key]);
			continue;
		}
		$arr[$key][3]=str_replace("Method:", "", $arr[$key][3]);
		$arr[$key][4]=str_replace('"','',str_replace('title="', "",fetch_match_contents('title="','"',$arr[$key][4])[0][0]));
		$a=array("server","server_port","password","method","remarks");
		$c=array_combine($a,$arr[$key]);
		$arr[$key]=$c;
	}
	$arr=array_merge($arr);
	return $arr;
}

function delByIp($arr){
	$num=[];
	foreach($arr as $k => $v){
		$flag=true;
		foreach ($num as $key => $value) {
			if($v[server]==$value[server]){
				$flag=false;
			}
		}
		if($flag){
			$num[]=$v;
			// $flag=true;
		}
	}
	return $num;
}

//要采集的地址
$ft["a"]["begin"] = '<h4>';
//截取的开始点<br />
$ft["a"]["end"] = '</h4>';
//截取的结束点

$rs = pick($url, $ft, $th);
//开始采集
$arr=$rs["a"][1];
$arr=delByValue($arr,'auth_sha1_v4 tls1.2_ticket_auth');//去除无用代码
$arr=changeArray($arr);//转换数组为二维
$arr=delByIp($arr);
//print_r($arr);
?>
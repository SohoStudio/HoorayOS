<?php
	//防sql注入
	function sqlInjection($string,$force=0){
		if(!$GLOBALS['magic_quotes_gpc'] || $force){
			if(is_array($string)){
				foreach($string as $key => $val){
					$string[$key] = sqlInjection($val, $force);
				}
			}else{
				$string = addslashes($string);
			}
			$string = str_replace('\'', "''",$string);
		}
		return $string;
	}
	//字符串累加
	function addstr($str,$add,$sign){$str=($str=='')?$add:($str.$sign.$add);return $str;}
	//替换单引号和双引号
	function toQuote($str){$str=str_replace("'",'&#39;',$str);$str=str_replace('"','&#34;',$str);return trim($str);}
	function deQuote($str){$str=str_replace('&#39;',"'",$str);$str=str_replace('&#34;','"',$str);return trim($str);}
	//常规字符串条件替换
	function replaceStr($mode,$str,$from,$to){
		switch($mode){
			case "":
				$return = strtr($str,array($from => $to));
			break;
			case "empty":
				$return = (empty($str)||$str=='') ? $from : $str;
			break;
		}
		return $return;
	}
	//文件地址处理
	function getFileInfo($str,$mode){
		if($str==""||is_null($str)) return "";
		switch($mode){
			case "path" : return dirname($str); break;
			case "name" : return basename($str,'.'.end(explode(".",$str))); break;
			case "ext" : return end(explode(".",$str)); break;
			case "simg" : return getFileInfo($str,"path")."/s_".getFileInfo($str,"name").".jpg"; break;
		}
	}
	//字符截断，支持中英文不乱码
	function cutstr($str,$len=0,$dot='...',$encoding='utf-8'){if(!is_numeric($len)){$len=intval($len);}if(!$len || strlen($str)<= $len){return $str;}$tempstr='';$str=str_replace(array('&', '"', '<', '>'),array('&', '"', '<', '>'),$str);if($encoding=='utf-8'){$n=$tn=$noc=0;while($n < strlen($str)){$t = ord($str[$n]);if($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {$tn = 1; $n++; $noc++;} elseif (194 <= $t && $t <= 223) {$tn = 2; $n += 2; $noc += 2;} elseif (224 <= $t && $t < 239) {$tn = 3; $n += 3; $noc += 2;} elseif (240 <= $t && $t <= 247) {$tn = 4; $n += 4; $noc += 2;} elseif (248 <= $t && $t <= 251) {   $tn = 5; $n += 5; $noc += 2;} elseif ($t == 252 || $t == 253) {$tn = 6; $n += 6; $noc += 2;} else {$n++;}if($noc >= $len){break;}}if($noc > $len) {$n -= $tn;}$tempstr = substr($str, 0, $n);} elseif ($encoding == 'gbk') {for ($i=0; $i<$len; $i++) {$tempstr .= ord($str{$i}) > 127 ? $str{$i}.$str{++$i} : $str{$i};}}$tempstr = str_replace(array('&', '"', '<', '>'), array('&', '"', '<', '>'), $tempstr);return $tempstr.$dot;}
	//字符截断，支持html补全
	function cuthtml($str,$length=0,$suffixStr="...",$clearhtml=true,$charset="utf-8",$start=0,$tags="P|DIV|H1|H2|H3|H4|H5|H6|ADDRESS|PRE|TABLE|TR|TD|TH|INPUT|SELECT|TEXTAREA|OBJECT|A|UL|OL|LI|BASE|META|LINK|HR|BR|PARAM|IMG|AREA|INPUT|SPAN",$zhfw=0.9){
		if($clearhtml||$clearhtml==1){return cutstr(strip_tags($str),$length,$suffixStr,$charset);}
		$re['utf-8']     = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
		$re['gb2312']    = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
		$re['gbk']       = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
		$re['big5']      = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
		$zhre['utf-8']   = "/[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
		$zhre['gb2312']  = "/[\xb0-\xf7][\xa0-\xfe]/";
		$zhre['gbk']     = "/[\x81-\xfe][\x40-\xfe]/";
		$zhre['big5']    = "/[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
		$tpos = array();
		preg_match_all("/<(".$tags.")([\s\S]*?)>|<\/(".$tags.")>/ism", $str, $match);
		$mpos = 0;
		for($j = 0; $j < count($match[0]); $j ++){
			$mpos = strpos($str, $match[0][$j], $mpos);
			$tpos[$mpos] = $match[0][$j];
			$mpos += strlen($match[0][$j]);
		}
		ksort($tpos);
		$sarr = array();
		$bpos = 0;
		$epos = 0;
		foreach($tpos as $k => $v){
			$temp = substr($str, $bpos, $k - $epos);
			if(!empty($temp))array_push($sarr, $temp);
			array_push($sarr, $v);
			$bpos = ($k + strlen($v));
			$epos = $k + strlen($v);
		}
		$temp = substr($str, $bpos);
		if(!empty($temp))array_push($sarr, $temp);
		$bpos = $start;
		$epos = $length;
		for($i = 0; $i < count($sarr); $i ++){
			if(preg_match("/^<([\s\S]*?)>$/i", $sarr[$i]))continue;
			preg_match_all($re[$charset], $sarr[$i], $match);
			for($j = $bpos; $j < min($epos, count($match[0])); $j ++){
				if(preg_match($zhre[$charset], $match[0][$j]))$epos -= $zhfw;
			}
			$sarr[$i] = "";
			for($j = $bpos; $j < min($epos, count($match[0])); $j ++){
				$sarr[$i] .= $match[0][$j];
			}
			$bpos -= count($match[0]);
			$bpos = max(0, $bpos);
			$epos -= count($match[0]);
			$epos = round($epos);
		}
		$slice = join("", $sarr);
		if($slice != $str)return $slice.$suffixStr;
		return $slice;
	}
	//根据tinyint字段判断显示内容
	function showTinyintMsg($val,$str1,$str2){
		if($val==1){$out=$str1;}else{$out=$str2;}
		return $out;
	}
	//获取内网IP
	function getIp(){
		$ip=false;
		if(!empty($_SERVER["HTTP_CLIENT_IP"])){
			$ip = $_SERVER["HTTP_CLIENT_IP"];
		}
		if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ips = explode (", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
			if ($ip) { array_unshift($ips, $ip); $ip = FALSE; }
			for ($i = 0; $i < count($ips); $i++) {
				if (!eregi ("^(10|172\.16|192\.168)\.", $ips[$i])) {
				$ip = $ips[$i];
				break;
				}
			}
		}
		return ($ip ? $ip : $_SERVER['REMOTE_ADDR']);
	}
	//生成随机字符串
	function getRandStr($len = 4){
		$chars = array("a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z","A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z","0","1","2","3","4","5","6","7","8","9");
		$charsLen = count($chars) - 1;
		shuffle($chars);
		$output = "";
		for($i=0; $i<$len; $i++){
			$output .= $chars[mt_rand(0, $charsLen)];
		}
		return $output;
	}
	//获取指定日期所在月的第一天和最后一天
	function getTheMonth($date){
		$firstday = date("Y-m-01",strtotime($date));
		$lastday = date("Y-m-d",strtotime("$firstday +1 month -1 day"));
		return array($firstday,$lastday);
	}
	//获取指定日期上个月的第一天和最后一天
	function getPurMonth($date){
		$time=strtotime($date);
		$firstday=date('Y-m-01',strtotime(date('Y',$time).'-'.(date('m',$time)-1).'-01'));
		$lastday=date('Y-m-d',strtotime("$firstday +1 month -1 day"));
		return array($firstday,$lastday);
	}
	//字符串编码任意转换
	function charsetConvert($source,$source_lang,$target_lang='utf-8'){
		if($source_lang != ''){
			$source_lang = str_replace(array('gbk','utf8','big-5'),array('gb2312','utf-8','big5'),strtolower($source_lang));
		}
		if($target_lang != ''){
			$target_lang = str_replace(array('gbk','utf8','big-5'),array('gb2312','utf-8','big5'),strtolower($target_lang));
		}
		if($source_lang == $target_lang||$source == ''){
			return $source;
		}
		$index = $source_lang."_".$target_lang;
		//繁简互换并不是交换字符集编码
		if(USEEXISTS&&!in_array($index,array('gb2312_big5','big5_gb2312'))){
			if(function_exists('iconv')){
				return iconv($source_lang,$target_lang,$source);
			}
			if(function_exists('mb_convert_encoding')){
				return mb_convert_encoding($source,$target_lang,$source_lang);
			}
		}
		$table = self::loadtable($index);
		if(!$table){
			return $source;
		}
		self::$string = $source;
		self::$source_lang = $source_lang;
		self::$target_lang = $target_lang;
		if($source_lang=='gb2312'||$source_lang=='big5'){
			if($target_lang=='utf-8'){
				self::$table = $table;
				return self::CHS2UTF8();
			}
			if($target_lang=='gb2312'){
				self::$table = array_flip($table);
			}else{
				self::$table = $table;
			}
			return self::BIG2GB();
		}elseif(self::$source_lang=='utf-8'){
			self::$table = array_flip($table);
			return self::UTF82CHS();
		}
		return NULL;
	}
	function loadtable($index){
		static $table = array();
		$tabIndex = '';
		switch ($index) {
			case 'gb2312_utf-8':
			case 'utf-8_gb2312':
			case 'gb2312escape':
			case 'unescapetogb2312':
				$tabIndex = 'gbkutf';
				break;
			case 'big5_utf-8':
			case 'utf-8_big5':
			case 'big5escape':
			case 'unescapetobig5':
				$tabIndex = 'big5utf';
				break;
			case 'gb2312_big5':
			case 'big5_gb2312':
				$tabIndex = 'gbkbig5';
				break;
			default:return NULL;
		}
		if(!isset($table[$tabIndex])){
			$table[$tabIndex] = @include(TABLE_DIR."/".$tabIndex.".php");
		}
		return $table[$tabIndex];
	}
	//字符转日期格式函数
	function strToDt($date_time_string){
		if($date_time_string == ""){
			$date_time_string = "NULL";
		}else{
			$date_time_string = $date_time_string;
		}
		$dt_elements = explode(" " ,$date_time_string); 
		$date_elements = explode("/" ,$dt_elements[0]); 
		$time_elements = explode(":" ,$dt_elements[1]); 
		if ($dt_elements [2]== "PM") { $time_elements[0]+=12;} 
		return date("Y-m-d h:i:s",mktime($time_elements [0], $time_elements[1], $time_elements[2], $date_elements[1], $date_elements[2], $date_elements[0])); 
	}
	//日期对比
	function dtDiff($interval,$date1,$date2){   
		$timedifference=formatTm($date1)-formatTm($date2);
		switch($interval){
			case "y":$retval=bcdiv($timedifference,86400*360);break;   
			case "m":$retval=bcdiv($timedifference,86400*30);break; 
			case "w":$retval=bcdiv($timedifference,604800);break;   
			case "d":$retval=bcdiv($timedifference,86400);break;   
			case "h":$retval=bcdiv($timedifference,3600);break;   
			case "n":$retval=bcdiv($timedifference,60);break;   
			case "s":$retval=$timedifference;break;   
		} 
		//$retval=($retval<=0) ? $retval=1 : $retval+1 ;  
		return $retval;   
	}
	function formatTm($timestamp = ''){    
		list($date,$time)=explode(" ",$timestamp); 
		list($year,$month,$day)=explode("-",$date); 
		list($hour,$minute,$seconds )=explode(":",$time); 
		$timestamp=gmmktime($hour,$minute,$seconds,$month,$day,$year); 
		return $timestamp; 
	}
	//日期加减
	function dtAdd($interval,$number,$date){
		//$date_time_string=strftime("%Y/%m/%d %H:%M:%S",$date);
		//$date_time_string=date("%Y/%m/%d %H:%M:%S",$date); 
		$date_time_string = $date;
		$dt_elements = explode(" " ,$date_time_string); 
		$date_elements = explode("-" ,$dt_elements[0]); 
		$time_elements = explode(":" ,$dt_elements[1]); 
		if ($dt_elements [2]== "PM") { $time_elements[0]+=12;} 
		$hours = $time_elements [0]; 
		$minutes = $time_elements [1]; 
		$seconds = $time_elements [2]; 
		$month = $date_elements[1]; 
		$day = $date_elements[2]; 
		$year = $date_elements[0]; 
		switch ($interval) { 
			case "yyyy": $year +=$number; break; 
			case "q": $month +=($number*3); break; 
			case "m": $month +=$number; break; 
			case "y": 
			case "d": 
			case "w": $day+=$number; break; 
			case "ww": $day+=($number*7); break; 
			case "h": $hours+=$number; break; 
			case "n": $minutes+=$number; break; 
			case "s": $seconds+=$number; break; 
		}
		$timestamp = mktime($hours,$minutes,$seconds,$month,$day,$year); 
		$timestamp = strftime("%Y-%m-%d %H:%M:%S",$timestamp); 
		return $timestamp; 
	}
	//连续创建带层级的文件夹
	function recursive_mkdir($folder){
		$folder = preg_split( "/[\\\\\/]/" , $folder );
		$mkfolder = '';
		for($i=0; isset($folder[$i]); $i++){
			if(!strlen(trim($folder[$i]))){
				continue;
			}
			$mkfolder .= $folder[$i];
			if(!is_dir($mkfolder)){
				mkdir("$mkfolder",0777);
			}
			$mkfolder .= DIRECTORY_SEPARATOR;
		}
	}
	
	/*****以下方法仅限该项目*****/
	
	//获取图片缩略图地址
	function getSimgSrc($string){
		return preg_replace("#(\w*\..*)$#U", "s_\${1}", $string);
	}
	
	//获取我的应用id数组
	function getMyAppListOnlyId(){
		global $db;
		$rs = $db->select(0, 1, 'tb_member', 'dock,desk1,desk2,desk3,desk4,desk5', 'and tbid='.$_SESSION['member']['id']);
		if($rs['dock'] != ''){
			$dock = explode(',', $rs['dock']);
			foreach($dock as $v){
				$tmp = explode('_', $v);
				if($tmp[0] == 'app' || $tmp[0] == 'widget'){
					$appid[] = $tmp[1];
				}
			}
		}
		for($i=1; $i<=5; $i++){
			if($rs['desk'.$i] != ''){
				$deskappid = explode(',', $rs['desk'.$i]);
				foreach($deskappid as $v){
					$tmp = explode('_', $v);
					if($tmp[0] == 'app' || $tmp[0] == 'widget'){
						$appid[] = $tmp[1];
					}
				}
			}
		}
		$rs = $db->select(0, 0, 'tb_folder', 'content', 'and content!="" and member_id='.$_SESSION['member']['id']);
		if($rs != NULL){
			foreach($rs as $v){
				$rss = explode(',', $v['content']);
				foreach($rss as $vv){
					$tmp = explode('_', $vv);
					if($tmp[0] == 'app' || $tmp[0] == 'widget'){
						$appid[] = $tmp[1];
					}
				}
			}
		}
		if($appid != NULL){
			return $appid;
		}else{
			return NULL;
		}
	}
	//获取我的应用id数组
	function getMyAppList(){
		global $db;
		$rs = $db->select(0, 1, 'tb_member', 'dock,desk1,desk2,desk3,desk4,desk5', 'and tbid='.$_SESSION['member']['id']);
		if($rs['dock'] != ''){
			$dock = explode(',', $rs['dock']);
			foreach($dock as $v){
				$appid[] = $v;
			}
		}
		for($i=1; $i<=5; $i++){
			if($rs['desk'.$i] != ''){
				$deskappid = explode(',', $rs['desk'.$i]);
				foreach($deskappid as $v){
					$appid[] = $v;
				}
			}
		}
		$rs = $db->select(0, 0, 'tb_folder', 'content', 'and content!="" and member_id='.$_SESSION['member']['id']);
		if($rs != NULL){
			foreach($rs as $v){
				$rss = explode(',', $v['content']);
				foreach($rss as $vv){
					$appid[] = $vv;
				}
			}
		}
		if($appid != NULL){
			return $appid;
		}else{
			return NULL;
		}
	}
	//验证是否已安装该应用
	function checkAppIsMine($id){
		$flag = false;
		$myapplist = getMyAppList();
		if(in_array($id, $myapplist)){
			$flag = true;
		}
		return $flag;
	}
	//强制格式化appid，如：'10,13,,17,4,6,'，格式化后：'10,13,17,4,6'
	function formatAppidArray($arr){
		foreach($arr as $k => $v){
			if($v==''){
				unset($arr[$k]);
			}
		}
		return $arr;
	}
	//验证是否登入
	function checkLogin(){
		return $_SESSION['member'] != NULL ? true : false;
	}
	//验证是否为管理员
	function checkAdmin(){
		global $db;
		$user = $db->select(0, 1, 'tb_member', 'type', 'and tbid='.$_SESSION['member']['id']);
		return $user['type'] == 1 ? true : false;
	}
	//验证是否有权限
	function checkPermissions($app_id){
		global $db;
		$isHavePermissions = false;
		$user = $db->select(0, 1, 'tb_member', 'permission_id', 'and tbid='.$_SESSION['member']['id']);
		if($user['permission_id'] != ''){
			$permission = $db->select(0, 1, 'tb_permission', 'apps_id', 'and tbid='.$user['permission_id']);
			if($permission['apps_id'] != ''){
				$apps = explode(',', $permission['apps_id']);
				if(in_array($app_id, $apps)){
					$isHavePermissions = true;
				}
			}
		}
		return $isHavePermissions;
	}
?>
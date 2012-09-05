<?php
error_reporting( E_ERROR | E_WARNING );

ob_start();
session_start();
header("Content-type: text/html; charset=utf-8");

//新浪应用APIKEY
define("SINA_APIKEY","319005669");
//文件上传大小限制，单位MB
$uploadFileMaxSize = 20;
//允许文件上传类型
$uploadFileType = array(
	array('ext'=>'rar', 'icon'=>'img/ui/file_rar.png'),
	array('ext'=>'zip', 'icon'=>'img/ui/file_zip.png'),
	array('ext'=>'7z', 'icon'=>'img/ui/file_7z.png'),
	array('ext'=>'jpeg', 'icon'=>'img/ui/file_jpeg.png'),
	array('ext'=>'jpg', 'icon'=>'img/ui/file_jpeg.png'),
	array('ext'=>'gif', 'icon'=>'img/ui/file_gif.png'),
	array('ext'=>'bmp', 'icon'=>'img/ui/file_bmp.png'),
	array('ext'=>'png', 'icon'=>'img/ui/file_png.png'),
	array('ext'=>'ico', 'icon'=>'img/ui/file_ico.png'),
	array('ext'=>'doc', 'icon'=>'img/ui/file_word.png'),
	array('ext'=>'docx', 'icon'=>'img/ui/file_word.png'),
	array('ext'=>'xls', 'icon'=>'img/ui/file_excel.png'),
	array('ext'=>'xlsx', 'icon'=>'img/ui/file_excel.png'),
	array('ext'=>'ppt', 'icon'=>'img/ui/file_ppt.png'),
	array('ext'=>'pptx', 'icon'=>'img/ui/file_ppt.png'),
	array('ext'=>'pdf', 'icon'=>'img/ui/file_pdf.png'),
	array('ext'=>'wma', 'icon'=>'img/ui/file_wma.png'),
	array('ext'=>'mp3', 'icon'=>'img/ui/file_mp3.png'),
	array('ext'=>'txt', 'icon'=>'img/ui/file_txt.png'),
	array('ext'=>'js', 'icon'=>'img/ui/file_txt.png'),
	array('ext'=>'css', 'icon'=>'img/ui/file_txt.png'),
	array('ext'=>'html', 'icon'=>'img/ui/file_txt.png')
);
//禁止文件上传类型
$uploadFileUnType = array('exe', 'cmd');
//应用分类
$apptype = array(
	array('id'=>1,'name'=>'系统'),
	array('id'=>2,'name'=>'游戏'),
	array('id'=>3,'name'=>'影音'),
	array('id'=>4,'name'=>'图书'),
	array('id'=>5,'name'=>'生活'),
	array('id'=>6,'name'=>'工具')
);
//错误代码
$errorcode = array(
	'noLogin'=>'1000',
	'noAdmin'=>'1001',
	'noPermissions'=>'1002'
);

trim(@extract($_COOKIE));
trim(@extract($_POST));
trim(@extract($_GET));

require_once('inc/db.config.php');
require_once('inc/db.class.php');
require_once('inc/function.inc.php');

$db = new HRDB($db_hoorayos_config);
?>
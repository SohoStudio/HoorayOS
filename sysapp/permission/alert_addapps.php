<?php
	require_once('../../global.php');
	require_once('inc/setting.inc.php');
	require_once('inc/smarty.php');
	
	$apps = $db->select(0, 0, 'tb_app', 'tbid,name,icon', 'and kindid = 1');
	$smarty->assign('apps', $apps);
	$smarty->display('sysapp/permission/alert_addapps.tpl');
?>
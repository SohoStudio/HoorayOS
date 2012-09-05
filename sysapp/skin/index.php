<?php
	require_once('../../global.php');
	require_once('inc/setting.inc.php');
	require_once('inc/smarty.php');
	
	switch($ac){
		case 'update':
			$db->update(0, 0, 'tb_member', "skin = '$skin'", 'and tbid = '.$_SESSION['member']['id']);
			break;
		default:
			$smarty->display('sysapp/skin/index.tpl');
	}
?>
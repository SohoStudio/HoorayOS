<?php
	require_once('../../global.php');
	require_once('inc/setting.inc.php');
	require_once('inc/smarty.php');
	
	switch($ac){
		default:
			$smarty->display('extapp/clock/index.tpl');
	}
?>
<?php
	require_once('../../global.php');
	require_once('inc/setting.inc.php');
	require_once('inc/smarty.php');
	
	switch($ac){
		default:
			$rs = $db->select(0, 0, 'tb_wallpaper', '*', '', 'tbid asc');
			foreach($rs as &$v){
				$v['s_url'] = getFileInfo($v['url'], 'simg');
			}
			$smarty->assign('wallpaperList', $rs);
			$rs = $db->select(0, 1, 'tb_member', 'wallpapertype', 'and tbid='.$_SESSION['member']['id']);
			$smarty->assign('wallpaperType', $rs['wallpapertype']);
			$smarty->display('sysapp/wallpaper/index.tpl');
	}
?>
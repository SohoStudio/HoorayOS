<?php
	require_once('../../global.php');
	require_once('inc/setting.inc.php');
	require_once('inc/smarty.php');
	
	$smarty->assign('errorcode', $errorcode);
	//验证是否登入
	if(!checkLogin()){
		$smarty->assign('code', $errorcode['noLogin']);
		$smarty->display('error.tpl');
		exit;
	}
	//验证是否为管理员
	else if(!checkAdmin()){
		$smarty->assign('code', $errorcode['noAdmin']);
		$smarty->display('error.tpl');
		exit;
	}
	//验证是否有权限
	else if(!checkPermissions(4)){
		$smarty->assign('code', $errorcode['noPermissions']);
		$smarty->display('error.tpl');
		exit;
	}
	
	switch($ac){
		case 'ajaxEdit':
			if($tbid == ''){
				$set = array(
					"name = '$val_name'",
					"apps_id = '$val_apps_id'"
				);
				$db_insert(0, 0, 'tb_permission', $set);
			}else{
				$db->update(0, 0, 'tb_permission', "apps_id='$val_apps_id'", "and tbid = $id" );
			}
			break;
		case 'updateApps':
			$appsrs = $db->select(0, 0, 'tb_app', 'tbid,name,icon', 'and tbid in ('.$appsid.')');
			foreach($appsrs as $a){
				echo '<div class="app" appid="'.$a['tbid'].'"><img src="../../'.$a['icon'].'" alt="'.$a['name'].'" title="'.$a['icon'].'"><span class="del">删</span></div>';
			}
			break;
		default:
			if($permissionid != NULL){
				$rs = $db->select(0, 1, 'tb_permission', '*', 'and tbid = '.$permissionid);
				if($rs['apps_id'] != ''){
					$appsrs = $db->select(0, 0, 'tb_app', 'tbid,name,icon', 'and tbid in ('.$rs['apps_id'].')');
					$rs['appsinfo'] = $appsrs;
				}
				$smarty->assign('permission', $rs);
			}
			$smarty->display('sysapp/permission/detail.tpl');
	}
?>
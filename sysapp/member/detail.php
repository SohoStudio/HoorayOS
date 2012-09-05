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
	else if(!checkPermissions(3)){
		$smarty->assign('code', $errorcode['noPermissions']);
		$smarty->display('error.tpl');
		exit;
	}
	
	switch($ac){
		case 'ajaxEdit':
			$val_password = $val_password == '' ? $val_password : sha1($val_password);			
			if($value_1 == ''){
				$set = array(
					"username = '$val_username'",
					"password = '$val_password'",
					"type = $val_type"
				);
				if($value_4 == 1){
					$set[] = "permission_id = '$val_permission_id'";
				}
				$db->insert(0, 0, 'tb_member', $set);
			}else{
				$set = array("type = $val_type");
				if($password != ''){
					$set[] = "password = '$val_password'";
				}
				if($value_4 == 1){
					$set[] = "permission_id = '$val_permission_id'";
				}else{
					$set[] = "permission_id = ''";
				}
				$db->update(0, 0, 'tb_member', $set, "and tbid = $id");
			}
			break;
		default:
			if($memberid != NULL){
				$rs = $db->select(0, 1, 'tb_member', '*', 'and tbid = '.$memberid);
				$smarty->assign('member', $rs);
			}
			$rs = $db->select(0, 0, 'tb_permission', 'tbid,name');
			$smarty->assign('permission', $rs);
			$smarty->display('sysapp/member/detail.tpl');
	}
?>
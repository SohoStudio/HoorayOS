<?php
	require_once('global.php');
	require_once('inc/setting.inc.php');
	require_once('inc/smarty.php');
	
	switch($ac){
		//登入
		case 'login':
			$sqlwhere = array(
				"username = '$value_1'",
				"password = '".sha1($value_2)."'"
			);
			$row = $db->select(0, 1, 'tb_member', '*', $sqlwhere);
			if($row != NULL){
				$_SESSION['member']['id'] = $row['tbid'];
				$_SESSION['member']['name'] = $row['username'];
				$db->update(0, 0, 'tb_member', 'lastlogindt = now(), lastloginip = "'.getIp().'"', 'and tbid = '.$row['tbid']);
				echo '1';
			}
			break;
		//验证是否登入
		case 'checkLogin':
			if(checkLogin()){
				echo 1;
			}
			break;
		//注册
		case 'reg':
			$isreg = $db->select(0, 1, 'tb_member', 'tbid', 'and username = "'.$value_1.'"');
			if($isreg != NULL){
				echo false;
			}else{
				$set = array(
					'username = "'.trim($value_1).'"',
					'password = "'.sha1(trim($value_2)).'"',
					'regdt = now()'
				);
				$db->insert(0, 0, 'tb_member', $set);
				echo true;
			}
			break;
		default:
			if(checkLogin()){
				header('Location:index.php');
			}else{
				$setting = $db->select(0, 1, 'tb_setting');
				$smarty->assign('setting', $setting);
				$smarty->display('login.tpl');
			}
	}
?>
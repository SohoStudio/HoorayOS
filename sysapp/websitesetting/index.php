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
	else if(!checkPermissions(2)){
		$smarty->assign('code', $errorcode['noPermissions']);
		$smarty->display('error.tpl');
		exit;
	}
	
	switch($ac){
		case 'ajaxEdit':
			$set = array(
				"title = '$val_title'",
				"keywords = '$val_keywords'",
				"description = '$val_description'"
			);
			$db->update(0, 0, 'tb_setting', $set);
			break;
		case 'getDonateList':
			//获取url地址
			$url = 'http://files.cnblogs.com/hooray/donate.xml';
			//取出远程url的xml文件
			$html = file_get_contents($url);
			if($html == ""){
				echo 0;
			}else{
				//将文件装到一个数组当中
				$arr = simplexml_load_string($html);
				//将属性循环出来
				foreach($arr as $value){
					echo '<div class="input-label"><label class="label-text">'.$value['name'].'：</label><span class="txt">'.$value['money'].' 元</span></div>';
				}
			}
			break;
		case 'checkVersion':
			//获取url地址
			$url = 'http://files.cnblogs.com/hooray/version.xml';
			//取出远程url的xml文件
			$html = file_get_contents($url);
			if($html == ""){
				echo 0;
			}else{
				//将文件装到一个数组当中
				$arr = simplexml_load_string($html);
				foreach($arr as $value){
					if($value['version'] == $version){
						echo 1;
					}else{
						echo $value['download'];
					}
					break;
				}
			}
			break;
		default:
			$rs = $db->select(0, 1, 'tb_setting');
			$smarty->assign('setinfo', $rs);
			$smarty->display('sysapp/websitesetting/index.tpl');
	}
?>
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
	
	$ac = $_REQUEST['ac'];
	switch($ac){
		case 'ajaxGetList':
			$orderby = 'tbid desc limit '.$from.','.$to;
			if($search_1 != ''){
				$sqlwhere[] = 'name like "%'.$search_1.'%"';
			}
			$rs = $db->select(0, 0, 'tb_permission', '*', $sqlwhere, $orderby);
			if($rs==NULL){
				$c = $db->select(0, 2, 'tb_permission', 'tbid', $sqlwhere);
				echo $c.'<{|*|}>';
			}else{
				if($reset){
					$c = $db->select(0, 2, 'tb_permission', 'tbid', $sqlwhere);
					echo $c.'<{|*|}>';
				}else{
					echo '-1<{|*|}>';
				}	
			}
			foreach($rs as $v){
				echo '<tr class="list-bd">';
					echo '<td style="text-align:left;padding-left:15px">'.$v['name'].'</td>';
					echo '<td><a href="detail.php?permissionid='.$v['tbid'].'" class="btn btn-mini btn-link">编辑</a><a href="javascript:;" class="btn btn-mini btn-link do-del" permissionid="'.$v['tbid'].'">删除</a></td>';
				echo '</tr>';
			}
			break;
		case 'ajaxDel':
			$db->delete(0, 0, 'tb_permission', 'and tbid='.$permissionid);
			break;
		default:
			$permissionscount = $db->select(0, 2, 'tb_permission', 'tbid');
			$smarty->assign('permissioncount', $permissionscount);
			$smarty->display('sysapp/permission/index.tpl');
	}
?>
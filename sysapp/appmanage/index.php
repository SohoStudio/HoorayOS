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
	else if(!checkPermissions(1)){
		$smarty->assign('code', $errorcode['noPermissions']);
		$smarty->display('error.tpl');
		exit;
	}
	
	switch($ac){
		case 'ajaxGetList':
			$orderby = 'dt desc limit '.$from.','.$to;
			if($search_1 != ''){
				$sqlwhere[] = 'name like "%'.$search_1.'%"';
			}
			if($search_2 != ''){
				$sqlwhere[] = 'kindid = '.$search_2;
			}
			$rs = $db->select(0, 0, 'tb_app', '*', $sqlwhere, $orderby);
			if($rs == NULL){
				$c = $db->select(0, 2, 'tb_app', 'tbid', $sqlwhere);
				echo $c.'<{|*|}>';
			}else{
				if($reset){
					$c = $db->select(0, 2, 'tb_app', 'tbid', $sqlwhere);
					echo $c.'<{|*|}>';
				}else{
					echo '-1<{|*|}>';
				}	
			}
			foreach($rs as $v){
				echo '<tr class="list-bd">';
					echo '<td style="text-align:left;padding-left:15px"><img src="../../'.$v['icon'].'" alt="'.$v['name'].'" class="appicon"><span class="appname">'.$v['name'].'</span></td>';
					echo '<td>'.$v['type'].'</td>';
					echo '<td>'.$apptype[$v['kindid']-1]['name'].'</td>';
					echo '<td>'.$v['usecount'].'</td>';
					echo '<td><a href="detail.php?appid='.$v['tbid'].'" class="btn btn-mini btn-link">编辑</a><a href="javascript:;" class="btn btn-mini btn-link do-del" appid="'.$v['tbid'].'">删除</a></td>';
				echo '</tr>';
			}
			break;
		case 'ajaxDel':
			$db->delete(0,0,'tb_app','and tbid='.$appid);
			break;
		default:
			$appcount = $db->select(0, 2, 'tb_app', 'tbid');
			$smarty->assign('appcount', $appcount);
			$smarty->assign('apptype', $apptype);
			$smarty->display('sysapp/appmanage/index.tpl');
	}
?>
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
		case 'ajaxGetList':
			$orderby = 'tbid desc limit '.$from.','.$to;
			if($search_1 != ''){
				$sqlwhere[] = 'username like "%'.$search_1.'%"';
			}
			if($search_2 != ''){
				$sqlwhere[] = 'type = '.$search_2;
			}
			$rs = $db->select(0, 0, 'tb_member', '*', $sqlwhere, $orderby);
			if($rs == NULL){
				$c = $db->select(0, 2, 'tb_member', 'tbid', $sqlwhere);
				echo $c.'<{|*|}>';
			}else{
				if($reset){
					$c = $db->select(0, 2, 'tb_member', 'tbid', $sqlwhere);
					echo $c.'<{|*|}>';
				}else{
					echo '-1<{|*|}>';
				}	
			}
			foreach($rs as $v){
				$type = $v['type'] == 1 ? '管理员' : '普通会员';
				echo '<tr class="list-bd">';
					echo '<td style="text-align:left;padding-left:15px">'.$v['username'].'</td>';
					echo '<td>'.$type.'</td>';
					echo '<td><a href="detail.php?memberid='.$v['tbid'].'" class="btn btn-mini btn-link">编辑</a><a href="javascript:;" class="btn btn-mini btn-link do-del" memberid="'.$v['tbid'].'">删除</a></td>';
				echo '</tr>';
			}
			break;
		case 'ajaxDel':
			$db->delete(0, 0, 'tb_member', 'and tbid='.$memberid);
			break;
		default:
			$appcount = $db->select(0, 2, 'tb_member', 'tbid');
			$smarty->assign('membercount', $appcount);
			$smarty->display('sysapp/member/index.tpl');
	}
?>
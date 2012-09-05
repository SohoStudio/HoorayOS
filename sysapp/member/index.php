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
				echo '<li><span class="level" style="width:20px">&nbsp;</span><span class="name">'.$v['username'].'</span><span class="do"><div class="input-prepend"><a class="btn btn-mini btn-info" href="detail.php?memberid='.$v['tbid'].'"><i class="icon-edit"></i> 编辑</a><a class="list_del btn btn-mini" href="javascript:;" memberid="'.$v['tbid'].'"><i class="icon-remove"></i> 删除</a></div></span><span class="type">'.$type.'</span></li>';
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
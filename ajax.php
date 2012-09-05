<?php
	require_once('global.php');
	require_once('inc/setting.inc.php');
	
	switch($ac){
		//登出
		case 'logout':
			session_unset();
			break;
		//获得主题
		case 'getWallpaper':
			$rs = $db->select(0, 1, 'tb_member', 'wallpaper_id,wallpapertype,wallpaperwebsite,wallpaperstate', 'and tbid='.$_SESSION['member']['id']);
			switch($rs['wallpaperstate']){
				case '1':
					$wallpaper = $db->select(0, 1, 'tb_wallpaper', 'url,width,height', 'and tbid='.$rs['wallpaper_id']);
					$wallpaper_array = array(
						$rs['wallpaperstate'],
						$wallpaper['url'],
						$rs['wallpapertype'],
						$wallpaper['width'],
						$wallpaper['height']
					);
					echo implode('<{|}>', $wallpaper_array);
					break;
				case '2':
					$wallpaper = $db->select(0, 1, 'tb_pwallpaper', 'url,width,height', 'and tbid='.$rs['wallpaper_id']);
					$wallpaper_array = array(
						$rs['wallpaperstate'],
						$wallpaper['url'],
						$rs['wallpapertype'],
						$wallpaper['width'],
						$wallpaper['height']
					);
					echo implode('<{|}>', $wallpaper_array);
					break;
				case '3':
					$wallpaper_array = array(
						$rs['wallpaperstate'],
						$rs['wallpaperwebsite']
					);
					echo implode('<{|}>', $wallpaper_array);
					break;
			}
			break;
		//更新主题
		case 'setWallpaper':
			$set = array(
				"wallpaperstate = $wpstate",
				"wallpapertype = '$wptype'"
			);
			switch($wpstate){
				case '1':
				case '2':
					if($wp != ''){
						$set[] = 'wallpaper_id = '.$wp;
					}					
					break;
				case '3':
					if($wp != ''){
						$set[] = "wallpaperwebsite = '$wp'";
					}
					break;
			}
			$db->update(0, 0, 'tb_member', $set, 'and tbid='.$_SESSION['member']['id']);
			break;
		//获得窗口皮肤
		case 'getSkin':
			$skin = $db->select(0, 1, 'tb_member', 'skin', 'and tbid='.$_SESSION['member']['id']);
			echo $skin['skin'];
			break;
		//获得应用码头位置
		case 'getDockPos':
			$dockpos = $db->select(0, 1, 'tb_member', 'dockpos', 'and tbid='.$_SESSION['member']['id']);
			echo $dockpos['dockpos'];
			break;
		//更新应用码头位置
		case 'setDockPos':
			$db->update(0, 0, 'tb_member', 'dockpos = "'.$dock.'"', 'and tbid='.$_SESSION['member']['id']);
			break;
		//获得图标排列方式
		case 'getAppXY':
			$appxy = $db->select(0, 1, 'tb_member', 'appxy', 'and tbid='.$_SESSION['member']['id']);
			echo $appxy['appxy'];
			break;
		//更新图标排列方式
		case 'setAppXY':
			$db->update(0, 0, 'tb_member', 'appxy = "'.$appxy.'"', 'and tbid='.$_SESSION['member']['id']);
			break;
		//获得文件夹内图标
		case 'getMyFolderApp':
			if($_SESSION['member']['id'] != ''){
				$content = $db->select(0, 1, 'tb_folder', 'content', 'and tbid='.$folderid.' and member_id='.$_SESSION['member']['id']);
				if($content['content'] != ''){
					$id_array = explode(',', $content['content']);
					foreach($id_array as $v){
						$v = explode('_', $v);
						switch($v[0]){
							case 'app':
							case 'widget':
								$rs = $db->select(0, 1, 'tb_app', '*', 'and tbid ='.$v[1]);
								$tmp['type'] = $rs['type'];
								$tmp['id'] = $rs['tbid'];
								$tmp['name'] = $rs['name'];
								$tmp['icon'] = $rs['icon'];
								break;
							case 'papp':
							case 'pwidget':
								$rs = $db->select(0, 1, 'tb_papp', '*', 'and tbid ='.$v[1]);
								$tmp['type'] = $rs['type'];
								$tmp['id'] = $rs['tbid'];
								$tmp['name'] = $rs['name'];
								$tmp['icon'] = $rs['icon'];
								break;
						}
						$data[] = $tmp;
						unset($tmp);
					}
					echo json_encode($data);
				}
			}
			break;
		//获得桌面图标
		case 'getMyApp':
			if($_SESSION['member']['id'] != ''){
				$appid = $db->select(0, 1, 'tb_member', 'dock,desk1,desk2,desk3,desk4,desk5', 'and tbid='.$_SESSION['member']['id']);
				$desktop['dock'] = array();
				for($i = 1; $i<=5; $i++){
					$desktop['desk'.$i] = array();
				}
				if($appid['dock'] != ''){
					$dock_list = explode(',', $appid['dock']);
					foreach($dock_list as $v){
						$v = explode('_', $v);
						switch($v[0]){
							case 'app':
							case 'widget':
								$rs = $db->select(0, 1, 'tb_app', '*', 'and tbid ='.$v[1]);
								$tmp['type'] = $rs['type'];
								$tmp['id'] = $rs['tbid'];
								$tmp['name'] = $rs['name'];
								$tmp['icon'] = $rs['icon'];
								break;
							case 'papp':
							case 'pwidget':
								$rs = $db->select(0, 1, 'tb_papp', '*', 'and tbid = '.$v[1].' and member_id = '.$_SESSION['member']['id']);
								$tmp['type'] = $rs['type'];
								$tmp['id'] = $rs['tbid'];
								$tmp['name'] = $rs['name'];
								$tmp['icon'] = $rs['icon'];
								break;
							case 'folder':
								$rs = $db->select(0, 1, 'tb_folder', '*', 'and tbid = '.$v[1].' and member_id = '.$_SESSION['member']['id']);
								$tmp['type'] = 'folder';
								$tmp['id'] = $rs['tbid'];
								$tmp['name'] = $rs['name'];
								$tmp['icon'] = $rs['icon'];
								break;
						}
						$data[] = $tmp;
						unset($tmp);
					}
					$desktop['dock'] = $data;
					unset($data);
					unset($dock_list);
				}
				for($i = 1; $i<=5; $i++){
					if($appid['desk'.$i] != ''){
						$deskappid_list = explode(',', $appid['desk'.$i]);
						foreach($deskappid_list as $v){
							$v = explode('_', $v);
							switch($v[0]){
								case 'app':
								case 'widget':
									$rs = $db->select(0, 1, 'tb_app', '*', 'and tbid ='.$v[1]);
									$tmp['type'] = $rs['type'];
									$tmp['id'] = $rs['tbid'];
									$tmp['name'] = $rs['name'];
									$tmp['icon'] = $rs['icon'];
									break;
								case 'papp':
								case 'pwidget':
									$rs = $db->select(0, 1, 'tb_papp', '*', 'and tbid = '.$v[1].' and member_id = '.$_SESSION['member']['id']);
									$tmp['type'] = $rs['type'];
									$tmp['id'] = $rs['tbid'];
									$tmp['name'] = $rs['name'];
									$tmp['icon'] = $rs['icon'];
									break;
								case 'folder':
									$rs = $db->select(0, 1, 'tb_folder', '*', 'and tbid = '.$v[1].' and member_id = '.$_SESSION['member']['id']);
									$tmp['type'] = 'folder';
									$tmp['id'] = $rs['tbid'];
									$tmp['name'] = $rs['name'];
									$tmp['icon'] = $rs['icon'];
									break;
							}
							$data[] = $tmp;
							unset($tmp);
						}
						$desktop['desk'.$i] = $data;
						unset($data);
						unset($deskappid_list);
					}
				}
				echo json_encode($desktop);
			}
			break;
		//根据id获取图标
		case 'getMyAppById':
			$flag = checkAppIsMine($type.'_'.$id);
			if($flag){
				switch($type){
					case 'app':
					case 'widget':
						$rs = $db->select(0, 1, 'tb_app', '*', 'and tbid = '.$id);
						if($rs != NULL){
							$app['type'] = $rs['type'];
							$app['id'] = $rs['tbid'];
							$app['name'] = $rs['name'];
							$app['icon'] = $rs['icon'];
							$app['url'] = $rs['url'];
							$app['width'] = $rs['width'];
							$app['height'] = $rs['height'];
							$app['isresize'] = $rs['isresize'];
							$app['issetbar'] = $rs['issetbar'];
							$app['isflash'] = $rs['isflash'];
						}
						break;
					case 'papp':
					case 'pwidget':
						$rs = $db->select(0, 1, 'tb_papp', '*', 'and tbid = '.$id.' and member_id = '.$_SESSION['member']['id']);
						if($rs != NULL){
							$app['type'] = $rs['type'];
							$app['id'] = $rs['tbid'];
							$app['name'] = $rs['name'];
							$app['icon'] = $rs['icon'];
							$app['url'] = $rs['url'];
							$app['width'] = $rs['width'];
							$app['height'] = $rs['height'];
							$app['isresize'] = $rs['isresize'];
							$app['issetbar'] = 0;
							$app['isflash'] = 1;
						}
						break;
					case 'folder':
						$rs = $db->select(0, 1, 'tb_folder', '*', 'and tbid = '.$id.' and member_id = '.$_SESSION['member']['id']);
						if($rs != NULL){
							$app['type'] = 'folder';
							$app['id'] = $rs['tbid'];
							$app['name'] = $rs['name'];
							$app['icon'] = $rs['icon'];
							$app['width'] = '650';
							$app['height'] = '400';
						}
						break;
				}
				echo json_encode($app);
			}
			break;
		//添加桌面图标
		case 'addMyApp':
			$rs = $db->select(0, 1, 'tb_member', 'desk'.$desk, 'and tbid='.$_SESSION['member']['id']);
			$deskapp = $rs['desk'.$desk];
			if($deskapp == ''){
				$deskapp = $type.'_'.$id;
			}else{
				$deskapp .= ','.$type.'_'.$id;
			}
			$db->update(0, 0, 'tb_app', 'usecount=usecount+1', 'and tbid='.$id);
			$db->update(0, 0, 'tb_member', 'desk'.$desk.'="'.$deskapp.'"', 'and tbid='.$_SESSION['member']['id']);
			break;
		//删除桌面图标
		case 'delMyApp':
			switch($type){
				case 'app':
				case 'widget':
					$rs = $db->select(0, 1, 'tb_member', 'dock,desk1,desk2,desk3,desk4,desk5', 'and tbid='.$_SESSION['member']['id']);
					$flag = false;
					$sqlwhere = '';
					if($rs['dock'] != ''){
						$dockapp = explode(',', $rs['dock']);
						foreach($dockapp as $k => $v){
							if($v == $type.'_'.$id){
								$flag = true;
								unset($dockapp[$k]);
								break;
							}
						}
						$sqlwhere .= 'dock="'.implode(',', $dockapp).'"';
					}else{
						$sqlwhere .= 'dock=""';
					}
					for($i=1; $i<=5; $i++){
						if($rs['desk'.$i] != ''){
							$deskapp = explode(',', $rs['desk'.$i]);
							foreach($deskapp as $k => $v){
								if($v == $type.'_'.$id){
									$flag = true;
									unset($deskapp[$k]);
									break;
								}
							}
							$sqlwhere .= ',desk'.$i.'="'.implode(',', $deskapp).'"';
						}else{
							$sqlwhere .= ',desk'.$i.'=""';
						}
					}
					if($flag){
						$db->update(0, 0, 'tb_member', $sqlwhere, 'and tbid='.$_SESSION['member']['id']);
					}else{
						$rs = $db->select(0, 0, 'tb_folder', 'content,tbid', 'and content!="" and member_id='.$_SESSION['member']['id']);
						if($rs != NULL){
							foreach($rs as $v){
								$flag = false;
								$folderapp = explode(',', $v['content']);
								foreach($folderapp as $key => $value){
									if($value == $type.'_'.$id){
										$flag = true;
										unset($folderapp[$key]);
										break;
									}
								}
								$folderappid = implode(',', $folderapp);
								if($flag){
									$db->update(0, 0, 'tb_folder', "content='$folderappid'", 'and tbid='.$v['tbid'].' and member_id='.$_SESSION['member']['id']);
								}
							}
						}
					}
					$db->update(0, 0, 'tb_app', 'usecount=usecount-1', 'and tbid='.$id);
					break;
				case 'papp':
				case 'pwidget':
					$rs = $db->select(0, 1, 'tb_member', 'dock,desk1,desk2,desk3,desk4,desk5', 'and tbid='.$_SESSION['member']['id']);
					$flag = false;
					$sqlwhere = '';
					if($rs['dock'] != ''){
						$dockapp = explode(',', $rs['dock']);
						foreach($dockapp as $k => $v){
							if($v == $type.'_'.$id){
								$flag = true;
								unset($dockapp[$k]);
								break;
							}
						}
						$sqlwhere .= 'dock="'.implode(',', $dockapp).'"';
					}else{
						$sqlwhere .= 'dock=""';
					}
					for($i=1; $i<=5; $i++){
						if($rs['desk'.$i] != ''){
							$deskapp = explode(',', $rs['desk'.$i]);
							foreach($deskapp as $k => $v){
								if($v == $type.'_'.$id){
									$flag = true;
									unset($deskapp[$k]);
									break;
								}
							}
							$sqlwhere .= ',desk'.$i.'="'.implode(',', $deskapp).'"';
						}else{
							$sqlwhere .= ',desk'.$i.'=""';
						}
					}
					if($flag){
						$db->update(0, 0, 'tb_member', $sqlwhere, 'and tbid='.$_SESSION['member']['id']);
					}else{
						$rs = $db->select(0, 0, 'tb_folder', 'content,tbid', 'and content!="" and member_id='.$_SESSION['member']['id']);
						if($rs != NULL){
							foreach($rs as $v){
								$flag = false;
								$folderapp = explode(',', $v['content']);
								foreach($folderapp as $key => $value){
									if($value == $type.'_'.$id){
										$flag = true;
										unset($folderapp[$key]);
										break;
									}
								}
								$folderappid = implode(',', $folderapp);
								if($flag){
									$db->update(0, 0, 'tb_folder', "content='$folderappid'", 'and tbid='.$v['tbid'].' and member_id='.$_SESSION['member']['id']);
								}
							}
						}
					}
					$db->delete(0, 0, 'tb_papp', "and tbid = $id and member_id = ".$_SESSION['member']['id']);
					break;
				case 'folder':
					//先删除文件夹内的app
					$rs = $db->select(0, 1, 'tb_folder', 'content', 'and tbid='.$id.' and member_id='.$_SESSION['member']['id']);
					if($rs['content'] != ''){
						$folderapp = explode(',', $rs['content']);
						foreach($folderapp as $f){
							$tmp = explode('_', $f);
							$ctype = $tmp[0];
							$cid = $tmp[1];
							switch($ctype){
								case 'app':
								case 'widget':
									$db->update(0, 0, 'tb_app', 'usecount=usecount-1', 'and tbid='.$cid);
									break;
								case 'papp':
								case 'pwidget':
									$db->delete(0, 0, 'tb_papp', "and tbid = $cid and member_id = ".$_SESSION['member']['id']);
									break;
							}
						}
					}
					//然后再删除文件夹本身
					$rs = $db->select(0, 1, 'tb_member', 'dock,desk1,desk2,desk3,desk4,desk5', 'and tbid='.$_SESSION['member']['id']);
					$sqlwhere = '';
					if($rs['dock'] != ''){
						$dockapp = explode(',', $rs['dock']);
						foreach($dockapp as $k => $v){
							if($v == $type.'_'.$id){
								unset($dockapp[$k]);
								break;
							}
						}
						$sqlwhere .= 'dock="'.implode(',', $dockapp).'"';
					}else{
						$sqlwhere .= 'dock=""';
					}
					for($i=1; $i<=5; $i++){
						if($rs['desk'.$i] != ''){
							$deskapp = explode(',', $rs['desk'.$i]);
							foreach($deskapp as $k => $v){
								if($v == $type.'_'.$id){
									unset($deskapp[$k]);
									break;
								}
							}
							$sqlwhere .= ',desk'.$i.'="'.implode(',', $deskapp).'"';
						}else{
							$sqlwhere .= ',desk'.$i.'=""';
						}
					}
					$db->update(0, 0, 'tb_member', $sqlwhere, 'and tbid='.$_SESSION['member']['id']);
					$db->delete(0, 0, 'tb_folder', "and tbid = $id and member_id = ".$_SESSION['member']['id']);
					break;
			}
			break;
		//更新桌面图标
		case 'moveMyApp':
			$rs = $db->select(0, 1, 'tb_member', 'dock,desk1,desk2,desk3,desk4,desk5', 'and tbid='.$_SESSION['member']['id']);
			$flag = false;
			$sqlwhere = '';
			if($rs['dock'] != ''){
				$dockapp = explode(',', $rs['dock']);
				foreach($dockapp as $k => $v){
					if($v == $type.'_'.$id){
						$flag = true;
						unset($dockapp[$k]);
						break;
					}
				}
				$sqlwhere .= 'dock="'.implode(',', $dockapp).'"';
			}else{
				$sqlwhere .= 'dock=""';
			}
			for($i=1; $i<=5; $i++){
				if($rs['desk'.$i] != ''){
					$deskapp = explode(',', $rs['desk'.$i]);
					foreach($deskapp as $k => $v){
						if($v == $type.'_'.$id){
							$flag = true;
							unset($deskapp[$k]);
							break;
						}
					}
					$sqlwhere .= ',desk'.$i.'="'.implode(',', $deskapp).'"';
				}else{
					$sqlwhere .= ',desk'.$i.'=""';
				}
			}
			if($flag){
				$db->update(0, 0, 'tb_member', $sqlwhere, 'and tbid='.$_SESSION['member']['id']);
			}else{
				$rs = $db->select(0, 0, 'tb_folder', 'content,tbid', 'and content!="" and member_id='.$_SESSION['member']['id']);
				if($rs != NULL){
					foreach($rs as $v){
						$flag = false;
						$folderapp = explode(',', $v['content']);
						foreach($folderapp as $key => $value){
							if($value == $type.'_'.$id){
								$flag = true;
								unset($folderapp[$key]);
								break;
							}
						}
						$folderappid = implode(',', $folderapp);
						if($flag){
							$db->update(0, 0, 'tb_folder', "content='$folderappid'", 'and tbid='.$v['tbid'].' and member_id='.$_SESSION['member']['id']);
						}
					}
				}
			}
			$rs = $db->select(0, 1, 'tb_member', 'desk'.$todesk, 'and tbid='.$_SESSION['member']['id']);
			$rs['desk'.$todesk] = $rs['desk'.$todesk] == '' ? $type.'_'.$id : $rs['desk'.$todesk].','.$type.'_'.$id;
			$db->update(0, 0, 'tb_member', "desk".$todesk."='".$rs['desk'.$todesk]."'", 'and tbid='.$_SESSION['member']['id']);
			break;
		case 'updateMyApp':
			switch($movetype){
				case 'dock-folder':
					$rs1 = $db->select(0, 1, 'tb_member', 'dock', 'and tbid='.$_SESSION['member']['id']);
					$rs2 = $db->select(0, 1, 'tb_folder', 'content', 'and tbid='.$to.' and member_id='.$_SESSION['member']['id']);
					$dock_arr = explode(',', $rs1['dock']);
					$key = array_search($type.'_'.$id, $dock_arr);
					unset($dock_arr[$key]);
					$rs2['content'] = $rs2['content'] == '' ? $type.'_'.$id : $rs2['content'].','.$type.'_'.$id;
					$db->update(0, 0, 'tb_member', "dock='".implode(',', $dock_arr)."'", 'and tbid='.$_SESSION['member']['id']);
					$db->update(0, 0, 'tb_folder', "content='".$rs2['content']."'", 'and tbid='.$to.' and member_id='.$_SESSION['member']['id']);
					break;
				case 'dock-dock':
					$rs = $db->select(0, 1, 'tb_member', 'dock', 'and tbid='.$_SESSION['member']['id']);
					$dock_arr = explode(',', $rs['dock']);
					//判断传入的应用id和数据库里的id是否吻合
					if($dock_arr[$from] == $type.'_'.$id){
						if($from > $to){
							for($i = $from; $i > $to; $i--){
								$dock_arr[$i] = $dock_arr[$i-1];
							}
							$dock_arr[$to] = $type.'_'.$id;
						}else if($to > $from){
							for($i = $from; $i < $to; $i++){
								$dock_arr[$i] = $dock_arr[$i+1];
							}
							$dock_arr[$to] = $type.'_'.$id;
						}
						$dock_arr = formatAppidArray($dock_arr);
						$db->update(0, 0, 'tb_member', "dock='".implode(',', $dock_arr)."'", 'and tbid='.$_SESSION['member']['id']);
					}
					break;
				case 'dock-desk':
					$rs = $db->select(0, 1, 'tb_member', 'dock,desk'.$desk, 'and tbid='.$_SESSION['member']['id']);
					$dock_arr = explode(',', $rs['dock']);
					$desk_arr = explode(',', $rs['desk'.$desk]);
					unset($dock_arr[$from]);
					if($desk_arr[0] == ''){
						$desk_arr[0] = $type.'_'.$id;
					}else{
						array_splice($desk_arr, $to, 0, $type.'_'.$id);
					}
					$db->update(0, 0, 'tb_member', "dock='".implode(',', $dock_arr)."',desk".$desk."='".implode(',', $desk_arr)."'", 'and tbid='.$_SESSION['member']['id']);
					break;
				case 'desk-folder':
					$rs1 = $db->select(0, 1, 'tb_member', 'desk'.$desk, 'and tbid='.$_SESSION['member']['id']);
					$rs2 = $db->select(0, 1, 'tb_folder', 'content', 'and tbid='.$to.' and member_id='.$_SESSION['member']['id']);
					$desk_arr = explode(',', $rs1['desk'.$desk]);
					$key = array_search($type.'_'.$id, $desk_arr);
					unset($desk_arr[$key]);
					$rs2['content'] = $rs2['content'] == '' ? $type.'_'.$id : $rs2['content'].','.$type.'_'.$id;
					$db->update(0, 0, 'tb_member', "desk".$desk."='".implode(',', $desk_arr)."'", 'and tbid='.$_SESSION['member']['id']);
					$db->update(0, 0, 'tb_folder', "content='".$rs2['content']."'", 'and tbid='.$to.' and member_id='.$_SESSION['member']['id']);
					break;
				case 'desk-dock':
					$rs = $db->select(0, 1, 'tb_member', 'dock,desk'.$desk, 'and tbid='.$_SESSION['member']['id']);
					$dock_arr = explode(',', $rs['dock']);
					$desk_arr = explode(',', $rs['desk'.$desk]);
					unset($desk_arr[$from]);
					if($dock_arr[0] == ''){
						$dock_arr[0] = $type.'_'.$id;
					}else{
						array_splice($dock_arr, $to, 0, $type.'_'.$id);						
					}
					if(count($dock_arr) > 7){
						$desk_arr[] = $dock_arr[7];
						unset($dock_arr[7]);
					}
					$db->update(0, 0, 'tb_member', "dock='".implode(',', $dock_arr)."',desk".$desk."='".implode(',', $desk_arr)."'", 'and tbid='.$_SESSION['member']['id']);
					break;
				case 'desk-desk':
					$rs = $db->select(0, 1, 'tb_member', 'desk'.$desk, 'and tbid='.$_SESSION['member']['id']);
					$desk_arr = explode(',', $rs['desk'.$desk]);
					//判断传入的应用id和数据库里的id是否吻合
					if($desk_arr[$from] == $type.'_'.$id){
						if($from > $to){
							for($i = $from; $i > $to; $i--){
								$desk_arr[$i] = $desk_arr[$i-1];
							}
							$desk_arr[$to] = $type.'_'.$id;
						}else if($to > $from){
							for($i = $from; $i < $to; $i++){
								$desk_arr[$i] = $desk_arr[$i+1];
							}
							$desk_arr[$to] = $type.'_'.$id;
						}
						$desk_arr = formatAppidArray($desk_arr);
						$db->update(0, 0, 'tb_member', "desk".$desk."='".implode(',',$desk_arr)."'", 'and tbid='.$_SESSION['member']['id']);
					}
					break;
				case 'desk-otherdesk':
					$rs = $db->select(0, 1, 'tb_member', 'desk'.$desk.',desk'.$otherdesk, 'and tbid='.$_SESSION['member']['id']);
					$desk_arr = explode(',', $rs['desk'.$desk]);
					$otherdesk_arr = explode(',', $rs['desk'.$otherdesk]);
					unset($desk_arr[$from]);
					if($otherdesk_arr[0] == ''){
						$otherdesk_arr[0] = $type.'_'.$id;
					}else{
						array_splice($otherdesk_arr, $to, 0, $type.'_'.$id);
					}
					$db->update(0, 0, 'tb_member', "desk".$desk."='".implode(',', $desk_arr)."',desk".$otherdesk."='".implode(',', $otherdesk_arr)."'", 'and tbid='.$_SESSION['member']['id']);
					break;
				case 'folder-folder':
					$rs1 = $db->select(0, 1, 'tb_folder', 'content', 'and tbid='.$from.' and member_id='.$_SESSION['member']['id']);
					$rs2 = $db->select(0, 1, 'tb_folder', 'content', 'and tbid='.$to.' and member_id='.$_SESSION['member']['id']);
					$folder1appid_arr = explode(',', $rs1['content']);
					$folder2appid_arr = explode(',', $rs2['content']);
					$key = array_search($type.'_'.$id, $folder1appid_arr);
					unset($folder1appid_arr[$key]);
					$rs2['content'] = $rs2['content'] == '' ? $type.'_'.$id : $rs2['content'].','.$type.'_'.$id;
					$db->update(0, 0, 'tb_folder', "content='".implode(',', $folder1appid_arr)."'", 'and tbid='.$from.' and member_id='.$_SESSION['member']['id']);
					$db->update(0, 0, 'tb_folder', "content='".$rs2['content']."'", 'and tbid='.$to.' and member_id='.$_SESSION['member']['id']);
					break;
				case 'folder-dock':
					$rs1 = $db->select(0, 1, 'tb_member', 'dock,desk'.$desk, 'and tbid='.$_SESSION['member']['id']);
					$rs2 = $db->select(0, 1, 'tb_folder', 'content', 'and tbid='.$from.' and member_id='.$_SESSION['member']['id']);
					$dock_arr = explode(',', $rs1['dock']);
					$desk_arr = explode(',', $rs1['desk'.$desk]);
					$folderappid_arr = explode(',', $rs2['content']);
					$key = array_search($type.'_'.$id, $folderappid_arr);
					unset($folderappid_arr[$key]);
					if($dock_arr[0] == ''){
						$dock_arr[0] = $type.'_'.$id;
					}else{
						array_splice($dock_arr, $to, 0, $type.'_'.$id);
					}
					if(count($dock_arr) > 7){
						$desk_arr[] = $dock_arr[7];
						unset($dock_arr[7]);
					}
					$db->update(0, 0, 'tb_member', "dock='".implode(',', $dock_arr)."',desk".$desk."='".implode(',', $desk_arr)."'", 'and tbid='.$_SESSION['member']['id']);
					$db->update(0, 0, 'tb_folder', "content='".implode(',', $folderappid_arr)."'", 'and tbid='.$from.' and member_id='.$_SESSION['member']['id']);
					break;
				case 'folder-desk':
					$rs1 = $db->select(0, 1, 'tb_member', 'desk'.$desk, 'and tbid='.$_SESSION['member']['id']);
					$rs2 = $db->select(0, 1, 'tb_folder', 'content', 'and tbid='.$from.' and member_id='.$_SESSION['member']['id']);
					$desk_arr = explode(',', $rs1['desk'.$desk]);
					$folderappid_arr = explode(',', $rs2['content']);
					$key = array_search($type.'_'.$id, $folderappid_arr);
					unset($folderappid_arr[$key]);
					if($desk_arr[0] == ''){
						$desk_arr[0] = $type.'_'.$id;
					}else{
						array_splice($desk_arr, $to, 0, $type.'_'.$id);
					}
					$db->update(0, 0, 'tb_member', "desk".$desk."='".implode(',', $desk_arr)."'", 'and tbid='.$_SESSION['member']['id']);
					$db->update(0, 0, 'tb_folder', "content='".implode(',', $folderappid_arr)."'", 'and tbid='.$from.' and member_id='.$_SESSION['member']['id']);
					break;
			}
			break;
		//新建文件夹
		case 'addFolder':
			$set = array(
				"icon = '$icon'",
				"name = '$name'",
				"member_id = ".$_SESSION['member']['id'],
				"dt = now()"
			);
			$folderid = $db->insert(0, 2, 'tb_folder', $set);
			echo $folderid;
			break;
		//文件夹重命名
		case 'updateFolder':
			$db->update(0, 0, 'tb_folder', "icon='$icon', name='$name'", 'and tbid='.$id.' and member_id='.$_SESSION['member']['id']);
			break;
		//新建私人应用
		case 'addPapp':
			$set = array(
				"name = '$name'",
				"icon = 'img/ui/papp.png'",
				"url = '$url'",
				"type = '$type'",
				"width = $width",
				"height = $height",
				"isresize = $isresize",
				"dt = now()",
				"member_id = ".$_SESSION['member']['id'],
				"indexid = 1"
			);
			$pappid = $db->insert(0, 2, 'tb_papp', $set);
			echo $pappid;
			break;
		//编辑私人应用
		case 'updatePapp':
			$db->update(0, 0, 'tb_papp', "name='$name', url='$url', width=$width, height=$height", 'and tbid='.$id.' and member_id='.$_SESSION['member']['id']);
			break;
		//获得应用介绍
		case 'getAppRemark':
			$rs = $db->select(0, 1, 'tb_app', 'remark', 'and tbid='.$id);
			echo $rs['remark'];
			break;
		//获得应用评分
		case 'getAppStar':
			$rs = $db->select(0, 1, 'tb_app', 'starnum', 'and tbid='.$id);
			echo $rs['starnum'];
			break;
		//更新应用评分
		case 'updateAppStar':
			$isscore = $db->select(0, 2, 'tb_app_star', 'tbid', 'and app_id='.$id.' and member_id='.$_SESSION['member']['id']);
			if($isscore == 0){
				$set = array(
					"app_id = ".$id,
					"member_id = ".$_SESSION['member']['id'],
					"starnum = $starnum",
					"dt = now()"
				);
				$db->insert(0, 0, 'tb_app_star', $set);
				$scoreavg = $db->select(0, 1, 'tb_app_star', 'avg(starnum) as starnum', 'and app_id='.$id);
				$db->update(0, 0, 'tb_app', 'starnum = "'.$scoreavg['starnum'].'"', 'and tbid='.$id);
				echo true;
			}else{
				echo false;
			}
			break;
		case 'html5upload':
			$r = new stdClass();
			//文件名转码，防止中文出现乱码，最后输出时再转回来
			$file_array = explode('.', iconv('UTF-8', 'gb2312', $_FILES['xfile']['name']));
			//取出扩展名
			$extension = $file_array[count($file_array) - 1];
			unset($file_array[count($file_array) - 1]);
			//取出文件名
			$name = implode('.', $file_array);
			//拼装新文件名（含扩展名）
			$file = $name.'_'.sha1(@microtime().$_FILES['xfile']['name']).'.'.$extension;
			//验证文件是否合格
			if(in_array($extension, $uploadFileUnType)){
				$r->error = "上传文件类型系统不支持";
			}else if($_FILES['xfile']['size'] > ($uploadFileMaxSize * 1048576)){
				$r->error = "上传文件单个大小不能超过 $uploadFileMaxSize MB";
			}else{
				$icon = '';
				foreach($uploadFileType as $uft){
					if($uft['ext'] == $extension){
						$icon = $uft['icon'];
						break;
					}
				}
				if($icon == ''){
					$icon = 'img/ui/file_unknow.png';
				}
				//生成文件存放路径
				$dir = 'dofiles/member/'.$_SESSION['member']['id'].'/file/';
				if(!is_dir($dir)){
					//循环创建目录
					recursive_mkdir($dir);
				}
				//上传
				move_uploaded_file($_FILES['xfile']["tmp_name"], $dir.$file);
				
				$r->dir = $dir;
				$r->file = iconv('gb2312', 'UTF-8', $file);
				$r->name = iconv('gb2312', 'UTF-8', $name);
				$r->extension = iconv('gb2312', 'UTF-8', $extension);
				$r->icon = $icon;
			}
			echo json_encode($r);
			break;
	}
?>
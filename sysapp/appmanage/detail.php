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
		case 'ajaxEdit':
			$issetbar = $kindid == 1 ? 0 : 1;
			$set = array(
				"icon = '$val_icon'",
				"name = '$val_name'",
				"kindid = $val_kindid",
				"url = '$val_url'",
				"type = '$val_type'",
				"width = $val_width",
				"height = $val_height",
				"isresize = $val_isresize",
				"issetbar = $issetbar",
				"isflash = $val_isflash",
				"remark = '$val_remark'"
			);
			if($id == ''){
				$set[] = "dt = now()";
				$db->insert(0, 0, 'tb_app', $set);
			}else{
				$sqlwhere = "and tbid = $id";
				$db->update(0, 0, 'tb_app', $set, $sqlwhere);
			}
			break;
		case 'uploadimg':
			//别人的http上传文件类
			require_once('libs/upload.class.php');
			//文件目录
			$uploadImagePath = 'dofiles/shortcut/';
			if(!file_exists($uploadImagePath)){
				recursive_mkdir($uploadImagePath,0777);
			}
			
			$UploadFile = new HttpUpload();
			$tmpname = $_FILES['jUploaderFile']['tmp_name'];
			$name = $_FILES['jUploaderFile']['name'];
			$size = $_FILES['jUploaderFile']['size'];
			$error = $_FILES['jUploaderFile']['error'];
			
			if(strlen(trim($tmpname)) > 0){
				$UploadFile->getFile($tmpname,$name,$name,$error);
				if(!$UploadFile->upload(md5(uniqid(rand(), true)),$uploadImagePath)){exit;}
				//图片路径
				$returnImangeUrl = $uploadImagePath.$UploadFile->getFileName();
				echo "{success:true,fileUrl:'".$returnImangeUrl."'}";
			}
			break;
		case 'html5uploadimg':
			$r = new stdClass();
			//header('content-type: application/json');
			$maxsize = 1; //Mb
			if($_FILES['xfile']['size'] > ($maxsize * 1048576)){
				$r->error = "图片大小不超过 $maxsize MB";
			}
			$folder = 'dofiles/shortcut/';
			if(!is_dir($folder)){
				mkdir($folder);
			}
			$folder .= $_POST['folder'] ? $_POST['folder'] . '/' : '';
			if(!is_dir($folder)){
				mkdir($folder);
			}
			
			$tld = explode('.', $_FILES['xfile']['name']);
			$tld = $tld[count($tld) - 1];
			$filename = $_POST['value'] ? $_POST['value'] : $folder . sha1(@microtime() . '-' . $_FILES['xfile']['name']) . '.' . $tld;
			
			$types = Array('image/png', 'image/gif', 'image/jpeg');
			if(in_array($_FILES['xfile']['type'], $types)){
				$source = file_get_contents($_FILES["xfile"]["tmp_name"]);
				imageresize($source, $filename, $_POST['width'], $_POST['height'], $_POST['crop'], $_POST['quality']);
			}else{
				move_uploaded_file($_FILES["xfile"]["tmp_name"], $filename);
			}
			
			$r->filename = $filename;
			$r->img = '<img src="../../' . $filename . '" />';
			
			echo json_encode($r);
			break;
		default:
			if(isset($appid)){
				$rs = $db->select(0, 1, 'tb_app', '*', 'and tbid='.$appid);
				$smarty->assign('app', $rs);
			}
			$smarty->assign('apptype', $apptype);
			$smarty->display('sysapp/appmanage/detail.tpl');
	}
	
	function imageresize($source, $destination, $width = 0, $height = 0, $crop = false, $quality = 80) {
		$quality = $quality ? $quality : 80;
		$image = imagecreatefromstring($source);
		if($image){
			// Get dimensions
			$w = imagesx($image);
			$h = imagesy($image);
			if(($width && $w > $width) || ($height && $h > $height)){
				$ratio = $w / $h;
				if(($ratio >= 1 || $height == 0) && $width && !$crop){
					$new_height = $width / $ratio;
					$new_width = $width;
				}elseif($crop && $ratio <= ($width / $height)){
					$new_height = $width / $ratio;
					$new_width = $width;
				}else{
					$new_width = $height * $ratio;
					$new_height = $height;
				}
			}else{
				$new_width = $w;
				$new_height = $h;
			}
			$x_mid = $new_width * .5;  //horizontal middle
			$y_mid = $new_height * .5; //vertical middle
			// Resample
			error_log('height: ' . $new_height . ' - width: ' . $new_width);
			$new = imagecreatetruecolor(round($new_width), round($new_height));
			
			$c = imagecolorallocatealpha($new , 0 , 0 , 0 , 127);//拾取一个完全透明的颜色
			imagealphablending($new , false);//关闭混合模式，以便透明颜色能覆盖原画布
			imagefill($new , 0 , 0 , $c);//填充
			imagesavealpha($new , true);//设置保存PNG时保留透明通道信息
			
			imagecopyresampled($new, $image, 0, 0, 0, 0, $new_width, $new_height, $w, $h);
			// Crop
			if($crop){
				$crop = imagecreatetruecolor($width ? $width : $new_width, $height ? $height : $new_height);
				imagecopyresampled($crop, $new, 0, 0, ($x_mid - ($width * .5)), 0, $width, $height, $width, $height);
				//($y_mid - ($height * .5))
			}
			// Output
			// Enable interlancing [for progressive JPEG]
			imageinterlace($crop ? $crop : $new, true);

			$dext = strtolower(pathinfo($destination, PATHINFO_EXTENSION));
			if($dext == ''){
				$dext = $ext;
				$destination .= '.' . $ext;
			}
			switch($dext){
				case 'jpeg':
				case 'jpg':
					imagejpeg($crop ? $crop : $new, $destination, $quality);
					break;
				case 'png':
					$pngQuality = ($quality - 100) / 11.111111;
					$pngQuality = round(abs($pngQuality));
					imagepng($crop ? $crop : $new, $destination, $pngQuality);
					break;
				case 'gif':
					imagegif($crop ? $crop : $new, $destination);
					break;
			}
			@imagedestroy($image);
			@imagedestroy($new);
			@imagedestroy($crop);
		}
	}
?>
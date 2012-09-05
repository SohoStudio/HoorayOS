<?php
	require_once('../../global.php');
	require_once('inc/setting.inc.php');
	require_once('inc/smarty.php');
	
	switch($ac){
		case 'imgUpload':
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
			//生成文件存放路径
			$dir = 'dofiles/member/'.$_SESSION['member']['id'].'/wallpaper/';
			if(!is_dir($dir)){
				//循环创建目录
				recursive_mkdir($dir);
			}
			//获取原图宽高
			$source = file_get_contents($_FILES["xfile"]["tmp_name"]);
			$image = imagecreatefromstring($source);
			$w = imagesx($image);
			$h = imagesy($image);
			//缩略图上传
			imageresize($source, $dir.'s_'.$file, 150, 105);
			//原图上传
			move_uploaded_file($_FILES['xfile']["tmp_name"], $dir.$file);
			//上传完毕后，添加数据库记录
			$db->insert(0, 0, 'tb_pwallpaper', "url='".$dir.$file."', width=$w, height=$h, member_id = ".$_SESSION['member']['id']);
						
			$r->dir = $dir;
			$r->file = iconv('gb2312', 'UTF-8', $file);
			echo json_encode($r);
			break;
		case 'del':
			$db->delete(0, 0, 'tb_pwallpaper', "and tbid = $id");
			break;
		default:
			$rs = $db->select(0, 1, 'tb_member', 'wallpapertype,wallpaperwebsite', 'and tbid = '.$_SESSION['member']['id']);
			$smarty->assign('wallpaperType', $rs['wallpapertype']);
			$smarty->assign('wallpaperWebsite', $rs['wallpaperwebsite']);
			$rs = $db->select(0, 0, 'tb_pwallpaper');
			foreach($rs as &$value){
				$value['surl'] = getSimgSrc($value['url']);
			}
			$smarty->assign('wallpaper', $rs);
			$smarty->display('sysapp/wallpaper/custom.tpl');
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
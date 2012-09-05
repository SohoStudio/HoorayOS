<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>壁纸设置</title>
{include file="../global_css.tpl"}
<link rel="stylesheet" href="../../img/ui/sys.css">
</head>

<body>
	<div class="title">
		<ul>
			<li class="focus">壁纸设置</li>
			<li><a href="../skin/index.php">皮肤设置</a></li>
		</ul>
	</div>
	<div class="wallpapertype form-inline">
		<div class="input-prepend fl">
			<a class="btn" href="index.php">系统壁纸</a><a class="btn disabled">自定义</a>
		</div>
		<div class="input-prepend fr">
			<span class="add-on">显示方式</span><select name="wallpapertype" id="wallpapertype">
				<option value="tianchong"{if $wallpaperType=='tianchong'} selected{/if}>填充</option>
				<option value="shiying"{if $wallpaperType=='shiying'} selected{/if}>适应</option>
				<option value="pingpu"{if $wallpaperType=='pingpu'} selected{/if}>平铺</option>
				<option value="lashen"{if $wallpaperType=='lashen'} selected{/if}>拉伸</option>
				<option value="juzhong"{if $wallpaperType=='juzhong'} selected{/if}>居中</option>
			</select>
		</div>
	</div>
	<div class="wapppapercustom">
		<div class="tip">
			<a class="btn btn-mini fr" style="overflow:hidden;position:relative">上传壁纸<input type="file" id="uploadfilebtn" style="position:absolute;right:0;bottom:0;opacity:0;filter:alpha(opacity=0);display:block;width:200px;height:100px"></a>
			<strong>自定义壁纸：</strong>最多上传6张，每张上传的壁纸大小不超过1M
		</div>
		<div class="view">
			<ul>
				{foreach from=$wallpaper item=wp}
				<li id="{$wp.tbid}" style="background:url(../../{$wp.surl})"><a href="javascript:;">删 除</a></li>
				{/foreach}
			</ul>
		</div>
	</div>
	<div class="wapppaperwebsite form-inline">
		<button type="button" class="btn fr">应用</button>
		<div class="input-prepend">
			<span class="add-on">网络壁纸</span><input type="text" id="wallpaperurl" style="width:355px" placeholder="请输入一个URL地址（地址以jpg,jpeg,png,gif,html,htm结尾）" value="{$wallpaperWebsite}">
		</div>
	</div>
{include file="../global_js.tpl"}
{literal}
<script>
$(function(){
	$('#wallpapertype').on('change', function(){
		window.parent.HROS.wallpaper.update(2, $('#wallpapertype').val(), '');
	});
	$('.wapppapercustom li').on('click', function(){
		window.parent.HROS.wallpaper.update(2, $('#wallpapertype').val(), $(this).attr('id'));
	});
	$('.wapppapercustom li a').on('click', function(){
		var id = $(this).parent().attr('id');
		$.ajax({
			type : 'POST',
			url : 'custom.php',
			data : 'ac=del&id=' + id,
			success : function(){
				$('#' + id).remove();
			}
		});
		return false;
	});
	$('.wapppaperwebsite button').on('click', function(){
		window.parent.HROS.wallpaper.update(3, $('#wallpapertype').val(), $('#wallpaperurl').val());
	});
	$('#uploadfilebtn').on('change', function(e){
		var files = e.target.files || e.dataTransfer.files;
		if(files.length == 0){
			return;
		}
		//检测文件是不是图片
		if(files[0].type.indexOf('image') === -1){
			alert('请上传图片');
			return false;
		}
		//检测文件大小是否超过1M
		if(files[0].size > 1024*1024){
			alert('图片大小超过1M');
			return;
		}
		var fd = new FormData();
		fd.append('xfile', files[0]);
		var xhr = new XMLHttpRequest();
		if(xhr.upload){
			xhr.upload.addEventListener('progress', function(e){
				if(e.lengthComputable){
//					$('#uploadfile .filelist:eq(' + file.index + ') .do').html('[&nbsp;--&nbsp;]');
//					var loaded = Math.ceil(e.loaded / e.total * 100);
//					$('#uploadfile .filelist:eq(' + file.index + ') .progress').css({
//						width : loaded + '%'
//					});
				}
			}, false);
			xhr.addEventListener('load', function(e){
				if(xhr.readyState == 4 && xhr.status == 200){
					var result = jQuery.parseJSON(e.target.responseText);
					window.location.reload();
//					if(result.error == null){
//						$('#uploadfile .filelist:eq(' + file.index + ') .do').html('[&nbsp;√&nbsp;]');
//					}else{
//						$('#uploadfile .filelist:eq(' + file.index + ') .do').html('[&nbsp;×&nbsp;]').attr('title', result.error);
//					}
				}
			}, false);
			xhr.open('post', 'custom.php?ac=imgUpload', true);
			xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
			xhr.send(fd);
		}
	});
});
</script>
{/literal}
</body>
</html>
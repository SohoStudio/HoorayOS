<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>应用管理</title>
{include file="../global_css.tpl"}
<link rel="stylesheet" href="../../img/ui/sys.css">
</head>

<body>
<form action="detail.php" method="post" name="form" id="form">
<input type="hidden" name="ac" value="ajaxEdit">
<input type="hidden" name="id" value="{$app.tbid}">
<div class="creatbox">
	<div class="middle">
		<p class="detile-title">编辑应用</p>
		<div class="input-label">
			<label class="label-text">应用图标：</label>
			<div class="label-box">
				<div class="shortcutbox">
					{if $app.icon != null}
						<div class="shortcut-addicon bgnone"><img src="../../{$app.icon}"></div>
					{else}
						<div class="shortcut-addicon"></div>
					{/if}
					<div class="shortcut-selicon">
						<a href="javascript:;"><img src="../../img/ui/system-gear.png" valsrc="img/ui/system-gear.png"></a>
						<a href="javascript:;"><img src="../../img/ui/system-users.png" valsrc="img/ui/system-users.png"></a>
						<a href="javascript:;"><img src="../../img/ui/system-wrench.png" valsrc="img/ui/system-wrench.png"></a>
						<a href="javascript:;"><img src="../../img/ui/system-star.png" valsrc="img/ui/system-star.png"></a>
						<a href="javascript:;"><img src="../../img/ui/system-shapes.png" valsrc="img/ui/system-shapes.png"></a>
						<a href="javascript:;"><img src="../../img/ui/system-chart-bar.png" valsrc="img/ui/system-chart-bar.png"></a>
						<a href="javascript:;"><img src="../../img/ui/system-document-edit.png" valsrc="img/ui/system-document-edit.png"></a>
						<a href="javascript:;"><img src="../../img/ui/system-documents.png" valsrc="img/ui/system-documents.png"></a>
						<a href="javascript:;"><img src="../../img/ui/system-mail.png" valsrc="img/ui/system-mail.png"></a>
						<a href="javascript:;"><img src="../../img/ui/system-puzzle.png" valsrc="img/ui/system-puzzle.png"></a>
					</div>
				</div>
				<input type="hidden" name="val_icon" id="val_icon" value="{$app.icon}">
			</div>
		</div>
		<div class="input-label">
			<label class="label-text">应用名称：</label>
			<div class="label-box">
				<input type="text" class="text" name="val_name" value="{$app.name}">
			</div>
		</div>
		<div class="input-label">
			<label class="label-text">应用分类：</label>
			<div class="label-box">
				<select name="val_kindid">
					{foreach from=$apptype item=at}
						{if $at.id == $app.kindid}
							<option value="{$at.id}" selected="true">{$at.name}</option>
						{else}
							<option value="{$at.id}">{$at.name}</option>
						{/if}
					{/foreach}
				</select>
			</div>
		</div>
		<div class="input-label">
			<label class="label-text">应用地址：</label>
			<div class="label-box">
				<input type="text" name="val_url" value="{$app.url}" style="width:300px">
			</div>
		</div>
		<div class="input-label">
			<label class="label-text">窗口大小：</label>
			<div class="label-box">
				<div class="input-prepend input-append fl">
					<span class="add-on">宽</span><input type="text" name="val_width" value="{$app.width}" style="width:40px"><span class="add-on">px</span>
				</div>
				<div class="input-prepend input-append fl" style="margin-left:20px">
					<span class="add-on">高</span><input type="text" name="val_height" value="{$app.height}" style="width:40px"><span class="add-on">px</span>
				</div>
			</div>
		</div>
		<div class="input-label">
			<label class="label-text">应用类型：</label>
			<div class="label-box form-inline">
				<label class="radio" style="margin-right:10px"><input type="radio" name="val_type" value="app" {if $app.type == 'app' || $app.tbid == ''}checked{/if} {if $app.tbid != ''}disabled{/if}>APP</label>
				<label class="radio"><input type="radio" name="val_type" value="widget" {if $app.type == 'widget'}checked{/if} {if $app.tbid != ''}disabled{/if}>挂件</label>
			</div>
		</div>
		<div class="input-label input-label-isresize" {if $app.type == 'widget'}style="display:none"{/if}>
			<label class="label-text">窗口是否拉伸：</label>
			<div class="label-box form-inline">
				<label class="radio" style="margin-right:10px"><input type="radio" name="val_isresize" value="1" {if $app.isresize == 1}checked{/if}>是</label>
				<label class="radio"><input type="radio" name="val_isresize" value="0" {if $app.isresize == 0}checked{/if}>否</label>
			</div>
		</div>
		<div class="input-label input-label-isflash" {if $app.type == 'widget'}style="display:none"{/if}>
			<label class="label-text">是否为Flash：</label>
			<div class="label-box form-inline">
				<label class="radio" style="margin-right:10px"><input type="radio" name="val_isflash" value="1" {if $app.isflash == 1}checked{/if}>是</label>
				<label class="radio" style="margin-right:10px"><input type="radio" name="val_isflash" value="0" {if $app.isflash == 0}checked{/if}>否</label>
				<span class="txt">[<a href="javascript:;" class="tip blue" title="Flash应用可能会导致窗口覆盖，请认真选择">?</a>]</span>
			</div>
		</div>
		<div class="input-label">
			<label class="label-text">应用介绍：</label>
			<div class="label-box">
				<textarea class="textarea" name="val_remark" id="val_remark" style="width:300px;height:100px;margin-bottom:10px;">{$app.remark}</textarea>
			</div>
		</div>
	</div>
</div>
<div class="bottom-bar">
	<div class="con">
		<a class="btn btn-large btn-primary fr" menu="submit" href="javascript:;"><i class="icon-white icon-ok"></i> 确定</a>
		<a class="btn btn-large" menu="back" href="index.php"><i class="icon-arrow-left"></i> 返回应用列表</a>
	</div>
</div>
<div id="shortupload" style="width:260px;height:110px;position:relative;display:none"><div id="target_box" class="dashboard_target_box"><div id="drop_zone_home" class="dashboard_target_messages_container"><p id="dtb-msg2" class="dashboard_target_box_message" style="top:-44px">选择你的图片<br>开始上传</p><p id="dtb-msg1" class="dashboard_target_box_message" style="top:-44px"><span class="compatible" style="display:inline">拖动图片到这里<br>开始上传图片</span><span class="notcompatible" id="dtb-msg4" style="display:none">点这里<br>开始上传图片</span></p></div><p id="dtb-msg3" class="dashboard_target_box_message">选择网络图片</p><p id="dtb-msg4" class="dashboard_target_box_message" style="position:relative"><span style="display:none;width:200px;height:2px;background:#ccc;left:-25px;position:absolute;z-index:1"></span><span style="display:none;width:0px;height:2px;background:#09F;left:-25px;position:absolute;z-index:2"></span></p></div></div>
</form>
{include file="../global_js.tpl"}
{literal}
<script>
$().ready(function(){
	//初始化ajaxForm
	var options = {
		beforeSubmit : showRequest,
		success : showResponse,
		type : 'POST'
	};
	$('#form').ajaxForm(options);
	$('input[name="value_5"]').change(function(){
		if($(this).val() == 'app'){
			$('.input-label-isresize, .input-label-isflash').slideDown();
		}else{
			$('.input-label-isresize, .input-label-isflash').slideUp();
		}
	});
	//选择图标
	$('.shortcut-selicon a').click(function(){
		$('.shortcut-addicon').addClass('bgnone').html($(this).html());
		$('#val_icon').val($(this).children('img').attr('valsrc'));
	});
	//提交
	$('a[menu=submit]').click(function(){
		$('#form').submit();
	});
	//添加图标
	$('.shortcut-addicon').click(function(){
		window.parent.art.dialog({
			resize : false,
			lock : true,
		    background : '#ccc',
		    opacity : 0.5,
			title : '设置应用图标',
			content : document.getElementById('shortupload'),
			fixed : true
		});
	});
	$.jUploader.setDefaults({
	    cancelable : true,	//可取消上传
	    allowedExtensions : ['jpg', 'png', 'gif'],	//只允许上传图片
	    messages : {
	        upload : '上传',
	        cancel : '取消',
	        emptyFile : "{file} 为空，请选择一个文件.",
	        invalidExtension : "{file} 后缀名不合法. 只有 {extensions} 是允许的.",
	        onLeave : "文件正在上传，如果你现在离开，上传将会被取消。"
	    }
	});
	$.jUploader({
	    button : 'dtb-msg2',			//这里设置按钮id
	    action : 'index.php?ac=uploadimg',			//这里设置上传处理接口，这个加了参数test_cancel=1来测试取消
	    onComplete : function(fileName, response){			//上传完成事件
	        //response是json对象，格式可以按自己的意愿来定义，例子为： { success: true, fileUrl:'' }
	        if(response.success){
				$('.shortcut-addicon').addClass('bgnone').html('<img src="../../' + response.fileUrl + '" />');
				$('#val_icon').val(response.fileUrl);
				var list = art.dialog.list;
				for(var i in list){
					list[i].close();
				};
	        }else{
	            alert('上传失败');
	        }
	    }
	});
	$.jUploader({
	    button : 'dtb-msg4',			//这里设置按钮id
	    action : 'index.php?ac=uploadimg',			//这里设置上传处理接口，这个加了参数test_cancel=1来测试取消
	    onComplete : function(fileName, response){			//上传完成事件
	        //response是json对象，格式可以按自己的意愿来定义，例子为： { success: true, fileUrl:'' }
	        if(response.success){
				$('.shortcut-addicon').addClass('bgnone').html('<img src="../../' + response.fileUrl + '" />');
				$('#val_icon').val(response.fileUrl);
				var list = art.dialog.list;
				for(var i in list){
					list[i].close();
				};
	        }else{
	            alert('上传失败');
	        }
	    }
	});
	$('#dtb-msg3').on('click', function(){
		art.dialog({
			resize : false,
			lock : true,
		    background : '#ccc',
		    opacity : 0.5,
			title : '设置网络图片地址',
			content : '<input type="text" class="text" id="webshortcuturl" value="http://">',
			ok : function(){
				$('.shortcut-addicon').addClass('bgnone').html('<img src="' + $('#webshortcuturl').val() + '" />');
				$('#val_icon').val($('#webshortcuturl').val());
				var list = art.dialog.list;
				for(var i in list){
					list[i].close();
				};
			},
			cancel : true,
			fixed : true
		});
	});
	//图标上传
	if($.browser.safari || $.browser.mozilla){
		$('#dtb-msg1 .compatible').show();
		$('#dtb-msg1 .notcompatible').hide();
		$('#drop_zone_home').on('mouseover', function(){
			$(this).children('p').stop().animate({
				top : 0
			}, 100);
		}).on('mouseout', function(){
			$(this).children('p').stop().animate({
				top : -44
			}, 100);
		});
		//功能实现
		$(document).on({
			dragleave:function(e){
				e.preventDefault();
				$('.dashboard_target_box').removeClass('over');
			},
			drop:function(e){
				e.preventDefault();
				//$('.dashboard_target_box').removeClass('over');
			},
			dragenter:function(e){
				e.preventDefault();
				$('.dashboard_target_box').addClass('over');
			},
			dragover:function(e){
				e.preventDefault();
				$('.dashboard_target_box').addClass('over');
			}
		});
		var box = document.getElementById('target_box');
		box.addEventListener('drop', function(e){
			e.preventDefault();
			//获取文件列表
			var fileList = e.dataTransfer.files;
			var img = document.createElement('img');
			//检测是否是拖拽文件到页面的操作
			if(fileList.length == 0){
				$('.dashboard_target_box').removeClass('over');
				return;
			}
			//检测文件是不是图片
			if(fileList[0].type.indexOf('image') === -1){
				$('.dashboard_target_box').removeClass('over');
				return;
			}
			
			if($.browser.safari){
				//Chrome8+
				img.src = window.webkitURL.createObjectURL(fileList[0]);
			}else if($.browser.mozilla){
				//FF4+
				img.src = window.URL.createObjectURL(fileList[0]);
			}else{
				//实例化file reader对象
				var reader = new FileReader();
				reader.onload = function(e){
					img.src = this.result;
					$(document.body).appendChild(img);
				}
				reader.readAsDataURL(fileList[0]);
			}
			var xhr = new XMLHttpRequest();
			xhr.open('post', 'index.php?ac=html5uploadimg', true);
			xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
			xhr.upload.addEventListener('progress', function(e){
				$('#dtb-msg3').hide();
				$('#dtb-msg4 span').show();
				$('#dtb-msg4').children('span').eq(1).css({
					width : 0
				});
				$('.show').html('');
				if(e.lengthComputable){
					var loaded = Math.ceil(e.loaded / e.total * 100);
					$('#dtb-msg4').children('span').eq(1).css({
						width : loaded * 2
					});
				}
			}, false);
			xhr.addEventListener('load', function(e){
				$('.dashboard_target_box').removeClass('over');
				$('#dtb-msg3').show();
				$('#dtb-msg4 span').hide();
				var result = jQuery.parseJSON(e.target.responseText);
				$('.shortcut-addicon').addClass('bgnone').html(result.img);
				$('#val_icon').val(result.filename);
				var list = art.dialog.list;
				for(var i in list){
					list[i].close();
				};
			}, false);
			
			var fd = new FormData();
			fd.append('xfile', fileList[0]);
			xhr.send(fd);
		},false);
	}else{
		$('#dtb-msg1 .compatible').hide();
		$('#dtb-msg1 .notcompatible').show();
	}
});
function showRequest(formData, jqForm, options){
	//alert('About to submit: \n\n' + $.param(formData));
	return true;
}
function showResponse(responseText, statusText, xhr, $form){
	//alert('status: ' + statusText + '\n\nresponseText: \n' + responseText + '\n\nThe output div should have already been updated with the responseText.');
	if($('input[name="value_1"]').val() != ''){
		if(responseText == ''){
			art.dialog({
				id : 'ajaxedit',
				content : '修改成功',
				ok : function(){
					art.dialog.list['ajaxedit'].close();
					window.parent.HROS.app.get();
				}
			});
		}
	}else{
		if(responseText == ''){
			art.dialog({
				id : 'ajaxedit',
				content : '添加成功',
				ok : function(){
					art.dialog.list['ajaxedit'].close();
					window.parent.HROS.app.get();
				}
			});
		}
	}
}
</script>
{/literal}
</body>
</html>
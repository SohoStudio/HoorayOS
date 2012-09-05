<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>权限管理</title>
{include file="../global_css.tpl"}
<link rel="stylesheet" href="../../img/ui/sys.css">
</head>

<body>
<form action="../permission/detail.php" method="post" name="form" id="form">
<input type="hidden" name="ac" value="ajaxEdit">
<input type="hidden" name="id" value="{$permission.tbid}">
<div class="creatbox">
	<div class="middle">
		<p class="detile-title">
			<strong>编辑权限</strong>
		</p>
		<div class="input-label">
			<label class="label-text">权限名称：</label>
			<div class="label-box">
				{if $permission.tbid != ''}
				{$permission.name}
				{else}
				<input type="text" name="val_name">
				{/if}
			</div>
		</div>
		<div class="input-label">
			<label class="label-text">专属应用：</label>
			<div class="label-box">
				<div class="permissions_apps">
					{foreach from=$permission.appsinfo item=apps}
					<div class="app" appid="{$apps.tbid}">
						<img src="../../{$apps.icon}" alt="{$apps.name}" title="{$apps.name}">
						<span class="del">删</span>
					</div>
					{/foreach}
				</div>
				<a class="btn btn-mini" href="javascript:;" menu="addapps">添加应用</a>
				<input type="hidden" name="val_apps_id" id="val_apps_id" value="{$permission.apps_id}">
			</div>
		</div>
	</div>
</div>
<div class="bottom-bar">
	<div class="con">
		<a class="btn btn-large btn-primary fr" menu="submit" href="javascript:;"><i class="icon-white icon-ok"></i> 确定</a>
		<a class="btn btn-large" menu="back" href="index.php"><i class="icon-arrow-left"></i> 返回权限列表</a>
	</div>
</div>
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
	//提交
	$('a[menu=submit]').click(function(){
		$('#form').submit();
	});
	//添加应用
	$('a[menu=addapps]').click(function(){
		$.dialog.data('appsid', $('#val_apps_id').val());
		$.dialog.open('sysapp/permission/alert_addapps.php', {
			id : 'alert_addapps',
			title : '添加应用',
			resize: false,
			width : 350,
			height : 300,
			ok : function(){
				$('#val_apps_id').val($.dialog.data('appsid'));
				updateApps($.dialog.data('appsid'));
			},
			cancel : true
		});
	});
	//删除应用
	$('.permissions_apps').on('click','.app .del',function(){
		var appid = $(this).parent().attr('appid');
		var appsid = $('#val_apps_id').val().split(',');
		var newappsid = [];
		for(var i=0, j=0; i<appsid.length; i++){
			if(appsid[i] != appid){
				newappsid[j] = appsid[i];
				j++;
			}
		}
		$('#val_apps_id').val(newappsid.join(','));
		$(this).parent().remove();
	});
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
				}
			});
		}
	}
}
function updateApps(appsid){
	$.ajax({
		type : 'POST',
		url : 'detail.php',
		data : 'ac=updateApps&appsid=' + appsid,
		success : function(msg){
			$('.permissions_apps').html(msg);
		}
	});
}
</script>
{/literal}
</body>
</html>
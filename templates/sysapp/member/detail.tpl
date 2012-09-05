<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>用户管理</title>
{include file="../global_css.tpl"}
<link rel="stylesheet" href="../../img/ui/sys.css">
</head>

<body>
<form action="detail.php" method="post" name="form" id="form">
<input type="hidden" name="ac" value="ajaxEdit">
<input type="hidden" name="id" value="{$member.tbid}">
<div class="creatbox">
	<div class="middle">
		<p class="detile-title">
			<strong>编辑用户</strong>
		</p>
		<div class="input-label">
			<label class="label-text">用户名：</label>
			<div class="label-box">
				{if $member.tbid != ''}
				{$member.username}
				{else}
				<input type="text" name="val_username">
				{/if}
			</div>
		</div>
		<div class="input-label">
			<label class="label-text">用户密码：</label>
			<div class="label-box">
				<input type="text" name="val_password">
				{if $member.tbid != ''}
				<span class="txt">（如果无需修改则不填）</span>
				{/if}
			</div>
		</div>
		<div class="input-label">
			<label class="label-text">用户类型：</label>
			<div class="label-box form-inline">
				<label class="radio" style="margin-right:10px"><input type="radio" name="val_type" value="0" {if $member.type == 0 || $member.tbid == ''}checked{/if}>普通会员</label>
				<label class="radio"><input type="radio" name="val_type" value="1" {if $member.type == 1}checked{/if}>管理员</label>
			</div>
		</div>
		<div class="input-label input-label-permission {if $member.type == 0}disn{/if}">
			<label class="label-text">用户权限：</label>
			<div class="label-box form-inline">
				{foreach from=$permission key=k item=p}
				<label class="checkbox" style="margin-right:10px"><input type="checkbox" name="val_permission_id" value="{$p.tbid}" {if $member.permission_id == $p.tbid}checked{/if}>{$p.name}</label>
				{/foreach}
				<span class="txt">[<a href="javascript:;" class="tip blue" title="权限最多只能选一项">?</a>]</span>
			</div>
		</div>
	</div>
</div>
<div class="bottom-bar">
	<div class="con">
		<a class="btn btn-large btn-primary fr" menu="submit" href="javascript:;"><i class="icon-white icon-ok"></i> 确定</a>
		<a class="btn btn-large" menu="back" href="index.php"><i class="icon-arrow-left"></i> 返回用户列表</a>
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
	$('input[name="val_type"]').change(function(){
		if($(this).val() == 1){
			$('.input-label-permission').slideDown();
		}else{
			$('.input-label-permission').slideUp();
		}
	});
	checkboxMax();
	$('input[name="val_permission_id"]').change(function(){
		checkboxMax();
	});
	//提交
	$('a[menu=submit]').click(function(){
		$('#form').submit();
	});
});
function checkboxMax(){
	if($('input[name="val_permission_id"]').filter(':checked').length >= 1){
		$('input[name="val_permission_id"]').not(':checked').each(function(){
			$(this).attr('disabled',true);
		});
	}else{
		$('input[name="val_permission_id"]').not(':checked').each(function(){
			$(this).attr('disabled',false);
		});
	}
}
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
</script>
{/literal}
</body>
</html>

<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>网站设置</title>
{include file="../global_css.tpl"}
<link rel="stylesheet" href="../../img/ui/sys.css">
</head>

<body>
	<form action="index.php" method="post" name="form" id="form">
	<input type="hidden" name="ac" value="ajaxEdit">
	<div class="title">网站设置</div>
	<div class="input-label">
		<div class="label-text">网站标题：</div>
		<div class="label-box">
			<input type="text" name="val_title" style="width:250px" value="{$setinfo.title}">
		</div>
	</div>
	<div class="input-label">
		<div class="label-text">SEO关键词：</div>
		<div class="label-box">
			<input type="text" name="val_keywords" style="width:250px" value="{$setinfo.keywords}">
			<p class="help-inline">（推荐写法：“关键词1,关键词2,关键词3”，必须为英文逗号，不超过100字符）</p>
		</div>
	</div>
	<div class="input-label">
		<div class="label-text">SEO描述标签：</div>
		<div class="label-box">
			<input type="text" name="val_description" style="width:250px" value="{$setinfo.description}">
			<p class="help-inline">（推荐写法：尽量把关键词重复2-3次）</p>
		</div>
	</div>
	<div class="input-label" style="background:none;padding-left:0;text-align:center;">
		<a class="btn set" href="javascript:;">应用</a>
	</div>
	</form>
	<div class="title">检查更新</div>
	<div class="input-label">
		<div class="label-text">当前版本：</div>
		<div class="label-box">
			<span class="version"></span>
			<a class="btn btn-mini check-version" href="javascript:;">检查更新</a>
		</div>
	</div>
	<div class="title">关于HoorayOS开源桌面应用框架</div>
	<div class="input-label">
		<div class="label-text">作者：</div>
		<div class="label-box">
			<span class="txt">胡尐睿丶</span>
		</div>
	</div>
	<div class="input-label">
		<div class="label-text">联系：</div>
		<div class="label-box">
			<span class="txt">
				<a href="http://weibo.com/318577790" target="_blank">@可惜我是胡尐睿丶</a>
			</span>
		</div>
	</div>
	<div class="input-label">
		<div class="label-text">项目地址：</div>
		<div class="label-box">
			<span class="txt"><a href="http://code.google.com/p/hoorayos" target="_blank">http://code.google.com/p/hoorayos</a></span>
		</div>
	</div>
	<div class="input-label">
		<div class="label-text">资助：</div>
		<div class="label-box">
			<span class="txt">HoorayOS是否就是你一直想要的web桌面么？那么我非常期待您能够热情的提供<font style="color:red;font-size:14px;font-weight:bold;margin:0 4px">35元</font>或者其他金额的捐赠鼓励，正如您支持其他开源项目一样。支付宝：<a href="https://me.alipay.com/hooray" target="_blank">https://me.alipay.com/hooray</a>。捐赠时请在付款说明里留下QQ或者邮箱地址。</span>
		</div>
	</div>
	<div class="input-label">
		<div class="label-text">交流QQ群：</div>
		<div class="label-box">
			<span class="txt">213804727</span>
		</div>
	</div>
	<div class="input-label">
		<div class="label-text">版权申明：</div>
		<div class="label-box">
			<span class="txt">HoorayOS仅供学习交流，禁止商业用途，版权归作者所有，未经作者同意下，不得删除代码中作者信息。</span>
		</div>
	</div>
	<div class="input-label">
		<div class="label-text">授权：</div>
		<div class="label-box">
			<span class="txt">
				授权网站或公司可将HoorayOS作为商用，并享受授权特权。<br>
				网站授权协议下载：<a href="http://code.google.com/p/hoorayos/downloads/detail?name=HoorayOS商业授权协议%20-%20网站.doc&amp;can=2&amp;q=" rel="nofollow">HoorayOS 2.0 商业授权协议 - 网站</a><br>
				公司授权协议下载：<a href="http://code.google.com/p/hoorayos/downloads/detail?name=HoorayOS%E5%95%86%E4%B8%9A%E6%8E%88%E6%9D%83%E5%8D%8F%E8%AE%AE%20-%20%E5%85%AC%E5%8F%B8.doc&amp;can=2&amp;q=" rel="nofollow">HoorayOS 2.0 商业授权协议 - 公司</a><br>
				联系QQ：304327508
			</span>
		</div>
	</div>
	<div class="title">资助名单（排名不分先后）</div>
	<div class="donate">
		<div class="input-label" style="background:none;padding-left:0;text-align:center">
			<a class="btn get-donate-list" href="javascript:;">查看</a>
		</div>
	</div>
{include file="../global_js.tpl"}
{literal}
<script>
$(function(){
	$('.version').text(window.parent.version);
	var options = {
		beforeSubmit : showRequest,
		success : showResponse,
		type : 'POST'
	}; 
	$('#form').ajaxForm(options); 
	$('.set').click(function(){
		$('#form').submit();
	});
	$('.get-donate-list').click(function(){
		$.ajax({
			url : 'index.php',
			data : 'ac=getDonateList',
			success : function(donate_html){
				if(donate_html == 0){
					art.dialog({
						icon : 'face-sad',
						time : 2,
						content : '系统繁忙，请稍后再试'
					});
				}else{
					$('.donate').html(donate_html);
				}
			}
		});
	});
	$('.check-version').click(function(){
		$.ajax({
			type : 'POST',
			url : 'index.php',
			data : 'ac=checkVersion&version=' + window.parent.version,
			success : function(version){
				if(version == 0){
					art.dialog({
						icon : 'face-sad',
						time : 2,
						content : '系统繁忙，请稍后再试'
					});
				}else if(version == 1){
					art.dialog({
						icon : 'face-smile',
						time : 2,
						content : '当前版本已经是最新版，无需更新'
					});
				}else{
					art.dialog({
						icon : 'warning',
						content : '有新版本，下载地址：<a href="' + version + '" target="_blank">' + version + '</a>'
					});
				}
			}
		});
	});
});
function showRequest(formData, jqForm, options){
	//alert('About to submit: \n\n' + $.param(formData));
	return true;
}
function showResponse(responseText, statusText, xhr, $form){
	//alert('status: ' + statusText + '\n\nresponseText: \n' + responseText + '\n\nThe output div should have already been updated with the responseText.');
	art.dialog({
		time : 1,
		content : '设置已保存，页面刷新后生效'
	});
}
</script>
{/literal}
</body>
</html>
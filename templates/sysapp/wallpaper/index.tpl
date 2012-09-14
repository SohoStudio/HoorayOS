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
		<div class="btn-group fl">
			<a class="btn disabled">系统壁纸</a><a class="btn" href="custom.php">自定义</a>
		</div>
		<div class="fr">
			<label>显示方式：</label>
			<select name="wallpapertype" id="wallpapertype" style="width:100px">
				<option value="tianchong"{if $wallpaperType=='tianchong'} selected{/if}>填充</option>
				<option value="shiying"{if $wallpaperType=='shiying'} selected{/if}>适应</option>
				<option value="pingpu"{if $wallpaperType=='pingpu'} selected{/if}>平铺</option>
				<option value="lashen"{if $wallpaperType=='lashen'} selected{/if}>拉伸</option>
				<option value="juzhong"{if $wallpaperType=='juzhong'} selected{/if}>居中</option>
			</select>
		</div>
	</div>
	<ul class="wallpaper">
		{foreach from=$wallpaperList key=k item=wp}
		<li{if $k%3==2} class="three"{/if} wpid="{$wp.tbid}">
			<img src="../../{$wp.s_url}">
			<div>{$wp.title}</div>
		</li>
		{/foreach}
	</ul>
{include file="../global_js.tpl"}
{literal}
<script>
$(function(){
	$("#wallpapertype").on('change',function(){
		window.parent.HROS.wallpaper.update(1, $('#wallpapertype').val(),'');
	});
	$('.wallpaper li').on('click',function(){
		window.parent.HROS.wallpaper.update(1, $('#wallpapertype').val(),$(this).attr('wpid'));
	});
});
</script>
{/literal}
</body>
</html>
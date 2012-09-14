<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<!-- IE=edge告诉IE使用最新的引擎渲染网页，chrome=1则可以激活Chrome Frame -->
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>{$setting.title}</title>
<meta name="description" content="{$setting.description}" />
<meta name="keywords" content="{$setting.keywords}" />
<link rel="stylesheet" href="js/HoorayLibs/hooraylibs.css">
<link rel="stylesheet" href="img/ui/index.css?v=120615">
<link rel="stylesheet" href="img/skins/{$skin}.css" id="window-skin">
</head>

<body>
<div class="loading"></div>
<!-- 桌面 -->
<div id="desktop">
	<div id="zoom-tip"><div><i>​</i>​<span></span></div><a href="javascript:;" class="close" onClick="HROS.zoom.close();">×</a></div>
	<div id="desk">
		<div id="desk-1" class="desktop-container"><div class="scrollbar scrollbar-x"></div><div class="scrollbar scrollbar-y"></div></div>
		<div id="desk-2" class="desktop-container"><div class="scrollbar scrollbar-x"></div><div class="scrollbar scrollbar-y"></div></div>
		<div id="desk-3" class="desktop-container"><div class="scrollbar scrollbar-x"></div><div class="scrollbar scrollbar-y"></div></div>
		<div id="desk-4" class="desktop-container"><div class="scrollbar scrollbar-x"></div><div class="scrollbar scrollbar-y"></div></div>
		<div id="desk-5" class="desktop-container"><div class="scrollbar scrollbar-x"></div><div class="scrollbar scrollbar-y"></div></div>
		<div id="dock-bar">
			<div id="dock-container">
				<div class="dock-middle">
					<div class="dock-applist"></div>
					<div class="dock-toollist">
						<a href="javascript:;" class="dock-tool-setting" title="桌面设置"></a>
						<a href="javascript:;" class="dock-tool-style" title="主题设置"></a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="task-bar-bg1"></div>
	<div id="task-bar-bg2"></div>
	<div id="task-bar">
		<div id="task-next"><a href="javascript:;" id="task-next-btn" hidefocus="true"></a></div>
		<div id="task-content">
			<div id="task-content-inner"></div>
		</div>
		<div id="task-pre"><a href="javascript:;" id="task-pre-btn" hidefocus="true"></a></div>
	</div>
	<div id="nav-bar">
		<div class="nav-wrapper">
			<div class="nav-container nav-current-1" id="navContainer">
				<div class="indicator indicator-header" id="navbarHeaderImg"><img src="img/ui/avatar.png" class="indicator-header-img"></div>
				<a class="indicator indicator-1" href="javascript:;" index="1" title="桌面1">
					<span class="indicator-icon-bg"></span>
					<span class="indicator-icon indicator-icon-1">1</span>
				</a>
				<a class="indicator indicator-2" href="javascript:;" index="2" title="桌面2">
					<span class="indicator-icon-bg"></span>
					<span class="indicator-icon indicator-icon-2">2</span>
				</a>
				<a class="indicator indicator-3" href="javascript:;" index="3" title="桌面3">
					<span class="indicator-icon-bg"></span>
					<span class="indicator-icon indicator-icon-3">3</span>
				</a>
				<a class="indicator indicator-4" href="javascript:;" index="4" title="桌面4">
					<span class="indicator-icon-bg"></span>
					<span class="indicator-icon indicator-icon-4">4</span>
				</a>
				<a class="indicator indicator-5" href="javascript:;" index="5" title="桌面5">
					<span class="indicator-icon-bg"></span>
					<span class="indicator-icon indicator-icon-5">5</span>
				</a>
				<a class="indicator indicator-manage" href="javascript:;" title="全局视图"></a>
			</div>
		</div>
	</div>
</div>
<!-- 全局视图 -->
<div id="appmanage">
	<a class="amg_close" href="javascript:;"></a>
	<div id="amg_dock_container"></div>
	<div class="amg_line_x"></div>
	<div id="amg_folder_container">
		<div class="folderItem">
			<div class="folder_bg folder_bg1"></div>
			<div class="folderOuter">
				<div class="folderInner"></div>
				<div class="scrollBar"></div>
			</div>
		</div>
		<div class="folderItem">
			<div class="folder_bg folder_bg2"></div>
			<div class="folderOuter">
				<div class="folderInner"></div>
				<div class="scrollBar"></div>
			</div>
			<div class="amg_line_y"></div>
		</div>
		<div class="folderItem">
			<div class="folder_bg folder_bg3"></div>
			<div class="folderOuter">
				<div class="folderInner"></div>
				<div class="scrollBar"></div>
			</div>
			<div class="amg_line_y"></div>
		</div>
		<div class="folderItem">
			<div class="folder_bg folder_bg4"></div>
			<div class="folderOuter">
				<div class="folderInner"></div>
				<div class="scrollBar"></div>
			</div>
			<div class="amg_line_y"></div>
		</div>
		<div class="folderItem">
			<div class="folder_bg folder_bg5"></div>
			<div class="folderOuter">
				<div class="folderInner"></div>
				<div class="scrollBar"></div>
			</div>
			<div class="amg_line_y"></div>
		</div>
	</div>
</div>
<script src="js/jquery-1.8.1.min.js"></script>
<script src="js/HoorayLibs/hooraylibs.js"></script>
<script src="js/templates.js"></script>
<script src="js/util.js"></script>
<script src="js/core.js?v=120615"></script>
<script src="js/hros.app.js"></script>
<script src="js/hros.appmanage.js"></script>
<script src="js/hros.base.js"></script>
<script src="js/hros.desktop.js"></script>
<script src="js/hros.dock.js"></script>
<script src="js/hros.folderView.js"></script>
<script src="js/hros.grid.js"></script>
<script src="js/hros.maskBox.js"></script>
<script src="js/hros.navbar.js"></script>
<script src="js/hros.popupMenu.js"></script>
<script src="js/hros.taskbar.js"></script>
<script src="js/hros.uploadFile.js"></script>
<script src="js/hros.wallpaper.js"></script>
<script src="js/hros.widget.js"></script>
<script src="js/hros.window.js"></script>
<script src="js/hros.zoom.js"></script>
<script src="js/artDialog4.1.6/jquery.artDialog.js?skin=default"></script>
{literal}
<script>
$().ready(function(){
	//IE下禁止选中
	document.body.onselectstart = document.body.ondrag = function(){return false;}
	
	$('.loading').hide();
	$('#desktop').show();
	//初始化
	HROS.base.init();
	//误删
//	art.dialog({
//		title: '欢迎使用HoorayOS',
//		icon: 'face-smile',
//		width: 320,
//		content: 'HoorayOS是否就是你一直想要的web桌面么？<br>' + '那么我非常期待您能够热情的提供<font style="color:red">35元</font>或者其他金额的捐赠鼓励，正如您支持其他开源项目一样。<br>' + '支付宝：<a href="https://me.alipay.com/hooray" style="color:#214FA3" target="_blank">https://me.alipay.com/hooray</a><div style="width:100%;height:0px;font-size:0;border-bottom:1px solid #ccc"></div>如果你对本框架感兴趣，欢迎加入讨论群：<br>213804727' + '<div style="width:100%;height:0px;font-size:0;border-bottom:1px solid #ccc"></div>' +
//				'HoorayOS仅供学习交流，禁止商业用途，版权归作者所有，未经作者同意下，不得删除代码中作者信息。若需要商业使用，请联系QQ：304327508 进行授权'
//	});
});
</script>
{/literal}
</body>
</html>
var Util = {};

Util.addBookmark = function(title, url){
	if(window.sidebar){
		window.sidebar.addPanel(title, url, '');
	}else{
		if(document.all){
			window.external.AddFavorite(url, title);
		}else{
			alert('您的浏览器不支持自动加入收藏夹，请使用浏览器菜单手动加入');
		}
	}
};
Util.setHome = function(ele){
	if(document.all){
		ele.style.behavior = 'url(#default#homepage)';
		ele.setHomePage(window.location.href);
	}else{
		alert('您的浏览器不支持自动设置主页，请使用浏览器菜单手动设置');
	}
};
Util.confirmExit = function(){
	return '你确定要离开当前页面么？';
};
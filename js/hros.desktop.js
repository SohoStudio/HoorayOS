/*
**  桌面
*/
HROS.deskTop = (function(){
	return {
		/*
		**  处理浏览器改变大小后的事件
		*/
		resize : function(time){
			//使用doTimeout插件，防止出现resize两次的bug
			$.doTimeout('resize', time, function(){
				console.log(1);
				if($('#desktop').css('display') !== 'none'){
					//更新码头位置
					HROS.dock.setPos();
					//更新图标定位
					HROS.deskTop.appresize();
					//更新窗口定位
					HROS.deskTop.windowresize();
					//更新滚动条
					HROS.app.getScrollbar();
				}else{
					HROS.appmanage.resize();
				}
				HROS.wallpaper.set(false);
			});
		},
		/*
		**  重新排列图标
		*/
		appresize : function(){
			var grid = HROS.grid.getAppGrid(), dockGrid = HROS.grid.getDockAppGrid();
			$('#dock-bar .dock-applist li').each(function(i){
				$(this).animate({
					'left' : dockGrid[i]['startX'],
					'top' : dockGrid[i]['startY']
				}, 500);
			});
			for(var j = 1; j <= 5; j++){
				$('#desk-' + j + ' li').each(function(i){
					$(this).animate({
						'left' : grid[i]['startX'] + 16,
						'top' : grid[i]['startY'] + 7
					}, 500);
				});
			}
		},
		/*
		**  重新定位窗口位置
		*/
		windowresize : function(){
			$('#desk div.window-container').each(function(){
				var windowdata = $(this).data('info');
				currentW = $(window).width() - $(this).width();
				currentH = $(window).height() - $(this).height();
				_l = windowdata['left'] / windowdata['emptyW'] * currentW >= currentW ? currentW : windowdata['left'] / windowdata['emptyW'] * currentW;
				_l = _l <= 0 ? 0 : _l;
				_t = windowdata['top'] / windowdata['emptyH'] * currentH >= currentH ? currentH : windowdata['top'] / windowdata['emptyH'] * currentH;
				_t = _t <= 0 ? 0 : _t;
				$(this).animate({
					'left' : _l,
					'top' : _t
				}, 500);
			});
		}
	}
})();
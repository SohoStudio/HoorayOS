/*
**  图标布局格子
**  这篇文章里有简单说明格子的作用
**  http://www.cnblogs.com/hooray/archive/2012/03/23/2414410.html
*/
HROS.grid = (function(){
	return {
		getAppGrid : function(){
			var width, height;
			width = $('#desk-' + HROS.CONFIG.desk).width() - HROS.CONFIG.appButtonLeft;
			height = $('#desk-' + HROS.CONFIG.desk).height() - HROS.CONFIG.appButtonTop;
			var appGrid = [], _top = HROS.CONFIG.appButtonTop, _left = HROS.CONFIG.appButtonLeft;
			for(var i = 0; i < 10000; i++){
				appGrid.push({
					startY : _top,
					endY : _top + 100,
					startX : _left,
					endX : _left + 120
				});
				if(HROS.CONFIG.appXY == 'x'){
					_left += 120;
					if(_left + 100 > width){
						_top += 100;
						_left = HROS.CONFIG.appButtonLeft;
					}
				}else{
					_top += 100;
					if(_top + 70 > height){
						_top = HROS.CONFIG.appButtonTop;
						_left += 120;
					}
				}
			}
			return appGrid;
		},
		searchAppGrid : function(x, y){
			var grid = HROS.grid.getAppGrid(), j = grid.length;
			var flags = 0, appLength = $('#desk-' + HROS.CONFIG.desk + ' li.appbtn:not(.add)').length - 1;
			for(var i = 0; i < j; i++){
				if(x >= grid[i].startX && x <= grid[i].endX){
					flags += 1;
				}
				if(y >= grid[i].startY && y <= grid[i].endY){
					flags += 1;
				}
				if(flags === 2){
					return i > appLength ? appLength : i;
				}else{
					flags = 0;
				}
			}
			return null;
		},
		getDockAppGrid : function(){
			var height = $('#dock-bar .dock-applist').height();
			var dockAppGrid = [], _left = 0, _top = 0;
			for(var i = 0; i < 7; i++){
				dockAppGrid.push({
					startY : _top,
					endY : _top + 62,
					startX : _left,
					endX : _left + 62
				});
				_top += 62;
				if(_top + 62 > height){
					_top = 0;
					_left += 62;
				}
			}
			return dockAppGrid;
		},
		searchDockAppGrid : function(x, y){
			var grid = HROS.grid.getDockAppGrid(), j = grid.length, flags = 0,
				appLength = $('#dock-bar .dock-applist li').length - 1;
			for(var i = 0; i < j; i++){
				if(x >= grid[i].startX && x <= grid[i].endX){
					flags += 1;
				}
				if(y >= grid[i].startY && y <= grid[i].endY){
					flags += 1;
				}
				if(flags === 2){
					return i > appLength ? appLength : i;
				}else{
					flags = 0;
				}
			}
			return null;
		},
		getFolderGrid : function(){
			var folderGrid = [];
			$('.folder-window:visible').each(function(){
				folderGrid.push({
					zIndex : $(this).css('z-index'),
					id : $(this).attr('realid'),
					startY : $(this).offset().top,
					endY : $(this).offset().top + $(this).height(),
					startX :  $(this).offset().left,
					endX :  $(this).offset().left +  $(this).width()
				});
			});
			folderGrid.sort(function(x, y){
				return y['zIndex'] - x['zIndex'];
			});
			return folderGrid;
		},
		searchFolderGrid : function(x, y){
			var folderGrid = HROS.grid.getFolderGrid(), j = folderGrid.length, flags = 0;
			for(var i = 0; i < j; i++){
				if(x >= folderGrid[i].startX && x <= folderGrid[i].endX){
					flags += 1;
				}
				if(y >= folderGrid[i].startY && y <= folderGrid[i].endY){
					flags += 1;
				}
				if(flags === 2){
					return folderGrid[i]['id'];
				}else{
					flags = 0;
				}
			}
			return null;
		},
		getManageDockAppGrid : function(){
			var manageDockAppGrid = [], _left = 20;
			for(var i = 0; i < 7; i++){
				manageDockAppGrid.push({
					startX : _left,
					endX : _left + 72
				});
				_left += 72;
			}
			return manageDockAppGrid;
		},
		searchManageDockAppGrid : function(x){
			var grid = HROS.grid.getManageDockAppGrid(), j = grid.length, flags = 0,
				appLength = $('#amg_dock_container li').length - 1;
			for(var i = 0; i < j; i++){
				if(x >= grid[i].startX && x <= grid[i].endX){
					flags += 1;
				}
				if(flags === 1){
					return i > appLength ? appLength : i;
				}else{
					flags = 0;
				}
			}
			return null;
		},
		getManageAppGrid : function(){
			var manageAppGrid = [], _top = 0;
			for(var i = 0; i < 10000; i++){
				manageAppGrid.push({
					startY : _top,
					endY : _top + 40
				});
				_top += 40;
			}
			return manageAppGrid;
		},
		searchManageAppGrid : function(y, desk){
			var grid = HROS.grid.getManageAppGrid(), j = grid.length, flags = 0,
				appLength = $('#amg_folder_container .folderItem:eq('+desk+') .folderInner li').length - 1;
			for(var i = 0; i < j; i++){
				if(y >= grid[i].startY && y <= grid[i].endY){
					flags += 1;
				}
				if(flags === 1){
					return i > appLength ? appLength : i;
				}else{
					flags = 0;
				}
			}
			return null;
		}
	}
})();
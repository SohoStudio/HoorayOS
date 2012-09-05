/*
**  图标
*/
HROS.app = (function(){
	return {
		/*
		**  获得图标排列方式，x横向排列，y纵向排列
		*/
		getXY : function(func){
			$.ajax({
				type : 'POST',
				url : ajaxUrl,
				data : 'ac=getAppXY'
			}).done(function(i){
				HROS.CONFIG.appXY = i;
				if(typeof(func) == 'function'){
					func();
				}
			});
		},
		/*
		**  更新图标排列方式
		*/
		updateXY : function(i, func){
			$.ajax({
				type : 'POST',
				url : ajaxUrl,
				data : 'ac=setAppXY&appxy=' + i
			}).done(function(){
				HROS.CONFIG.appXY = i;
				if(typeof(func) == 'function'){
					func();
				}
			});
		},
		/*
		**  获取图标
		*/
		get : function(){
			//绘制图标表格
			var grid = HROS.grid.getAppGrid(), dockGrid = HROS.grid.getDockAppGrid();
			//获取json数组并循环输出每个图标
			$.getJSON(ajaxUrl + '?ac=getMyApp', function(sc){
				//加载应用码头图标
				if(sc['dock'] != null){
					var dock_append = '', temp = {};
					for(var i = 0; i < sc['dock'].length; i++){
						dock_append += appbtnTemp({
							'top' : dockGrid[i]['startY'],
							'left' : dockGrid[i]['startX'],
							'title' : sc['dock'][i]['name'],
							'type' : sc['dock'][i]['type'],
							'id' : 'd_' + sc['dock'][i]['type'] + '_' + sc['dock'][i]['id'],
							'realid' : sc['dock'][i]['id'],
							'imgsrc' : sc['dock'][i]['icon']
						});
					}
					$('#dock-bar .dock-applist').html('').append(dock_append);
				}
				//加载桌面图标
				for(var j = 1; j <= 5; j++){
					var desk_append = '', temp = {};
					if(sc['desk' + j] != null){
						for(var i = 0; i < sc['desk' + j].length; i++){
							desk_append += appbtnTemp({
								'top' : grid[i]['startY'] + 7,
								'left' : grid[i]['startX'] + 16,
								'title' : sc['desk' + j][i]['name'],
								'type' : sc['desk' + j][i]['type'],
								'id' : 'd_' + sc['desk' + j][i]['type'] + '_' + sc['desk' + j][i]['id'],
								'realid' : sc['desk' + j][i]['id'],
								'imgsrc' : sc['desk' + j][i]['icon']
							});
						}
					}
					desk_append += addbtnTemp({
						'top' : grid[i]['startY'] + 7,
						'left' : grid[i]['startX'] + 16
					});
					$('#desk-' + j + ' li').remove();
					$('#desk-' + j).append(desk_append);
					i = 0;
				}
				//绑定'应用市场'图标点击事件
				$('#desk').off('click').on('click', 'li.add', function(){
					HROS.window.createTemp({
						id : 'yysc',
						title : '应用市场',
						url : 'sysapp/appmarket/index.php',
						width : 800,
						height : 484,
						isresize : false,
						isflash : false
					});
				});
				//绑定图标拖动事件
				HROS.app.move();
				//绑定应用码头拖动事件
				HROS.dock.move();
				//加载滚动条
				HROS.app.getScrollbar();
				//绑定滚动条拖动事件
				HROS.app.moveScrollbar();
				//绑定图标右击事件
				$('#desk').on('contextmenu', '.appbtn:not(.add)', function(e){
					$('.popup-menu').hide();
					$('.quick_view_container').remove();
					switch($(this).attr('type')){
						case 'app':
						case 'widget':
							var popupmenu = HROS.popupMenu.app($(this));
							break;
						case 'papp':
						case 'pwidget':
							var popupmenu = HROS.popupMenu.papp($(this));
							break;
						case 'folder':
							var popupmenu = HROS.popupMenu.folder($(this));
							break;
					}
					var l = ($(document).width() - e.clientX) < popupmenu.width() ? (e.clientX - popupmenu.width()) : e.clientX;
					var t = ($(document).height() - e.clientY) < popupmenu.height() ? (e.clientY - popupmenu.height()) : e.clientY;
					popupmenu.css({
						left : l,
						top : t
					}).show();
					return false;
				});
			});
		},
		/*
		**  添加应用
		*/
		add : function(id, type, fun){
			$.ajax({
				type : 'POST',
				url : ajaxUrl,
				data : 'ac=addMyApp&id=' + id  + '&type=' + type + '&desk=' + HROS.CONFIG.desk,
				success : function(){
					if(typeof(fun) !== 'undefined'){
						fun();
					}
				}
			}); 
		},
		/*
		**  删除应用
		*/
		remove : function(id, type, fun){
			$.ajax({
				type : 'POST',
				url : ajaxUrl,
				data : 'ac=delMyApp&id=' + id + '&type=' + type,
				success : function(){
					if(type == 'widget'){
						HROS.widget.removeCookie(id, type);
					}
					if(typeof(fun) !== 'undefined'){
						fun();
					}
				}
			});
		},
		/*
		**  图标拖动、打开
		**  这块代码略多，主要处理了9种情况下的拖动，分别是：
		**  桌面拖动到应用码头、桌面拖动到文件夹内、当前桌面上拖动(排序)
		**  应用码头拖动到桌面、应用码头拖动到文件夹内、应用码头上拖动(排序)
		**  文件夹内拖动到桌面、文件夹内拖动到应用码头、不同文件夹之间拖动
		*/
		move : function(){
			//应用码头图标拖动
			$('#dock-bar .dock-applist').off('mousedown', 'li').on('mousedown', 'li', function(e){
				e.preventDefault();
				e.stopPropagation();
				if(e.button == 0 || e.button == 1){
					var oldobj = $(this), x, y, cx, cy, dx, dy, lay, obj = $('<li id="shortcut_shadow">' + oldobj.html() + '</li>');
					dx = cx = e.clientX;
					dy = cy = e.clientY;
					x = dx - oldobj.offset().left;
					y = dy - oldobj.offset().top;
					//绑定鼠标移动事件
					$(document).on('mousemove', function(e){
						$('body').append(obj);
						lay = HROS.maskBox.desk();
						lay.show();
						cx = e.clientX <= 0 ? 0 : e.clientX >= $(document).width() ? $(document).width() : e.clientX;
						cy = e.clientY <= 0 ? 0 : e.clientY >= $(document).height() ? $(document).height() : e.clientY;
						_l = cx - x;
						_t = cy - y;
						if(dx != cx || dy != cy){
							obj.css({
								left : _l,
								top : _t
							}).show();
						}
					}).on('mouseup', function(){
						$(document).off('mousemove').off('mouseup');
						obj.remove();
						if(typeof(lay) !== 'undefined'){
							lay.hide();
						}
						//判断是否移动图标，如果没有则判断为click事件
						if(dx == cx && dy == cy){
							switch(oldobj.attr('type')){
								case 'app':
								case 'papp':
									HROS.window.create(oldobj.attr('realid'), oldobj.attr('type'));
									break;
								case 'widget':
								case 'pwidget':
									HROS.widget.create(oldobj.attr('realid'), oldobj.attr('type'));
									break;
								case 'folder':
									HROS.folderView.init(oldobj);
									break;
							}
							return false;
						}
						var folderId = HROS.grid.searchFolderGrid(cx, cy);
						if(folderId != null){
							if(oldobj.hasClass('folder') == false){
								$.ajax({
									type : 'POST',
									url : ajaxUrl,
									data : 'ac=updateMyApp&movetype=dock-folder&id=' + oldobj.attr('realid') + '&type=' + oldobj.attr('type') + '&from=' + oldobj.index() + '&to=' + folderId + '&desk=' + HROS.CONFIG.desk,
									success : function(){
										oldobj.remove();
										HROS.deskTop.appresize();
										//如果文件夹预览面板为显示状态，则进行更新
										if($('#qv_' + folderId).length != 0){
											HROS.folderView.init($('#d_folder_' + folderId));
										}
										//如果文件夹窗口为显示状态，则进行更新
										if($('#w_folder_' + folderId).length != 0){
											HROS.window.updateFolder(folderId, 'folder');
										}
									}
								});
							}
						}else{
							var icon, icon2;
							var iconIndex = $('#desk-' + HROS.CONFIG.desk + ' li.appbtn:not(.add)').length == 0 ? -1 : $('#desk-' + HROS.CONFIG.desk + ' li').index(oldobj);
							var iconIndex2 = $('#dock-bar .dock-applist').html() == '' ? -1 : $('#dock-bar .dock-applist li').index(oldobj);
							
							var dock_w2 = HROS.CONFIG.dockPos == 'left' ? 0 : HROS.CONFIG.dockPos == 'top' ? ($(window).width() - $('#dock-bar .dock-applist').width() - 20) / 2 : $(window).width() - $('#dock-bar .dock-applist').width();
							var dock_h2 = HROS.CONFIG.dockPos == 'top' ? 0 : ($(window).height() - $('#dock-bar .dock-applist').height() - 20) / 2;
							icon2 = HROS.grid.searchDockAppGrid(cx - dock_w2, cy - dock_h2);
							if(icon2 != null && icon2 != oldobj.index()){
								$.ajax({
									type : 'POST',
									url : ajaxUrl,
									data : 'ac=updateMyApp&movetype=dock-dock&id=' + oldobj.attr('realid') + '&type=' + oldobj.attr('type') + '&from=' + oldobj.index() + '&to=' + icon2 + '&desk=' + HROS.CONFIG.desk,
									success : function(){
										if(icon2 < iconIndex2){
											$('#dock-bar .dock-applist li:eq(' + icon2 + ')').before(oldobj);
										}else if(icon2 > iconIndex2){
											$('#dock-bar .dock-applist li:eq(' + icon2 + ')').after(oldobj);
										}
										HROS.deskTop.appresize();
									}
								});
							}else{
								var dock_w = HROS.CONFIG.dockPos == 'left' ? 73 : 0;
								var dock_h = HROS.CONFIG.dockPos == 'top' ? 73 : 0;
								icon = HROS.grid.searchAppGrid(cx - dock_w, cy - dock_h);
								if(icon != null){
									$.ajax({
										type : 'POST',
										url : ajaxUrl,
										data : 'ac=updateMyApp&movetype=dock-desk&id=' + oldobj.attr('realid') + '&type=' + oldobj.attr('type') + '&from=' + oldobj.index() + '&to=' + (icon + 1) + '&desk=' + HROS.CONFIG.desk,
										success : function(){
											if(icon < iconIndex){
												$('#desk-' + HROS.CONFIG.desk + ' li:not(.add):eq(' + icon + ')').before(oldobj);
											}else if(icon > iconIndex){
												$('#desk-' + HROS.CONFIG.desk + ' li:not(.add):eq(' + icon + ')').after(oldobj);
											}else{
												if(iconIndex == -1){
													$('#desk-' + HROS.CONFIG.desk + ' li.add').before(oldobj);
												}
											}
											HROS.deskTop.appresize();
										}
									});
								}
							}
						}
					});
				}
				return false;
			});
			//桌面图标拖动
			$('#desk .desktop-container').off('mousedown', 'li:not(.add)').on('mousedown', 'li:not(.add)', function(e){
				e.preventDefault();
				e.stopPropagation();
				if(e.button == 0 || e.button == 1){
					var oldobj = $(this), x, y, cx, cy, dx, dy, lay, obj = $('<li id="shortcut_shadow">' + oldobj.html() + '</li>');
					dx = cx = e.clientX;
					dy = cy = e.clientY;
					x = dx - oldobj.offset().left;
					y = dy - oldobj.offset().top;
					//绑定鼠标移动事件
					$(document).on('mousemove', function(e){
						$('body').append(obj);
						lay = HROS.maskBox.desk();
						lay.show();
						cx = e.clientX <= 0 ? 0 : e.clientX >= $(document).width() ? $(document).width() : e.clientX;
						cy = e.clientY <= 0 ? 0 : e.clientY >= $(document).height() ? $(document).height() : e.clientY;
						_l = cx - x;
						_t = cy - y;
						if(dx != cx || dy != cy){
							obj.css({
								left : _l,
								top : _t
							}).show();
						}
					}).on('mouseup', function(){
						$(document).off('mousemove').off('mouseup');
						obj.remove();
						if(typeof(lay) !== 'undefined'){
							lay.hide();
						}
						//判断是否移动图标，如果没有则判断为click事件
						if(dx == cx && dy == cy){
							switch(oldobj.attr('type')){
								case 'app':
								case 'papp':
									HROS.window.create(oldobj.attr('realid'), oldobj.attr('type'));
									break;
								case 'widget':
								case 'pwidget':
									HROS.widget.create(oldobj.attr('realid'), oldobj.attr('type'));
									break;
								case 'folder':
									HROS.folderView.init(oldobj);
									break;
							}
							return false;
						}
						var folderId = HROS.grid.searchFolderGrid(cx, cy);
						if(folderId != null){
							if(oldobj.attr('type') != 'folder'){
								$.ajax({
									type : 'POST',
									url : ajaxUrl,
									data : 'ac=updateMyApp&movetype=desk-folder&id=' + oldobj.attr('realid') + '&type=' + oldobj.attr('type') + '&from=' + (oldobj.index() - 2) + '&to=' + folderId + '&desk=' + HROS.CONFIG.desk,
									success : function(){
										oldobj.remove();
										HROS.deskTop.appresize();
										//如果文件夹预览面板为显示状态，则进行更新
										if($('#qv_' + folderId).length != 0){
											HROS.folderView.init($('#d_folder_' + folderId));
										}
										//如果文件夹窗口为显示状态，则进行更新
										if($('#w_folder_' + folderId).length != 0){
											HROS.window.updateFolder(folderId, 'folder');
										}
									}
								});
							}
						}else{
							var icon, icon2;
							var iconIndex = $('#desk-' + HROS.CONFIG.desk + ' li.appbtn:not(.add)').length == 0 ? -1 : $('#desk-' + HROS.CONFIG.desk + ' li').index(oldobj);
							var iconIndex2 = $('#dock-bar .dock-applist').html() == '' ? -1 : $('#dock-bar .dock-applist li').index(oldobj);
							
							var dock_w2 = HROS.CONFIG.dockPos == 'left' ? 0 : HROS.CONFIG.dockPos == 'top' ? ($(window).width() - $('#dock-bar .dock-applist').width() - 20) / 2 : $(window).width() - $('#dock-bar .dock-applist').width();
							var dock_h2 = HROS.CONFIG.dockPos == 'top' ? 0 : ($(window).height()-$('#dock-bar .dock-applist').height() - 20) / 2;
							icon2 = HROS.grid.searchDockAppGrid(cx - dock_w2, cy - dock_h2);
							if(icon2 != null){
								$.ajax({
									type : 'POST',
									url : ajaxUrl,
									data : 'ac=updateMyApp&movetype=desk-dock&id=' + oldobj.attr('realid') + '&type=' + oldobj.attr('type') + '&from=' + (oldobj.index() - 2) + '&to=' + (icon2 + 1) + '&desk=' + HROS.CONFIG.desk,
									success : function(){
										if(icon2 < iconIndex2){
											$('#dock-bar .dock-applist li:eq(' + icon2 + ')').before(oldobj);
										}else if(icon2 > iconIndex2){
											$('#dock-bar .dock-applist li:eq(' + icon2 + ')').after(oldobj);
										}else{
											if(iconIndex2 == -1){
												$('#dock-bar .dock-applist').append(oldobj);
											}
										}
										if($('#dock-bar .dock-applist li').length > 7){
											$('#desk-' + HROS.CONFIG.desk + ' li.add').before($('#dock-bar .dock-applist li').last());
										}
										HROS.deskTop.appresize();
									}
								});
							}else{
								var dock_w = HROS.CONFIG.dockPos == 'left' ? 73 : 0;
								var dock_h = HROS.CONFIG.dockPos == 'top' ? 73 : 0;
								icon = HROS.grid.searchAppGrid(cx - dock_w, cy - dock_h);
								if(icon != null && icon != (oldobj.index() - 2)){
									$.ajax({
										type : 'POST',
										url : ajaxUrl,
										data : 'ac=updateMyApp&movetype=desk-desk&id=' + oldobj.attr('realid') + '&type=' + oldobj.attr('type') + '&from=' + (oldobj.index() - 2) + '&to=' + icon + '&desk=' + HROS.CONFIG.desk,
										success : function(){
											if(icon < iconIndex){
												$('#desk-' + HROS.CONFIG.desk + ' li:not(.add):eq(' + icon + ')').before(oldobj);
											}else if(icon > iconIndex){
												$('#desk-' + HROS.CONFIG.desk + ' li:not(.add):eq(' + icon + ')').after(oldobj);
											}else{
												if(iconIndex == -1){
													$('#desk-' + HROS.CONFIG.desk + ' li.add').before(oldobj);
												}
											}
											HROS.deskTop.appresize();
										}
									});
								}
							}
						}
					});
				}
			});
			//文件夹内图标拖动
			$('.folder_body, .quick_view_container').off('mousedown', 'li').on('mousedown', 'li', function(e){
				e.preventDefault();
				e.stopPropagation();
				if(e.button == 0 || e.button == 1){
					var oldobj = $(this), x, y, cx, cy, dx, dy, lay, obj = $('<li id="shortcut_shadow">' + oldobj.html() + '</li>');
					dx = cx = e.clientX;
					dy = cy = e.clientY;
					x = dx - oldobj.offset().left;
					y = dy - oldobj.offset().top;
					//绑定鼠标移动事件
					$(document).on('mousemove', function(e){
						$('body').append(obj);
						lay = HROS.maskBox.desk();
						lay.show();
						cx = e.clientX <= 0 ? 0 : e.clientX >= $(document).width() ? $(document).width() : e.clientX;
						cy = e.clientY <= 0 ? 0 : e.clientY >= $(document).height() ? $(document).height() : e.clientY;
						_l = cx - x;
						_t = cy - y;
						if(dx != cx || dy != cy){
							obj.css({
								left : _l,
								top : _t
							}).show();
						}
					}).on('mouseup', function(){
						$(document).off('mousemove').off('mouseup');
						obj.remove();
						if(typeof(lay) !== 'undefined'){
							lay.hide();
						}
						//判断是否移动图标，如果没有则判断为click事件
						if(dx == cx && dy == cy){
							switch(oldobj.attr('type')){
								case 'app':
								case 'papp':
									HROS.window.create(oldobj.attr('realid'), oldobj.attr('type'));
									break;
								case 'widget':
								case 'pwidget':
									HROS.widget.create(oldobj.attr('realid'), oldobj.attr('type'));
									break;
							}
							return false;
						}
						var folderId = HROS.grid.searchFolderGrid(cx, cy);
						if(folderId != null){
							if(oldobj.parents('.folder-window').attr('realid') != folderId){
								$.ajax({
									type : 'POST',
									url : ajaxUrl,
									data : 'ac=updateMyApp&movetype=folder-folder&id=' + oldobj.attr('realid') + '&type=' + oldobj.attr('type') + '&from=' + oldobj.parents('.folder-window').attr('realid') + '&to=' + folderId + '&desk=' + HROS.CONFIG.desk,
									success : function(){
										oldobj.remove();
										HROS.deskTop.appresize();
										//如果文件夹预览面板为显示状态，则进行更新
										if($('#qv_' + folderId).length != 0){
											HROS.folderView.init($('#d_folder_' + folderId));
										}
										//如果文件夹窗口为显示状态，则进行更新
										if($('#w_folder_' + folderId).length != 0){
											HROS.window.updateFolder(folderId, 'folder');
										}
									}
								});
							}
						}else{
							var icon, icon2;
							var iconIndex = $('#desk-' + HROS.CONFIG.desk + ' li.appbtn:not(.add)').length == 0 ? -1 : $('#desk-' + HROS.CONFIG.desk + ' li').index(oldobj);
							var iconIndex2 = $('#dock-bar .dock-applist').html() == '' ? -1 : $('#dock-bar .dock-applist li').index(oldobj);
							
							var dock_w2 = HROS.CONFIG.dockPos == 'left' ? 0 : HROS.CONFIG.dockPos == 'top' ? ($(window).width() - $('#dock-bar .dock-applist').width() - 20) / 2 : $(window).width() - $('#dock-bar .dock-applist').width();
							var dock_h2 = HROS.CONFIG.dockPos == 'top' ? 0 : ($(window).height() - $('#dock-bar .dock-applist').height() - 20) / 2;
							icon2 = HROS.grid.searchDockAppGrid(cx - dock_w2, cy - dock_h2);
							if(icon2 != null){
								$.ajax({
									type : 'POST',
									url : ajaxUrl,
									data : 'ac=updateMyApp&movetype=folder-dock&id=' + oldobj.attr('realid') + '&type=' + oldobj.attr('type') + '&from=' + oldobj.parents('.folder-window').attr('realid') + '&to=' + (icon2 + 1) + '&desk=' + HROS.CONFIG.desk,
									success : function(){
										var folderId = oldobj.parents('.folder-window').attr('realid');
										if(icon2 < iconIndex2){
											$('#dock-bar .dock-applist li.appbtn:not(.add):eq(' + icon2 + ')').before(oldobj);
										}else if(icon2 > iconIndex2){
											$('#dock-bar .dock-applist li.appbtn:not(.add):eq(' + icon2 + ')').after(oldobj);
										}else{
											if(iconIndex2 == -1){
												$('#dock-bar .dock-applist').append(oldobj);
											}
										}
										if($('#dock-bar .dock-applist li').length > 7){
											$('#desk-' + HROS.CONFIG.desk + ' li.add').before($('#dock-bar .dock-applist li').last());
										}
										HROS.deskTop.appresize();
										//如果文件夹预览面板为显示状态，则进行更新
										if($('#qv_' + folderId).length != 0){
											HROS.folderView.init($('#d_folder_' + folderId));
										}
										//如果文件夹窗口为显示状态，则进行更新
										if($('#w_folder_' + folderId).length != 0){
											HROS.window.updateFolder(folderId, 'folder');
										}
									}
								});
							}else{
								var dock_w = HROS.CONFIG.dockPos == 'left' ? 73 : 0;
								var dock_h = HROS.CONFIG.dockPos == 'top' ? 73 : 0;
								icon = HROS.grid.searchAppGrid(cx - dock_w, cy - dock_h);
								if(icon != null){
									$.ajax({
										type : 'POST',
										url : ajaxUrl,
										data : 'ac=updateMyApp&movetype=folder-desk&id=' + oldobj.attr('realid') + '&type=' + oldobj.attr('type') + '&from=' + oldobj.parents('.folder-window').attr('realid') + '&to=' + (icon + 1) + '&desk=' + HROS.CONFIG.desk,
										success : function(){
											var folderId = oldobj.parents('.folder-window').attr('realid');
											if(icon < iconIndex){
												$('#desk-' + HROS.CONFIG.desk + ' li.appbtn:not(.add):eq(' + icon + ')').before(oldobj);
											}else if(icon > iconIndex){
												$('#desk-' + HROS.CONFIG.desk + ' li.appbtn:not(.add):eq(' + icon + ')').after(oldobj);
											}else{
												if(iconIndex == -1){
													$('#desk-' + HROS.CONFIG.desk + ' li.add').before(oldobj);
												}
											}
											HROS.deskTop.appresize();
											//如果文件夹预览面板为显示状态，则进行更新
											if($('#qv_' + folderId).length != 0){
												HROS.folderView.init($('#d_folder_' + folderId));
											}
											//如果文件夹窗口为显示状态，则进行更新
											if($('#w_folder_' + folderId).length != 0){
												HROS.window.updateFolder(folderId, 'folder');
											}
										}
									});
								}
							}
						}
					});
				}
			});
		},
		/*
		**  加载滚动条
		*/
		getScrollbar : function(){
			setTimeout(function(){
				$('#desk .desktop-container').each(function(){
					var desk = $(this), scrollbar = desk.children('.scrollbar');
					//先清空所有附加样式
					scrollbar.hide();
					desk.scrollLeft(0).scrollTop(0);
					/*
					**  判断图标排列方式
					**  横向排列超出屏幕则出现纵向滚动条，纵向排列超出屏幕则出现横向滚动条
					*/
					if(HROS.CONFIG.appXY == 'x'){
						/*
						**  获得桌面图标定位好后的实际高度
						**  因为显示的高度是固定的，而实际的高度是根据图标个数会变化
						*/
						var deskH = parseInt(desk.children('.add').css('top')) + 108;
						/*
						**  计算滚动条高度
						**  高度公式（图标纵向排列计算滚动条宽度以此类推）：
						**  滚动条实际高度 = 桌面显示高度 / 桌面实际高度 * 滚动条总高度(桌面显示高度)
						**  如果“桌面显示高度 / 桌面实际高度 >= 1”说明图标个数未能超出桌面，则不需要出现滚动条
						*/
						if(desk.height() / deskH < 1){
							desk.children('.scrollbar-y').height(desk.height() / deskH * desk.height()).css('top',0).show();
						}
					}else{
						var deskW = parseInt(desk.children('.add').css('left')) + 106;
						if(desk.width() / deskW < 1){
							desk.children('.scrollbar-x').width(desk.width() / deskW * desk.width()).css('left',0).show();
						}
					}
				});
			},500);
		},
		/*
		**  移动滚动条
		*/
		moveScrollbar : function(){
			/*
			**  手动拖动
			*/
			$('.scrollbar').on('mousedown', function(e){
				var x, y, cx, cy, deskrealw, deskrealh, movew, moveh;
				var scrollbar = $(this), desk = scrollbar.parent('.desktop-container');
				deskrealw = parseInt(desk.children('.add').css('left')) + 106;
				deskrealh = parseInt(desk.children('.add').css('top')) + 108;
				movew = desk.width() - scrollbar.width();
				moveh = desk.height() - scrollbar.height();
				if(scrollbar.hasClass('scrollbar-x')){
					x = e.clientX - scrollbar.offset().left;
				}else{
					y = e.clientY - scrollbar.offset().top;
				}
				$(document).on('mousemove', function(e){
					if(scrollbar.hasClass('scrollbar-x')){
						if(HROS.CONFIG.dockPos == 'left'){
							cx = e.clientX - x - 73 < 0 ? 0 : e.clientX - x - 73 > movew ? movew : e.clientX - x - 73;
						}else{
							cx = e.clientX - x < 0 ? 0 : e.clientX - x > movew ? movew : e.clientX - x;
						}
						scrollbar.css('left', cx / desk.width() * deskrealw + cx);
						desk.scrollLeft(cx / desk.width() * deskrealw);
					}else{
						if(HROS.CONFIG.dockPos == 'top'){
							cy = e.clientY - y - 73 < 0 ? 0 : e.clientY - y - 73 > moveh ? moveh : e.clientY - y - 73;
						}else{
							cy = e.clientY - y < 0 ? 0 : e.clientY - y > moveh ? moveh : e.clientY - y;
						}
						scrollbar.css('top', cy / desk.height() * deskrealh + cy);
						desk.scrollTop(cy / desk.height() * deskrealh);
					}
				}).on('mouseup', function(){
					$(this).off('mousemove').off('mouseup');
				});
			});
			/*
			**  鼠标滚轮
			**  只支持纵向滚动条
			*/
			$('#desk .desktop-container').each(function(i){
				$('#desk-' + (i + 1)).on('mousewheel', function(event, delta){
					var desk = $(this), deskrealh = parseInt(desk.children('.add').css('top')) + 108, scrollupdown;
					/*
					**  delta == -1   往下
					**  delta == 1    往上
					**  chrome下鼠标滚轮每滚动一格，页面滑动距离是200px，所以下面也用这个值来模拟每次滑动的距离
					*/
					if(delta < 0){
						scrollupdown = desk.scrollTop() + 200 > deskrealh - desk.height() ? deskrealh - desk.height() : desk.scrollTop() + 200;
					}else{
						scrollupdown = desk.scrollTop() - 200 < 0 ? 0 : desk.scrollTop() - 200;
					}
					desk.stop(false, true).animate({scrollTop:scrollupdown},300);
					desk.children('.scrollbar-y').stop(false, true).animate({
						top : scrollupdown / deskrealh * desk.height() + scrollupdown
					}, 300);
				});
			});
		}
	}
})();
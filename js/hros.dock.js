/*
**  应用码头
*/
HROS.dock = (function(){
	return {
		getPos : function(fun){
			$.ajax({
				type : 'POST',
				url : ajaxUrl,
				data : 'ac=getDockPos',
				success : function(i){
					HROS.CONFIG.dockPos = i;
					HROS.dock.setPos();
					if(typeof(fun) != 'undefined'){
						fun();
					}
				}
			});
		},
		setPos : function(){
			var desktop = $('#desk-' + HROS.CONFIG.desk), desktops = $('#desk .desktop-container');
			var desk_w = desktop.css('width', '100%').width(), desk_h = desktop.css('height', '100%').height();
			//清除dock位置样式
			$('#dock-container').removeClass('dock-top').removeClass('dock-left').removeClass('dock-right');
			$('#dock-bar').removeClass('top-bar').removeClass('left-bar').removeClass('right-bar').hide();
			if(HROS.CONFIG.dockPos == 'top'){
				$('#dock-bar').addClass('top-bar').children('#dock-container').addClass('dock-top');
				desktops.css({
					'width' : desk_w,
					'height' : desk_h - 143,
					'left' : desk_w,
					'top' : 73
				});
				desktop.css({
					'left' : 0
				});
			}else if(HROS.CONFIG.dockPos == 'left'){
				$('#dock-bar').addClass('left-bar').children('#dock-container').addClass('dock-left');
				desktops.css({
					'width' : desk_w - 73,
					'height' : desk_h - 70,
					'left' : desk_w + 73,
					'top' : 0
				});
				desktop.css({
					'left' : 73
				});
			}else if(HROS.CONFIG.dockPos == 'right'){
				$('#dock-bar').addClass('right-bar').children('#dock-container').addClass('dock-right');
				desktops.css({
					'width' : desk_w - 73,
					'height' : desk_h - 70,
					'left' : desk_w,
					'top' : 0
				});
				desktop.css({
					'left' : 0
				});
			}
			$('#dock-bar').show();
			HROS.taskbar.resize();
		},
		updatePos : function(pos, fun){
			$.ajax({
				type : 'POST',
				url : ajaxUrl,
				data : 'ac=setDockPos&dock=' + pos,
				success : function(){
					HROS.CONFIG.dockPos = pos;
					if(typeof(fun) != 'undefined'){
						fun();
					}
				}
			});
		},
		move : function(){
			$('#dock-container').off('mousedown').on('mousedown',function(e){
				if(e.button == 0 || e.button == 1){
					var lay = HROS.maskBox.dock(), location;
					$(document).on('mousemove', function(e){
						lay.show();
						if(e.clientY < lay.height() * 0.2){
							location = 'top';		
						}else if(e.clientX < lay.width() * 0.5){
							location = 'left';
						}else{				
							location = 'right';
						}
						$('.dock_drap_effect').removeClass('hover');
						$('.dock_drap_effect_' + location).addClass('hover');
					}).on('mouseup', function(){
						$(document).off('mousemove').off('mouseup');
						lay.hide();
						if(location != HROS.CONFIG.dockPos && typeof(location) != 'undefined'){
							HROS.dock.updatePos(location, function(){
								//更新码头位置
								HROS.dock.setPos();
								//更新图标位置
								HROS.deskTop.appresize();
								//更新滚动条
								HROS.app.getScrollbar();
							});
						}
					});
				}
			});
		}
	}
})();
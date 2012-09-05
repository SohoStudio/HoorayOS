HROS.folderView = (function(){
	return {
		init : function(obj){
			var folderViewHtml = '';
			$.getJSON(ajaxUrl + '?ac=getMyFolderApp&folderid=' + obj.attr('realid'), function(sc){
				var height = 0;
				if(sc != null){
					for(var i = 0; i < sc.length; i++){
						switch(sc[i]['type']){
							case 'app':
							case 'widget':
							case 'papp':
							case 'pwidget':
								folderViewHtml += appbtnTemp({
									'top' : 0,
									'left' : 0,
									'title' : sc[i]['name'],
									'type' : sc[i]['type'],
									'id' : 'd_' + sc[i]['type'] + '_' + sc[i]['id'],
									'realid' : sc[i]['id'],
									'imgsrc' : sc[i]['icon']
								});
								break;
						}
					}
					if(sc.length % 4 == 0){
						height += Math.floor(sc.length / 4) * 60;
					}else{
						height += (Math.floor(sc.length / 4) + 1) * 60;
					}
				}else{
					folderViewHtml = '文件夹为空';
					height += 30;
				}
				//判断是桌面上的文件夹，还是应用码头上的文件夹
				var left, top;
				if(obj.parent('div').hasClass('dock-applist')){
					left = obj.offset().left + 60;
					top = obj.offset().top;
				}else{
					left = obj.offset().left + 80;
					top = obj.offset().top - 20;
				}
				//判断预览面板是否有超出屏幕
				var isScrollbar = false;
				if(height + top + 46 > $(document).height()){
					var outH = height + top + 46 - $(document).height();
					if(outH <= top){
						top -= outH;
					}else{
						height -= outH - top;
						top = 0;
						isScrollbar = true;
					}
				}
				$('.quick_view_container').remove();
				if(left + 340 > $(document).width()){
					//预览居左
					$('body').append(folderViewTemp({
						'id' : 'qv_' + obj.attr('realid'),
						'realid' : obj.attr('realid'),
						'apps' : folderViewHtml,
						'top' : top,
						'left' : left - 340 - 80,
						'height' : height,
						'mlt' : Math.ceil((height + 26) / 2),
						'mlm' : false,
						'mlb' : Math.ceil((height + 26) / 2),
						'mrt' : obj.offset().top - top,
						'mrm' : true,
						'mrb' : height + 26 - (obj.offset().top - top) - 20
					}));
				}else{
					//预览居右
					$('body').append(folderViewTemp({
						'id' : 'qv_' + obj.attr('realid'),
						'realid' : obj.attr('realid'),
						'apps' : folderViewHtml,
						'top' : top,
						'left' : left,
						'height' : height,
						'mlt' : obj.offset().top - top,
						'mlm' : true,
						'mlb' : height + 26 - (obj.offset().top - top) - 20,
						'mrt' : Math.ceil((height + 26) / 2),
						'mrm' : false,
						'mrb' : Math.ceil((height + 26) / 2)
					}));
				}
				$('body').on('contextmenu', '.appbtn:not(.add)', function(e){
					$('.popup-menu').hide();
					TEMP.AppRight = HROS.popupMenu.app($(this));
					var l = ($(document).width() - e.clientX) < TEMP.AppRight.width() ? (e.clientX - TEMP.AppRight.width()) : e.clientX;
					var t = ($(document).height() - e.clientY) < TEMP.AppRight.height() ? (e.clientY - TEMP.AppRight.height()) : e.clientY;
					TEMP.AppRight.css({
						left : l,
						top : t
					}).show();
					return false;
				});
				$('.quick_view_container_open').on('click',function(){
					HROS.window.create($(this).parents('.quick_view_container').attr('realid'), 'folder');
					$('#quick_view_container_' + $(this).parents('.quick_view_container').attr('realid')).remove();
				});
				HROS.folderView.getScrollbar(obj.attr('realid'),isScrollbar);
				HROS.folderView.moveScrollbar(obj.attr('realid'));
				HROS.app.move();
			});
		},
		getScrollbar : function(id, isScrollbar){
			var view = '#quick_view_container_list_in_' + id;
			var scrollbar = '#quick_view_container_list_' + id + ' .scrollBar';
			if(isScrollbar){
				$('#quick_view_container_list_' + id + ' .scrollBar_bgc').show();
				$(scrollbar).show().height($(view).height() / (Math.ceil($(view).children().length / 4) * 60) * $(view).height());
			}else{
				$('#quick_view_container_list_' + id + ' .scrollBar_bgc').hide();
				$(scrollbar).hide().height(0);
			}
		},
		moveScrollbar : function(id){
			var view = '#quick_view_container_list_in_' + id;
			var scrollbar = '#quick_view_container_list_' + id + ' .scrollBar';
			/*
			**  手动拖动
			*/
			$(scrollbar).on('mousedown', function(e){
				var offsetTop = $('#quick_view_container_' + id).offset().top + 36;
				var y, cy, deskrealh, moveh;
				var scrollbar = $(this), desk = $(view);
				deskrealh = Math.ceil($(view).children().length / 4) * 60;
				moveh = desk.height() - scrollbar.height();
				y = e.clientY - scrollbar.offset().top;
				$(document).on('mousemove', function(e){
					cy = e.clientY - y - offsetTop < 0 ? 0 : e.clientY - y - offsetTop > moveh ? moveh : e.clientY - y - offsetTop;
					scrollbar.css('top', cy);
					desk.scrollTop(cy / desk.height() * deskrealh);
				}).on('mouseup', function(){
					$(this).off('mousemove').off('mouseup');
				});
			});
			/*
			**  鼠标滚轮
			*/
			$(view).off('mousewheel').on('mousewheel', function(event, delta){
				var desk = $(this), deskrealh = Math.ceil($(view).children().length / 4) * 60, scrollupdown;
				/*
				**  delta == -1   往下
				**  delta == 1    往上
				*/
				if(delta < 0){
					scrollupdown = desk.scrollTop() + 40 > deskrealh - desk.height() ? deskrealh - desk.height() : desk.scrollTop() + 40;
				}else{
					scrollupdown = desk.scrollTop() - 40 < 0 ? 0 : desk.scrollTop() - 40;
				}
				desk.stop(false, true).animate({
					scrollTop : scrollupdown
				}, 300);
				$(scrollbar).stop(false, true).animate({
					top : scrollupdown / deskrealh * desk.height()
				}, 300);
			});
		}
	}
})();
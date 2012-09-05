/*
**  任务栏
*/
HROS.taskbar = (function(){
	return {
		init : function(){
			$('#task-content-inner').off('click').on('click', 'a.task-item', function(){
				if($(this).hasClass('task-item-current')){
					HROS.window.hide($(this).attr('realid'), $(this).attr('type'));
				}else{
					HROS.window.show2top($(this).attr('realid'), $(this).attr('type'));
				}
			}).off('contextmenu').on('contextmenu', 'a.task-item', function(e){
				$('.popup-menu').hide();
				$('.quick_view_container').remove();
				HROS.taskbar.rightClick($(this), e.clientX, e.clientY);
				return false;
			});
		},
		rightClick: function(obj, x, y){
			$('.popup-menu').hide();
			$('.quick_view_container').remove();
			var popupmenu = HROS.popupMenu.task(obj);
			l = $(document).width() - x < popupmenu.width() ? x - popupmenu.width() : x;
			t = y - popupmenu.height();
			popupmenu.css({
				left : l,
				top : t
			}).show();
			return false;
		},
		pageClick : function(showW, realW){
			var overW = realW - showW;
			if(HROS.CONFIG.dockPos == 'right'){
				$('#task-content-inner').animate({
					marginLeft : 0
				}, 200);
			}else{
				$('#task-content-inner').animate({
					marginRight : 0
				}, 200);
			}
			$('#task-next a').addClass('disable');
			$('#task-pre a').removeClass('disable');
			$('#task-next-btn').off('click').on('click',function(){
				if($(this).hasClass('disable') == false){
					if(HROS.CONFIG.dockPos == 'right'){
						var marginL = parseInt($('#task-content-inner').css('margin-left')) + 114;
						if(marginL >= 0){
							marginL = 0;
							$('#task-next a').addClass('disable');
						}
						$('#task-pre a').removeClass('disable');
						$('#task-content-inner').animate({
							marginLeft : marginL
						}, 200);
					}else{
						var marginR = parseInt($('#task-content-inner').css('margin-right')) + 114;
						if(marginR >= 0){
							marginR = 0;
							$('#task-next a').addClass('disable');
						}
						$('#task-pre a').removeClass('disable');
						$('#task-content-inner').animate({
							marginRight : marginR
						}, 200);
					}
				}
			});
			$('#task-pre-btn').off('click').on('click', function(){
				if($(this).hasClass('disable') == false){
					if(HROS.CONFIG.dockPos == 'right'){
						var marginL = parseInt($('#task-content-inner').css('margin-left')) - 114;
						if(marginL <= overW * -1){
							marginL = overW * -1;
							$('#task-pre a').addClass('disable');
						}
						$('#task-next a').removeClass('disable');
						$('#task-content-inner').animate({
							marginLeft : marginL
						}, 200);
					}else{
						var marginR = parseInt($('#task-content-inner').css('margin-right')) - 114;
						if(marginR <= overW * -1){
							marginR = overW * -1;
							$('#task-pre a').addClass('disable');
						}
						$('#task-next a').removeClass('disable');
						$('#task-content-inner').animate({
							marginRight : marginR
						}, 200);
					}
				}
			});
		},
		resize : function(){
			if(HROS.CONFIG.dockPos == 'left'){
				$('#task-bar').css({
					'left' : 73,
					'right' : 0
				});
				$('#task-content-inner').removeClass('fl');
			}else if(HROS.CONFIG.dockPos == 'right'){
				$('#task-bar').css({
					'left' : 0,
					'right' : 73
				});
				$('#task-content-inner').addClass('fl');
			}else{
				$('#task-bar').css({
					'left' : 0,
					'right' : 0
				});
				$('#task-content-inner').removeClass('fl');
			}
			var w = $('#task-bar').width(), taskItemW = $('#task-content-inner .task-item').length * 114, showW = w - 112;
			if(taskItemW >= showW){
				$('#task-next, #task-pre').show();
				$('#task-content').css('width', showW);
				HROS.taskbar.pageClick(showW, taskItemW);
			}else{
				$('#task-next, #task-pre').hide();
				$('#task-content').css('width','100%');
				$('#task-content-inner').css({
					'margin-left' : 0,
					'margin-right' : 0
				});
			}
		}
	}
})();
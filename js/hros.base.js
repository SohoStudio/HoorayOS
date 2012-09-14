/*
**  一个不属于其他模块的模块
*/
HROS.base = (function(){
	return {
		/*
		**	系统初始化
		*/
		init : function(){
			//文件上传
			//HROS.uploadFile.init();
			//增加离开页面确认窗口，IE不支持，故屏蔽
			if(!$.browser.msie){
				window.onbeforeunload = Util.confirmExit;
			}
			//绑定body点击事件，主要目的就是为了强制隐藏右键菜单
			$('#desktop').on('click', function(){
				$('.popup-menu').hide();
				$('.quick_view_container').remove();
			});
			//隐藏浏览器默认右键菜单
			$('body').on('contextmenu', function(){
				$(".popup-menu").hide();
				return false;
			});
			//绑定浏览器resize事件
			HROS.base.resize();
			//用于判断网页是否缩放，该功能提取自QQ空间
			HROS.zoom.init();
			//初始化分页栏
			HROS.navbar.init();
			//绑定任务栏点击事件
			HROS.taskbar.init();
			//获得dock的位置
			HROS.dock.getPos(function(){
				//获取图标排列顺序
				HROS.app.getXY(function(){
					/*
					**      当dockPos为top时          当dockPos为left时         当dockPos为right时
					**  -----------------------   -----------------------   -----------------------
					**  | o o o         dock  |   | o | o               |   | o               | o |
					**  -----------------------   | o | o               |   | o               | o |
					**  | o o                 |   | o | o               |   | o               | o |
					**  | o +                 |   |   | o               |   | o               |   |
					**  | o             desk  |   |   | o         desk  |   | o         desk  |   |
					**  | o                   |   |   | +               |   | +               |   |
					**  -----------------------   -----------------------   -----------------------
					**  因为desk区域的尺寸和定位受dock位置的影响，所以加载图标前必须先定位好dock的位置
					*/
					HROS.app.get();
				});
			});
			//加载壁纸
			HROS.wallpaper.get(function(){
				HROS.wallpaper.set();
			});
			//绑定应用码头2个按钮的点击事件
			$('.dock-tool-setting').on('mousedown', function(){
				return false;
			}).on('click',function(){
				HROS.window.createTemp({
					id : 'zmsz',
					title : '桌面设置',
					url : 'sysapp/desksetting/index.php',
					width : 750,
					height : 450,
					isresize : false,
					isflash : false
				});
			});
			$('.dock-tool-style').on('mousedown', function(){
				return false;
			}).on('click', function(){
				HROS.window.createTemp({
					id : 'ztsz',
					title : '主题设置',
					url : 'sysapp/wallpaper/index.php',
					width : 580,
					height : 520,
					isresize : false,
					isflash : false
				});
			});
			//桌面右键
			$('#desk').on('contextmenu', function(e){
				$(".popup-menu").hide();
				$('.quick_view_container').remove();
				var popupmenu = HROS.popupMenu.desk();
				l = ($(document).width() - e.clientX) < popupmenu.width() ? (e.clientX - popupmenu.width()) : e.clientX;
				t = ($(document).height() - e.clientY) < popupmenu.height() ? (e.clientY - popupmenu.height()) : e.clientY;
				popupmenu.css({
					left : l,
					top : t
				}).show();
				return false;
			});
			//还原widget
			HROS.widget.reduction();
			//加载新手帮助
			HROS.base.help();
			//配置artDialog全局默认参数
			(function(config){
				config['lock'] = true;
				config['fixed'] = true;
				config['resize'] = false;
				config['background'] = '#000';
				config['opacity'] = 0.5;
			})($.dialog.defaults);
		},
		logout : function(){
			$.ajax({
				type : 'POST',
				url : ajaxUrl,
				data : 'ac=logout',
				success : function(){
					location.href = 'login.php';
				}
			});
		},
		resize : function(){
			$(window).on('resize', function(){
				HROS.deskTop.resize(200);
			});
		},
		getSkin : function(){
			$.ajax({
				type : 'POST',
				url : ajaxUrl,
				data : 'ac=getSkin',
				success : function(skin){
					$('#window-skin').remove();
					var link = document.createElement('link');
					link.rel = 'stylesheet';
					link.href = 'img/skins/' + skin + '.css?' + version;
					link.id = 'window-skin';
					$('body').append(link);
				}
			});
		},
		help : function(){
			if($.cookie('isLoginFirst') == null){
				$.cookie('isLoginFirst', '1', {expires : 95});
				if(!$.browser.msie || ($.browser.msie && $.browser.version < 9)){
					$('body').append(helpTemp);
					//IE6,7,8基本就告别新手帮助了
					$('#step1').show();
					$('.close').on('click', function(){
						$('#help').remove();
					});
					$('.next').on('click', function(){
						var obj = $(this).parents('.step');
						var step = obj.attr('step');
						obj.hide();
						$('#step' + (parseInt(step) + 1)).show();
					});
					$('.over').on('click', function(){
						$('#help').remove();
					});
				}
			}
		}
	}
})();
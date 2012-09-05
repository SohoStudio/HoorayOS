/*
**  分页导航
*/
HROS.navbar = (function(){
	return {
		/*
		**  初始化
		*/
		init : function(){
			$('#nav-bar').css({
				'left' : $(document).width() / 2 - 105,
				'top' : 80
			}).show();
			HROS.navbar.move();
			HROS.navbar.deskSwitch();
		},
		/*
		**  拖动
		*/
		move : function(){
			$('#nav-bar').on('mousedown', function(e){
				if(e.button == 0 || e.button == 1){
					var x, y, cx, cy, lay, obj = $('#nav-bar');
					x = e.clientX - obj.offset().left;
					y = e.clientY - obj.offset().top;
					//绑定鼠标移动事件
					$(document).on('mousemove', function(e){
						lay = HROS.maskBox.desk();
						lay.show();
						cx = e.clientX - x <= 0 ? 0 : e.clientX - x > $(document).width() - 210 ? $(document).width() - 210 : e.clientX - x;
						cy = e.clientY - y <= 10 ? 10 : e.clientY - y > $(document).height() - 50 ? $(document).height() - 50 : e.clientY - y;
						obj.css({
							left : cx,
							top : cy
						});
					}).on('mouseup', function(){
						if(typeof(lay) !== 'undefined'){
							lay.hide();
						}
						$(this).off('mousemove').off('mouseup');
					});
				}
			});
		},
		/*
		**  点击切换
		*/
		deskSwitch : function(){
			$('#nav-bar .nav-container').on('mousedown', 'a.indicator', function(e){
				$('.popup-menu').hide();
				$('.quick_view_container').remove();
				if(e.button == 0 || e.button == 1){
					var x, y, cx, cy, dx, dy, lay, obj = $('#nav-bar'), thisobj = $(this);
					dx = cx = obj.offset().left;
					dy = cy = obj.offset().top;
					x = e.clientX - dx;
					y = e.clientY - dy;
					//绑定鼠标移动事件
					$(document).on('mousemove', function(e){
						lay = HROS.maskBox.desk();
						lay.show();
						cx = e.clientX - x <= 0 ? 0 : e.clientX - x > $(document).width() - 210 ? $(document).width() - 210 : e.clientX - x;
						cy = e.clientY - y <= 10 ? 10 : e.clientY - y > $(document).height() - 50 ? $(document).height() - 50 : e.clientY - y;
						obj.css({
							left : cx,
							top : cy
						});
					}).on('mouseup', function(){
						if(dx == cx && dy == cy){
							if(typeof(thisobj.attr('index')) !== 'undefined'){
								var nav = $('#navContainer'), currindex = HROS.CONFIG.desk, switchindex = thisobj.attr('index'),
								currleft = $('#desk-' + currindex).offset().left, switchleft = $('#desk-' + switchindex).offset().left;
								if(currindex != switchindex){
									if(!$('#desk-' + switchindex).hasClass('animated') && !$('#desk-' + currindex).hasClass('animated')){
										$('#desk-' + currindex).addClass('animated').animate({
											left : switchleft
										}, 500, 'easeInOutCirc', function(){
											$(this).removeClass('animated');
										});
										$('#desk-'+switchindex).addClass('animated').animate({
											left : currleft
										}, 500, 'easeInOutCirc', function(){
											$(this).removeClass('animated');
											nav.removeClass('nav-current-' + currindex).addClass('nav-current-' + switchindex);
											HROS.CONFIG.desk = switchindex;
										});
									}
								}
							}else{
								//初始化全局视图
								HROS.appmanage.init();
							}
						}
						if(typeof(lay) !== 'undefined'){
							lay.hide();
						}
						$(this).off('mousemove').off('mouseup');
					});
				}
			});
		}
	}
})();
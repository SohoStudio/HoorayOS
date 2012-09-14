/*
**  小挂件
*/
HROS.widget = (function(){
	return {
		/*
		**  创建挂件
		**  自定义挂件：HROS.widget.createTemp({url,width,height,left,top});
		**      示例：HROS.widget.createTemp({url:"http://www.baidu.com",width:800,height:400,left:100,top:100});
		*/
		createTemp : function(obj){
			$('.popup-menu').hide();
			$('.quick_view_container').remove();
			var type = 'widget', id = obj.id == null ? Date.parse(new Date()) : obj.id;
			//判断窗口是否已打开
			var iswidgetopen = false;
			$('#desk .widget').each(function(){
				if($(this).attr('realid') == id && $(this).attr('type') == type){
					iswidgetopen = true;
				}
			});
			//如果没有打开，则进行创建
			if(iswidgetopen == false){
				function nextDo(options){
					$('#desk').append(widgetWindowTemp({
						'width' : options.width,
						'height' : options.height,
						'type' : options.type,
						'id' : 'w_' + options.type + '_' + options.id,
						'realid' : options.id,
						'top' : options.top,
						'left' : options.left,
						'url' : options.url
					}));
					var widgetId = '#w_' + options.type + '_' + options.id;
					//绑定小挂件上各个按钮事件
					HROS.widget.handle($(widgetId));
					//绑定小挂件移动
					HROS.widget.move($(widgetId));
				}
				nextDo({
					type : 'widget',
					id : obj.id == null ? Date.parse(new Date()) : obj.id,
					url : obj.url,
					width : obj.width,
					height : obj.height,
					top : obj.top == null ? 0 : obj.top,
					left : obj.left == null ? 0 : obj.left
				});
			}
		},
		create : function(id, type){
			//判断窗口是否已打开
			var iswidgetopen = false;
			$('#desk .widget').each(function(){
				if($(this).attr('realid') == id){
					iswidgetopen = true;
				}
			});
			//如果没有打开，则进行创建
			if(iswidgetopen == false){
				function nextDo(options){
					if(HROS.widget.checkCookie(id, type)){
						if($.cookie('widgetState')){
							widgetState = eval("(" + $.cookie('widgetState') + ")");
							$(widgetState).each(function(){
								if(this.id == options.id){
									options.top = this.top;
									options.left = this.left;
									options.type = this.type;
								}
							});
						}
					}else{
						HROS.widget.addCookie(options.id, options.type, 0, 0);
					}
					$('#desk').append(widgetWindowTemp({
						'width' : options.width,
						'height' : options.height,
						'type' : options.type,
						'id' : 'w_' + options.type + '_' + options.id,
						'realid' : options.id,
						'top' : options.top,
						'left' : options.left,
						'url' : options.url
					}));
					var widgetId = '#w_' + options.type + '_' + options.id;
					//绑定小挂件上各个按钮事件
					HROS.widget.handle($(widgetId));
					//绑定小挂件移动
					HROS.widget.move($(widgetId));
				}
				ZENG.msgbox.show('小挂件正在加载中，请耐心等待...', 6, 100000);
				$.getJSON(ajaxUrl + '?ac=getMyAppById&id=' + id + '&type=' + type, function(widget){
					if(widget != null){
						ZENG.msgbox._hide();
						var options = {
							id : widget['id'],
							url : widget['url'],
							width : widget['width'],
							height : widget['height'],
							type : widget['type']
						};
						nextDo(options);
					}else{
						ZENG.msgbox.show('小挂件加载失败', 5, 2000);
						return false;
					}
				});
			}
		},
		//还原上次退出系统时widget的状态
		reduction : function(){
			if($.cookie('widgetState')){
				var widgetState = eval("(" + $.cookie('widgetState') + ")");
				for(var i = 0; i < widgetState.length; i++){
					HROS.widget.create(widgetState[i].id, widgetState[i].type);
				}
			}
		},
		//根据id验证是否存在cookie中
		checkCookie : function(id, type){
			var flag = false;
			if($.cookie('widgetState')){
				widgetState = eval("(" + $.cookie('widgetState') + ")");
				$(widgetState).each(function(){
					if(this.id == id && this.type == type){
						flag = true;
					}
				});
			}
			return flag;
		},
		/*
		**  以下三个方法：addCookie、updateCookie、removeCookie
		**  用于记录widget打开状态以及摆放位置
		**  实现用户二次登入系统时，还原上次widget的状态
		*/
		addCookie : function(id, type, top, left){
			if(!HROS.widget.checkCookie(id, type)){
				var json = [];
				if($.cookie('widgetState')){
					var widgetState = eval("(" + $.cookie('widgetState') + ")"), len = widgetState.length;
					for(var i = 0; i < len; i++){
						json.push("{'id':'" + widgetState[i].id + "','type':'" + widgetState[i].type + "','top':'" + widgetState[i].top + "','left':'" + widgetState[i].left + "'}");
					}
				}
				json.push("{'id':'" + id + "','type':'" + type + "','top':'" + top + "','left':'" + left + "'}");
				$.cookie('widgetState', '[' + json.join(',') + ']', {expires : 95});
			}
		},
		updateCookie : function(id, type, top, left){
			if(HROS.widget.checkCookie(id, type)){
				var widgetState = eval("(" + $.cookie('widgetState') + ")"), len = widgetState.length, json = [];
				for(var i = 0; i < len; i++){
					if(widgetState[i].id == id){
						json.push("{'id':'" + id + "','type':'" + type + "','top':'" + top + "','left':'" + left + "'}");
					}else{
						json.push("{'id':'" + widgetState[i].id + "','type':'" + widgetState[i].type + "','top':'" + widgetState[i].top + "','left':'" + widgetState[i].left + "'}");
					}
				}
				$.cookie('widgetState', '[' + json.join(',') + ']', {expires : 95});
			}
		},
		removeCookie : function(id, type){
			if(HROS.widget.checkCookie(id, type)){
				var widgetState = eval("(" + $.cookie('widgetState') + ")"), len = widgetState.length, json = [];
				for(var i = 0; i < len; i++){
					if(widgetState[i].id != id){
						json.push("{'id':'" + widgetState[i].id + "','type':'" + widgetState[i].type + "','top':'" + widgetState[i].top + "','left':'" + widgetState[i].left + "'}");
					}
				}
				$.cookie('widgetState', '[' + json.join(',') + ']', {expires : 95});
			}
		},
		move : function(obj){
			obj.on('mousedown', '.move', function(e){
				var lay, x, y;
				x = e.clientX - obj.offset().left;
				y = e.clientY - obj.offset().top;
				//绑定鼠标移动事件
				$(document).on('mousemove', function(e){
					lay = HROS.maskBox.desk();
					lay.show();
					_l = e.clientX - x;
					_t = e.clientY - y;
					_t = _t < 0 ? 0 : _t;
					obj.css({
						left : _l,
						top : _t
					});
				}).on('mouseup', function(){
					$(this).off('mousemove').off('mouseup');
					if(typeof(lay) !== 'undefined'){
						lay.hide();
					}
					HROS.widget.updateCookie(obj.attr('realid'), obj.attr('type'), _t, _l);
				});
			});
		},
		close : function(id, type){
			var widgetId = '#w_' + type + '_' + id;
			$(widgetId).html('').remove();
			HROS.widget.removeCookie(id, type);
		},
		handle : function(obj){
			obj.on('click', '.ha-close', function(){
				HROS.widget.close(obj.attr('realid'), obj.attr('type'));
			})
		}
	}
})();
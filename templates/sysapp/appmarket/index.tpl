<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>应用市场</title>
{include file="../global_css.tpl"}
<link rel="stylesheet" href="../../img/ui/sys.css">
</head>

<body>
<div class="sub-nav">
	<ul class="nav-sort">
		<li class="focus" value="0"><a href="javascript:;">全部</a></li>
		{foreach from=$apptype item=at}
			{if $at.id==1 && $membertype==1}
				<li value="{$at.id}"><a href="javascript:;">{$at.name}</a></li>
			{elseif $at.id!=1}
				<li value="{$at.id}"><a href="javascript:;">{$at.name}</a></li>
			{/if}
		{/foreach}
	</ul>
	<dl class="nav-personal">
		<dt></dt>
		<dd value="-1"><a href="javascript:;" class="per-app">我的应用</a></dd>
	</dl>
	<input type="hidden" name="search_1" id="search_1" value="">
</div>
<div class="wrap">
	<div class="col-sub">
		<div class="search-box">
			<div class="input-append">
				<input type="text" name="keyword" id="keyword" style="width:170px;"><button id="search_3" class="btn"><i class="icon-search"></i></button>
			</div>
		</div>
		<div class="mbox commend-day">
			<h3>今日推荐</h3>
			<div class="commend-container">
				<a href="?ct=app&amp;ac=show&amp;app_id=192">
					<!--img src="http://open.115.com/static/uploads/ico/2012021316591511928.png" alt="图吧地图"-->			
				</a>
			</div>
			<div class="commend-text">
				<h4>
					<strong>图吧地图</strong>
					<span>33593人在用</span>				
				</h4>
				<div class="con">
					图吧地图是国内最大的在线电子地图及无线地图服务提供商，市场占有率超过80％。图吧地图（map）为互联网和手机用户提供地图搜索、位置查询和公交，驾车线路等交通规划服务，为行业客户提供GIS行业应用解决方案和地图API产品，同时为中小企业客户提供推广必备的地图标注产品。				
				</div>
				<a href="javascript:;" app_id="192" class="btn-add">添加应用</a>
			</div>
			<span class="star-box"><i style="width:68%;"></i></span>
		</div>
		<div class="mbox commend-day">
			<h3>我也要开发应用</h3>
		</div>
	</div>
	<div class="col-main">
		<div class="mbox app-list-box">
			<div class="title">
				<ul>
					<li class="focus" value="1"><a href="javascript:;">最新应用</a></li>
					<li value="2"><a href="javascript:;">最热门</a></li>
					<li value="3"><a href="javascript:;">最高评价</a></li>
					<input type="hidden" name="search_2" id="search_2" value="1">
				</ul>
			</div>
			<ul class="app-list"></ul>
			<div id="pagination" class="pagination"></div>
			<input id="pagination_setting" type="hidden" maxrn="{$appcount}" prn="5" pid="0" />
		</div>
	</div>
</div>
{include file="../global_js.tpl"}
{literal}
<script>
$().ready(function(){
	$('.nav-sort li, .nav-personal dd').click(function(){
		$('.nav-sort li, .nav-personal dd').removeClass('focus');
		$(this).addClass('focus');
		$('#search_1').val($(this).attr('value'));
		$('.app-list-box .title li').removeClass('focus').eq(0).addClass('focus');
		$('#search_2').val(1);
		pageselectCallback(-1);
	});
	$('.app-list-box .title li').click(function(){
		$('.app-list-box .title li').removeClass('focus');
		$(this).addClass('focus');
		$('#search_2').val($(this).attr('value'));
		pageselectCallback(-1);
	});
	//搜索按钮
	$('#search_3').click(function(){
		$('.app-list-box .title li').removeClass('focus').eq(0).addClass('focus');
		$('.nav-sort li').removeClass('focus').eq(0).addClass('focus');
		$('#search_1').val(0);
		$('#search_2').val(1);
		pageselectCallback(-1);
	});
	//添加应用
	$('.btn-add-s').live('click', function(){
		var appid = $(this).attr('app_id');
		var apptype = $(this).attr('app_type');
		window.parent.HROS.app.add(appid, apptype, function(){
			pageselectCallback($('#pagination_setting').attr('pid'));
			window.parent.HROS.app.get();
		});
	});
	//删除应用
	$('.btn-remove-s').live('click', function(){
		var appid = $(this).attr('app_id');
		var apptype = $(this).attr('app_type');
		window.parent.HROS.app.remove(appid, apptype, function(){
			pageselectCallback($('#pagination_setting').attr('pid'));
			window.parent.HROS.app.get();
		});
	});
	//打开应用
	$('.btn-run-s').live('click', function(){
		if($(this).attr('app_type') == 'app'){
			window.parent.HROS.window.create($(this).attr('app_id'), $(this).attr('app_type'));
		}else{
			window.parent.HROS.widget.create($(this).attr('app_id'), $(this).attr('app_type'));
		}
	});
	//加载列表
	pageselectCallback();
});
function initPagination(cpn){
	$('#pagination').pagination(parseInt($('#pagination_setting').attr('maxrn')), {
		current_page : cpn,
		items_per_page : parseInt($('#pagination_setting').attr('prn')),
		num_display_entries : 4,
		callback : pageselectCallback,
		prev_text : '上一页',
		next_text : '下一页',
		corner : '0'
	});
}
function pageselectCallback(page_id, reset){
	art.dialog({
		lock : true,
		id : 'page',
		esc : false,
		content : '数据加载中...'
	});
	page_id = (page_id == undefined || isNaN(page_id)) ? 0 : page_id;
	if(page_id == -1){
		page_id = 0;
		reset = 1;
	}
	var from = page_id * parseInt($('#pagination_setting').attr('prn')), to = parseInt($('#pagination_setting').attr('prn')); 
	$.ajax({
		type : 'POST',
		url : 'index.php',
		data : 'ac=ajaxGetList&reset=' + reset + '&from=' + from + '&to=' + to + '&search_1=' + $('#search_1').val() + '&search_2=' + $('#search_2').val() + '&search_3=' + $('#keyword').val(),
		success : function(msg){
			var arr = msg.split('<{|*|}>');
			if(parseInt(arr[0], 10) != -1){
				$('#pagination_setting').attr('maxrn', arr[0]);
				initPagination(page_id);
			}
			$('.app-list').html(arr[1]);
			art.dialog.list['page'].close();
		}
	}); 
}
</script>
{/literal}
</body>
</html>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>用户管理</title>
{include file="../global_css.tpl"}
<link rel="stylesheet" href="../../img/ui/sys.css">
{literal}
<style>
body{margin:10px 10px 0}
</style>
{/literal}
</head>

<body>
<div class="well well-small" style="margin-bottom:10px">
	<div class="form-inline">
		<label>用户名：</label>
		<input type="text" name="search_1" id="search_1" class="span2">
		<label style="margin-left:10px">用户类型：</label>
		<select name="search_2" id="search_2" style="width:150px">
			<option value="">全部</option>
			<option value="0">普通会员</option>
			<option value="1">管理员</option>
		</select>
		<a class="btn" menu="search" href="javascript:;" style="margin-left:10px"><i class="icon-search"></i> 搜索</a>
		<a class="btn btn-primary fr" href="detail.php"><i class="icon-white icon-plus"></i> 添加新用户</a>
	</div>
</div>
<table class="list-table">
	<thead>
		<tr class="col-name">
			<th>用户名</th>
			<th style="width:100px">类型</th>
			<th style="width:150px">操作</th>
		</tr>
		<tr class="sep-row"><td colspan="100"></td></tr>
		<tr class="toolbar">
			<td colspan="100">
				<b style="margin:0 10px">符合条件的记录</b>有<font class="list-count">0</font>条
			</td>
		</tr>
		<tr class="sep-row"><td colspan="100"></td></tr>
	</thead>
	<tbody class="list-con"></tbody>
	<tfoot><tr><td colspan="100"><div class="pagination pagination-centered"><ul id="pagination"></ul></div><input id="pagination_setting" type="hidden" maxrn="{$membercount}" prn="15" pid="0"></td></tr></tfoot>
</table>
{include file="../global_js.tpl"}
{literal}
<script>
$().ready(function(){
	//删除
	$('.list-con').on('click', '.do-del', function(){
		var memberid = $(this).attr('memberid');
		var name = $(this).parent().prev().text();
		art.dialog({
			id : 'ajaxedit',
			content : '确定要删除 “' + name + '” 该用户么？',
			ok : function(){
				$.ajax({
					type : 'POST',
					url : 'index.php',
					data : 'ac=ajaxDel&memberid=' + memberid,
					success : function(msg){
						pageselectCallback($('#pagination_setting').attr('pid'));
					}
				});
			},
			cancel: true
		});
	});
	//搜索
	$('a[menu=search]').click(function(){
		pageselectCallback(-1);
	});
	pageselectCallback();
});
function initPagination(cpn){
	$('#pagination').pagination(parseInt($('#pagination_setting').attr('maxrn')), {
		current_page : cpn,
		items_per_page : parseInt($('#pagination_setting').attr('prn')),
		num_display_entries : 4,
		callback : pageselectCallback,
		prev_text : '上一页',
		next_text : '下一页'
	});
}
function pageselectCallback(page_index, reset){
	ZENG.msgbox.show('正在加载中，请稍后...', 6, 100000);
	page_index = (page_index == undefined || isNaN(page_index)) ? 0 : page_index;
	if(page_index == -1){
		page_index = 0;
		reset = 1;
	}
	var from = page_index * parseInt($('#pagination_setting').attr('prn')), to = parseInt($('#pagination_setting').attr('prn')); 
	$.ajax({
		type : 'POST', 
		url : 'index.php', 
		data : 'ac=ajaxGetList&reset=' + reset + '&from=' + from + '&to=' + to + '&search_1=' + $('#search_1').val() + '&search_2=' + $('#search_2').val(),
		success : function(msg){
			var arr = msg.split('<{|*|}>');
			if(parseInt(arr[0], 10) != -1){
				$('#pagination_setting').attr('maxrn', arr[0]);
				$('.list-count').text(arr[0]);
				initPagination(page_index);
			}
			$('.list-con').html(arr[1]);
			ZENG.msgbox._hide();
		}
	}); 
}
</script>
{/literal}
</body>
</html>
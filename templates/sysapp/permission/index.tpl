<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>权限管理</title>
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
		<label>权限名称：</label>
		<input type="text" name="search_1" id="search_1" class="span2">
		<a class="btn" menu="search" href="javascript:;" style="margin-left:10px"><i class="icon-search"></i> 搜索</a>
		<a class="btn btn-primary fr" href="detail.php"><i class="icon-white icon-plus"></i> 创建新权限</a>
	</div>
</div>
<table class="list-table">
	<thead>
		<tr class="col-name">
			<th>权限名称</th>
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
	<tfoot><tr><td colspan="100"><div class="pagination pagination-centered"><ul id="pagination"></ul></div><input id="pagination_setting" type="hidden" maxrn="{$permissionscount}" prn="15" pid="0"></td></tr></tfoot>
</table>
{include file="../global_js.tpl"}
{literal}
<script>
$().ready(function(){
	//删除
	$('.list-con').on('click', '.do-del', function(){
		var permissionsid = $(this).attr('permissionsid');
		var name = $(this).parent().prev().text();
		art.dialog({
			id : 'ajaxedit',
			content : '确定要删除 “' + name + '” 该权限么？',
			ok : function(){
				$.ajax({
					type : 'POST',
					url : 'index.php',
					data : 'ac=ajaxDel&permissionsid=' + permissionsid,
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
	pageselectCallback(0);
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
function pageselectCallback(page_id,reset){
	ZENG.msgbox.show('正在加载中，请稍后...', 6, 100000);
	page_id = (page_id == undefined || isNaN(page_id)) ? 0 : page_id;
	if(page_id == -1){
		page_id = 0;
		reset = 1;
	}
	var from = page_id * parseInt($('#pagination_setting').attr('prn')), to = parseInt($('#pagination_setting').attr('prn')); 
	$.ajax({
		type : 'POST', 
		url : 'index.php', 
		data : 'ac=ajaxGetList&reset=' + reset + '&from=' + from + '&to=' + to + '&search_1=' + $('#search_1').val(),
		success : function(msg){
			var arr = msg.split('<{|*|}>');
			if(parseInt(arr[0], 10) != -1){
				$('#pagination_setting').attr('maxrn', arr[0]);
				initPagination(page_id);
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
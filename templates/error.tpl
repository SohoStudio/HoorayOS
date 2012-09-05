<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>啊，出错了</title>
{include file="sysapp/global_css.tpl"}
<link rel="stylesheet" href="../../img/ui/sys.css">
</head>

<body>
{include file="sysapp/global_js.tpl"}
{if $code == $errorcode.noLogin}
<script type="text/javascript">
$(function(){
	window.parent.ZENG.msgbox.show("对不起，您还没有登入！", 1, 2000);
});
</script>
{elseif $code == $errorcode.noAdmin}
<script type="text/javascript">
$(function(){
	window.parent.ZENG.msgbox.show("对不起，您不是管理员！", 1, 2000);
});
</script>
{elseif $code == $errorcode.noPermissions}
<script type="text/javascript">
$(function(){
	window.parent.ZENG.msgbox.show("对不起，您没有权限操作！", 1, 2000);
});
</script>
{/if}
</body>
</html>

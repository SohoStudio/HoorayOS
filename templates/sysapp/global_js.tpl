<script src="../../js/jquery-1.8.1.min.js"></script>
<script src="../../js/bootstrap/js/bootstrap.min.js"></script>
<script src="../../js/HoorayLibs/hooraylibs.js"></script>
<script src="../../js/artDialog4.1.6/jquery.artDialog.js?skin=default"></script>
<script src="../../js/artDialog4.1.6/plugins/iframeTools.js"></script>
<script>
$(function(){
	//$('.tip').colorTip();
	//配置artDialog全局默认参数
	(function(config){
		config['lock'] = true;
		config['fixed'] = true;
		config['resize'] = false;
		config['background'] = '#000';
		config['opacity'] = 0.5;
	})($.dialog.defaults);
});
</script>
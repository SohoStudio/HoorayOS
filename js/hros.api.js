var HROS = {};

HROS.api = (function(){
	return {
		close : function(id, callback){
			$('#w_papp_' + id + ' .ha-close').on('click', callback);
		}
	}
})();
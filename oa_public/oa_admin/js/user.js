var user = function(){
	var init = function(){
		$(".Huiform").Validform({
			tiptype : 4,
			tipSweep : true
		});
	};
	
	var del = function(event){
		var did = $(event.currentTarget).attr('did');
		msg = '确定删除吗?';
		layer.confirm(msg,function(index){
		    window.location.href = $('#delUrl').val()+'?id='+did;
		});
		return false;		
	};

	init();
}();
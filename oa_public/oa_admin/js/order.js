var order = function(){
	var init = function(){
		$(".Huiform").Validform({
			tiptype : 4,
			tipSweep : true
		});
		$(".del").click(del);
		$(".ship").click(ship);
		$(".checkShip").click(checkShip);
	};
	
	var del = function(event){
		var did = $(event.currentTarget).attr('did');
		msg = '订单删除后无法恢复，确定删除吗?';
		layer.confirm(msg,function(index){
		    window.location.href = $('#delUrl').val()+'?id='+did;
		});
		return false;		
	};
	
	var ship = function(event){
		var oid = $(event.currentTarget).attr('oid');
		$('#soid').val(oid);
		layer.open({
		    type: 1,
		    area: '500px',
		    title: [
		        '订单'+oid+'发货',
		        'border:none; background:#61BA7A; color:#fff;' 
		    ],
		    bgcolor: '#eee', //设置层背景色
		    content: $('#orderShip')
		});
		return false;		
	};
	
	var checkShip = function(event){
		var sid = $(event.currentTarget).attr('sid');
		layer.open({
		    type: 1,
		    area: '500px',
		    title: [
		        '订单'+sid+'物流信息',
		        'border:none; background:#61BA7A; color:#fff;' 
		    ],
		    bgcolor: '#eee', //设置层背景色
		    content: $('#shipInfo')
		});
		return false;		
	};

	init();
}();
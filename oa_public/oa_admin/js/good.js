var good = function(){
	var init = function(){
		$(".Huiform").Validform({
			tiptype : 4,
			tipSweep : true
		});
		$('.del').click(del);
		$('.delsta').click(delsta);
        $('.addsta').click(addsta);
        $('.delsku').click(delsku);
        $('.addsku').click(addsku);
        $('#shipping_type').change(shippingShow);
	};
	var del = function(event){
		var did = $(event.currentTarget).attr('did');
		var checkUrl = $('#checkUrl').val()+'?id='+did;
		if(checkUrl){
			$.ajax({
	            type: "GET",
	            url: checkUrl,
	            dataType: "json",
	            success: function(data){
	                var msg = '';
	            	if(data.result == 1){
	            	    layer.msg('该项已被商品使用，无法删除');           	    
	            	}else if(data.result == -1){
	            		layer.msg('你没有删除权限'); 
	            	}else{
	            		msg = '确定删除吗?';
	            		layer.confirm(msg,function(index){
	            		    window.location.href = $('#delUrl').val()+'?id='+did;
	            		});
	            	} 
	            	return false;
	            }
	        });
		}else{
			msg = '确定删除吗?';
    		layer.confirm(msg,function(index){
    		    window.location.href = $('#delUrl').val()+'?id='+did;
    		});
    		return false;
		}
		
	};
	
	var delsta = function(event){
        var index = $(".delsta").index(event.currentTarget);
        var oid = $(event.currentTarget).attr("oid");
        if(oid){
        	if(!window.confirm("是否确定删除？")){
        		return false;
        	}
        }
        $("#sta li").eq(index).remove();
    };

    var addsta = function(){
        var html = '<li style="margin-bottom:5px;"><input name="scat[]" style="width:40%;" type="text" class="input-text" >&nbsp;<span><a href="javascript:void(0)" class="delsta">&times;</a></span></li>';
        $("#sta").append(html);
        $('.delsta').unbind('click');
        $('.delsta').click(delsta);
    };
    
    var delsku = function(event){
        var index = $(".delsku").index(event.currentTarget);
        var oid = $(event.currentTarget).attr("oid");
        if(oid){
        	if(!window.confirm("是否确定删除？")){
        		return false;
        	}
        }
        $("#goodsku li").eq(index+1).remove();
    };
    
    var addsku = function(){
        var html = '<li style="margin-bottom:5px;">SKU号：<input name="gskuno[]" style="width:10%;" type="text" class="input-text" value="">&nbsp;描述：<input name="gskuinfo[]" style="width:10%;" type="text" class="input-text" value="">&nbsp;数量：<input name="gskunum[]" style="width:10%;" type="text" class="input-text" value="">&nbsp;售价：<input name="gskuprice[]" style="width:10%;" type="text" class="input-text" value="">&nbsp;成本：<input name="gskucost[]" style="width:10%;" type="text" class="input-text" value="">&nbsp;<span><a href="javascript:void(0)" class="delsku">&times;</a></span></li>';
        $("#goodsku").append(html);
        $('.delsku').unbind('click');
        $('.delsku').click(delsku);
    };
    
    var shippingShow = function(event){
        var type = $(event.currentTarget).val();
        if(type==1){
        	$('.otype').show();
        	$('.ntype').show();
        }else if(type==2){
        	$('.otype').hide();
        	$('.ntype').show();
        }else{
        	$('.otype').hide();
        	$('.ntype').hide();
        }
    };

	init();
}();
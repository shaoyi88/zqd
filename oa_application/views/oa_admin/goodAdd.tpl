<link href="/oa_public/common/js/umeditor/themes/default/css/umeditor.css" type="text/css" rel="stylesheet">
<link href="/oa_public/oa_admin/css/good.css" type="text/css" rel="stylesheet">
<script type="text/javascript" src="/oa_public/common/js/umeditor/umeditor.config.js"></script>
<script type="text/javascript" src="/oa_public/common/js/umeditor/umeditor.min.js"></script>
<script src="/oa_public/common/js/jupload/js/vendor/jquery.ui.widget.js"></script>
<script src="/oa_public/common/js/jupload/js/jquery.iframe-transport.js"></script>
<script src="/oa_public/common/js/jupload/js/jquery.fileupload.js"></script>
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i>  商品管理 <span class="c-gray en">&gt;</span> {$typeMsg}</nav>
<div class="pd-20">
	<form class="Huiform" id="form-good-add" action="{formatUrl('good/doAdd')}" method="post">
		{if isset($info)}
		<input name="good_id" type="hidden" value="{$info['good_id']}">
		{/if}
		<table class="table table-border table-bordered table-bg">
      		<tbody>
      			<tr>
          		     <th class="text-r" width="80"><span class="c-red">*</span>商品名：</th>
          			 <td><input name="good_name" type="text" class="input-text" id="good_name" value="{if isset($info)}{$info['good_name']}{/if}" nullmsg="商品名不能为空！" datatype="*"></td>
        		</tr>
        		<tr>
          		     <th class="text-r" width="80"><span class="c-red">*</span>重量(克)：</th>
          			 <td><input name="good_weight" type="text" class="input-text" id="good_weight" value="{if isset($info)}{$info['good_weight']}{/if}" nullmsg="商品重量不能为空！" datatype="n"></td>
        		</tr>
        		<tr>
          			<th class="text-r"><span class="c-red">*</span>类型：</th>
          			<td>
          			    <select class="select-box" id="good_type" name="good_type" nullmsg="类型不能为空！" datatype="*">
      						<option value="">请选择类型</option>
      						{foreach $good_type as $k=>$item}
      						<option value="{$k}" {if isset($info) && $info['good_type'] == $k}selected{/if}>
      						{$item}
      						</option>
      						{/foreach}
    					</select>
          			</td>
          		</tr>
          		<tr>
          			<th class="text-r"><span class="c-red">*</span>分类：</th>
          			<td>
          			    <select class="select-box" id="good_category" name="good_category" nullmsg="分类不能为空！" datatype="*">
      						<option value="">请选择类型</option>
      						{foreach $catTree as $k=>$item}
      						<option value="{$item['cat_id']}" {if isset($info) && $info['good_category'] == {$item['cat_id']}}selected{/if}>
      						{if $item['level'] > 0}{str_repeat('&nbsp', $item['level']*2)}{/if}{$item['cat_name']}
      						</option>
      						{/foreach}
    					</select>
          			</td>
          		</tr>
          		<tr>
          			<th class="text-r"><span class="c-red">*</span>商品主图：</th>
          			<td>         			
          			<a class="input-file-a btn btn-primary radius" href="javascript:;">
          			<i class="Hui-iconfont">&#xe600;</i> 添加图片
          			<input class="input-file-f" id="mainPicupload" type="file" name="image" data-url="{formatUrl('good/uploadMainPic')}" multiple>
          			</a>
          			<div class="files">
          			{if isset($info) && !empty($mainImg)}
          			{foreach $mainImg as $v}
          			<div class="images_zone"><input type="hidden" name="imgs[]" value="{$v}" /><span><img src="{$v}"  /></span><a href="javascript:;">删除</a></div>
          			{/foreach}
          			{/if}
          			</div>
					<div class="up_progress">
					  <div class="progress-bar"></div>
					</div>
					<div style="clear:both;"></div>
          			</td>
          		</tr>
          		<tr>
          			<th class="text-r"><span class="c-red">*</span>邮费模板：</th>
          			<td>
          			    <select class="select-box" id="good_delivery_info" name="good_delivery_info" nullmsg="模板不能为空！" datatype="*">
      						<option value="">请选择</option>
      						{foreach $shipping as $item}
      						<option value="{$item['shipping_id']}" {if isset($info) && $info['good_delivery_info'] == {$item['shipping_id']}}selected{/if}>
      						{$item['shipping_name']}
      						</option>
      						{/foreach}
    					</select>
          			</td>
          		</tr>
          		<tr>
          			<th class="text-r va-t"><span class="c-red">*</span>商品详情：</th>
          			<td>
					    <script id="container" name="good_detail" type="text/plain" style="width:95%;height:240px;">{if isset($info)}{$info['good_detail']}{/if}</script>
					    <script type="text/javascript">
					        var um = UM.getEditor('container');
					    </script>
          			</td>
          		</tr>
          		<tr>
          			<th class="text-r"><span class="c-red">*</span>商品属性：</th>
          			<td>        			
          			<ul id="goodsku">
          			{if isset($info)}
          			{foreach $sku[$info['good_id']] as $k=>$v}
          			<li style="margin-bottom:5px;">
          			<input type="hidden" name="gskushow[]" value="{$v['sku_show']}">
          			SKU号：<input name="gskuno[]" style="width:10%;" type="text" class="input-text" value="{$v['sku_no']}" nullmsg="SKU号不能为空！" datatype="*">&nbsp;
          			描述：<input name="gskuinfo[]" style="width:10%;" type="text" class="input-text" value="{$v['sku_info']}" nullmsg="描述不能为空！" datatype="*">&nbsp;
        		        数量：<input name="gskunum[]" style="width:10%;" type="text" class="input-text" value="{$v['sku_num']}" nullmsg="数量不能为空！" datatype="*">&nbsp;
        		        售价：<input name="gskuprice[]" style="width:10%;" type="text" class="input-text" value="{$v['sku_price']}" nullmsg="售价不能为空！" datatype="*">&nbsp;
        		        成本：<input name="gskucost[]" style="width:10%;" type="text" class="input-text" value="{$v['sku_cost']}" nullmsg="成本不能为空！" datatype="*">&nbsp;
        		    {if $k>0}
        		    <span><a href="javascript:void(0)" class="delsku">&times;</a></span>
        		    {/if}
        		    </li>      		    
        		    {/foreach}
        		    {else} 
        		    SKU号：<input name="gskuno[]" style="width:10%;" type="text" class="input-text" value="" nullmsg="SKU号不能为空！" datatype="*">&nbsp;
          			描述：<input name="gskuinfo[]" style="width:10%;" type="text" class="input-text" value="" nullmsg="描述不能为空！" datatype="*">&nbsp;
        		        数量：<input name="gskunum[]" style="width:10%;" type="text" class="input-text" value="" nullmsg="数量不能为空！" datatype="*">&nbsp;
        		        售价：<input name="gskuprice[]" style="width:10%;" type="text" class="input-text" value="" nullmsg="售价不能为空！" datatype="*">&nbsp;
        		        成本：<input name="gskucost[]" style="width:10%;" type="text" class="input-text" value="" nullmsg="成本不能为空！" datatype="*">&nbsp;
        		    {/if}      		    
          			</ul>
          			<p><a class="btn btn-primary radius addsku" href="javascript:void(0);"><i class="Hui-iconfont">&#xe600;</i></a><span></p>
          			</td>
          		</tr>
          		<tr>
          			<th>&nbsp;</th>
          			<td>
            			<button type="submit" class="btn btn-success radius"><i class="icon-ok"></i> {$typeMsg}</button>
          			</td>
        		</tr>
      		</tbody>
      	</table>
	</form>
</div>
<script type="text/javascript">
$("#mainPicupload").fileupload({
    dataType: 'json',
    add: function (e, data) {
      if(e.isDefaultPrevented()) {
         return false;
      }
      var numItems = $('.files .images_zone').length;
      if(numItems>=5){
        alert('提交图片不能超过5张');
        return false;
      }
      $('.up_progress .progress-bar').css('width','0px');
      $('.up_progress').show();
      $('.up_progress .progress-bar').html('正在上传...');
      data.submit();
    },
    done: function (e, data) {
      $('.up_progress').hide();
      $('.upl').remove();
      var d = data.result;
      if(d.status==0){
        alert(d.msg);
      }else{
        var imgshow = '<div class="images_zone"><input type="hidden" name="imgs[]" value="'+d.msg+'" /><span><img src="'+d.msg+'"  /></span><a href="javascript:;">删除</a></div>';
        $('.files').append(imgshow);
      }
	  $('.images_zone a').on('click',function(){
	    $(this).parent().remove();
	  });
    },
    progressall: function (e, data) {
      console.log(data);
      var progress = parseInt(data.loaded / data.total * 100, 10);
      $('.up_progress .progress-bar').css('width',progress + '%');
    }
  });
</script>
<script type="text/javascript" src="/oa_public/oa_admin/js/good.js"></script>
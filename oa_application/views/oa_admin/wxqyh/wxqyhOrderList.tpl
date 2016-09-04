<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 订单管理<span class="c-gray en">&gt;</span> 订单列表 <a class="btn btn-success radius r mr-20" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
{if isset($msg)}
<div class="header">
	<div class="Huialert Huialert-danger"><i class="icon-remove"></i>{$msg}</div>
</div>
{/if}
<div class="pd-20">
	<form class="Huiform" action="{formatUrl('order/index')}" method="get">
		<div class="text-c"> 
			<input type="text" value="{if isset($keyword['user_name'])}{$keyword['user_name']}{/if}" class="input-text" style="width:250px" placeholder="输入会员姓名" id="" name="user_name">
			<button type="submit" class="btn btn-success" id="" name=""><i class="Hui-iconfont">&#xe665;</i> 搜订单</button>
		</div>
	</form>
	{if checkRight('order_add')}
	<div class="cl pd-5 bg-1 bk-gray mt-20"> 
		<span class="l">
			<a href="{formatUrl('order/add')}" class="btn btn-primary radius"><i class="Hui-iconfont">&#xe600;</i> 添加订单</a>
		</span>
	 </div>
	 {/if}
	 {if empty($dataList)}
	 	<div class="cl pd-5 bg-1 bk-gray"><h2 class="text-c">暂无订单</h2></div>
	 {else}
	 	<table class="table table-border table-bg table-bordered table-hover">
			<thead>
        		<tr class="text-c">
        		    <th>订单号</th>
          			<th>会员</th>
          			<th>地址</th>
          			<th>金额</th>
          			<th>下单时间</th>
          			<th>状态</th>
          			<th width="250">操作</th>
        		</tr>
      		</thead>
      		<tbody>
			{foreach $dataList as $item}
				<tr class="text-c">
				    <td>{$item['order_no']}</td>
        			<td>{if $item['user_name']}{$item['user_name']}{else}$item['user_nickname']}{/if}</td>
        			<td>{$area[$item['province']]}&nbsp;{$area[$item['city']]}&nbsp;{$area[$item['area']]}&nbsp;{$item['address']}，{$item['username']}，{$item['userphone']}</td>
        			<td>{$item['pay_fee']}</td>
        			<td>{date('Y-m-d H:i:s',$item['order_time'])}</td>
        			<td>{$status[$item['order_status']]}</td>
          			<td>
          			    {if checkRight('order_list')}<a class="btn btn-primary radius" title="详情" href="{formatUrl('order/view?id=')}{$item['order_no']}" style="text-decoration:none">详情</a>&nbsp;&nbsp;{/if}        			    
          			    {if checkRight('order_del')}<a class="btn btn-primary radius del" did="{$item['order_no']}" title="删除" href="javascript:;" style="text-decoration:none">删除</a>&nbsp;&nbsp;{/if}
          			    {if checkRight('order_ship')&&$item['order_status']==2}<a class="btn btn-primary radius ship" oid="{$item['order_no']}" title="发货" href="javascript:;" style="text-decoration:none">发货</a>&nbsp;&nbsp;{/if}
          			    {if checkRight('order_list')&&$item['order_status']>2&&$item['order_status']<6}<a class="btn btn-primary radius checkShip" sid="{$item['order_no']}" title="物流" href="javascript:;" style="text-decoration:none">物流</a>&nbsp;&nbsp;{/if}
          			</td>
          		</tr>
			{/foreach}
			</tbody>
		</table>
	 {/if}
	 {if isset($pageUrl)}{$pageUrl}{/if}
</div>
<div class="pd-20 text-c" style="display:none" id="orderShip">
<form class="Huiform" id="form-order-ship" action="{formatUrl('order/doSend')}" method="post">
	<table class="table table-bg table-border table-bordered">
	    <tr>
  		     <th class="text-r" width="100">快递公司：</th>
  			 <td>
  			     <select class="select-box" name="shipping">
  			     {foreach $shipping as $k=>$v}
  			     <option value="{$k}">{$v[0]}</option>
  			     {/foreach}
  			     </select>
  			 </td>
		</tr>
		<tr>
  		     <th class="text-r">快递单号：</th>
  			 <td><input class="input-text" type="text" name="shipping_no" datatype="*" nullMsg="快递单号不能为空"></td>
		</tr>
		<tr>
  		     <th class="text-r">&nbsp;</th>
  			 <td>
  			 <input type="hidden" id="soid" name="soid">
  			 <input class="btn btn-primary radius" type="submit" value="发货">
  			 </td>
		</tr>		
	</table>
</form>
</div>
<div class="pd-20 text-c" style="display:none" id="shipInfo">
	<table class="table table-bg table-border table-bordered">
	    <tr>
  			 <td class="text-l shiparea">暂无物流信息</td>
		</tr>		
	</table>
</div>
<script type="text/javascript" src="/oa_public/oa_admin/js/order.js?v=108"></script>
<input type="hidden" id="delUrl" value="{formatUrl('order/doDel')}"></input>
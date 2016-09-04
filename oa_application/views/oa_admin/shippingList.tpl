<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 邮费模板管理<span class="c-gray en">&gt;</span> 模板列表 <a class="btn btn-success radius r mr-20" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
{if isset($msg)}
<div class="header">
	<div class="Huialert Huialert-danger"><i class="icon-remove"></i>{$msg}</div>
</div>
{/if}
<div class="pd-20">
	{if checkRight('shipping_add')}
	<div class="cl pd-5 bg-1 bk-gray mt-20"> 
		<span class="l">
			<a href="{formatUrl('good/addShipping')}" class="btn btn-primary radius"><i class="Hui-iconfont">&#xe600;</i> 添加模板</a>
		</span>
	 </div>
	 {/if}
	 {if empty($dataList)}
	 	<div class="cl pd-5 bg-1 bk-gray"><h2 class="text-c">暂无模板</h2></div>
	 {else}
	 	<table class="table table-border table-bg table-bordered table-hover">
			<thead>
        		<tr class="text-c">
        		    <th>序号</th>
        		    <th>模板名称</th>
        		    <th>快递公司</th>
        		    <th>首重</th>
        		    <th>续重</th>
        		    <th>类型</th>
        		    <th>内容</th>
        		    <th>条件</th>
          			<th>是否默认</th>
          			<th width=220>操作</th>
        		</tr>
      		</thead>
      		<tbody>
			{foreach $dataList as $k=>$item}
				<tr class="text-c">
				    <td>{$k+1}</td>
        			<td>{$item['shipping_name']}</td>
        			<td>{$postage[$item['shipping_postage']][0]}</td>
        			<td>{$item['shipping_first_weight']}KG</td>
        			<td>{$item['shipping_additional_weight']}KG</td>
        			<td>{$type[$item['shipping_type']]}</td>
        			<td>{if $item['shipping_content']}<a href="javascript:;" class="getShippingContent">查看</a>{else}-{/if}</td>
        			<td>{if $item['shipping_condition']}{$item['shipping_condition']}{/if}</td>
        			<td>{if $item['is_default']==1}默认{else}-{/if}</td>
          			<td>
          			    {if checkRight('shipping_edit')}
          			    {if $item['is_default']==1}
          			    <a class="btn btn-primary radius cancleDefault" title="取消默认" href="javascript:;" style="text-decoration:none" id="{$item['shipping_id']}">取消默认</a>&nbsp;&nbsp;
          			    {else}
          			    <a class="btn btn-primary radius setDefault" title="设为默认" href="javascript:;" style="text-decoration:none" id="{$item['shipping_id']}">设为默认</a>&nbsp;&nbsp;
          			    {/if}
          			    {/if}
          				{if checkRight('shipping_edit')}<a class="btn btn-primary radius edit" title="编辑" href="{formatUrl('good/addShipping?sid=')}{$item['shipping_id']}" style="text-decoration:none">编辑</a>{/if}
          				{if checkRight('shipping_del')}<a class="btn btn-primary radius ml-5 del" did="{$item['shipping_id']}" title="删除" href="javascript:;" style="text-decoration:none">删除</a>{/if}
          			</td>
          		</tr>
			{/foreach}
			</tbody>
		</table>
	 {/if}
	 {if isset($pageUrl)}{$pageUrl}{/if}
</div>
<script type="text/javascript" src="/oa_public/oa_admin/js/goods.js"></script>
<input type="hidden" id="checkUrl" value="{formatUrl('good/checkShipping')}"></input>
<input type="hidden" id="delUrl" value="{formatUrl('good/doDelShipping')}"></input>